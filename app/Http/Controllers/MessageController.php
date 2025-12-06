<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Message;
use App\Models\Customer;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvalidAmountNotification;
use App\Mail\InvalidAmountAdminNotification;
use App\Models\BalanceTransaction;

class MessageController extends Controller
{
    
    public function index()
    {
        $query = Message::query();
        if (request('sender')) {
            $query->where('sender', request('sender'));
        }

        if (request('status')) {
            $query->where('status', request('status'));
        }

        $messages = $query->orderByDesc('id')->paginate();
        return view('messages.index', compact('messages'));
    }

    public function receive_sms(Request $request)
    {
        $data = $request->all();

        $from = $data['from'] ?? null;
        $messageContent = $data['content'] ?? '';
        $timestampString = $data['date'] ?? now();

        Log::info("Message received from $from");
        Log::info($data);

        $timestamp = $this->convertTimestampForMySQL($timestampString);

        $patterns = [
            'Libyana' => '/تم تحويل\s+([\d\.]+)\s+دينار من الرقم\s+(\d+) إلى رصيدك بنجاح\./',
            'Almadar' => '/المشترك الكريم,لقد تم تحويل\s*([\d\.]+)\s*د\.ل الي رصيدك من الرقم\s*(\d+)\s*,?\s*رصيدك الحالي\s*([\d\.]+)\s*د\.ل/',
            'RED' => '/لقد إستلمت\s+([\d,\.]+)\s*د\s+من الرقم\s+(\d+)\s+في\s+[\d\-]+\.رصيدك الحالي هو\s+([\d,\.]+)\s*د\./',
            'VF-Cash' => '/تم استلام مبلغ\s+([\d\.]+)\s*جنيه\s+من رقم\s+(\d+)\s*؛[^;]*رصيدك الحالي\s+([\d\.]+)/',
        ];

        $type = null;
        $value = null;
        $sourceNumber = null;
        $currentBalance = null;
        $account = null;
        $status = false;

        DB::beginTransaction();

        try {
            if (array_key_exists($from, $patterns) && preg_match($patterns[$from], $messageContent, $matches)) {
                $type = $from;

                $content = Content::first();

                if ($from === 'Libyana') {
                    $value = floatval($matches[1]);
                    $sourceNumber = $matches[2];

                    Log::info("Libyana Message - Value: $value دينار, Source Number: $sourceNumber");

                    $normalizedSourceNumber = $this->normalizePhoneNumber($sourceNumber);

                    $customer = Customer::where('phone_number', $normalizedSourceNumber)->first();

                    if ($customer) {
                        $pointCost = $content->point_cost_libyana ?? $content->point_cost ?? 0.01;
                        $points = round($value / $pointCost);

                        $balanceBefore = $customer->balance;
                        $customer->balance += $points;
                        $customer->save();

                        BalanceTransaction::create([
                            'customer_id' => $customer->id,
                            'type' => 'transfer_in',
                            'amount' => $points,
                            'balance_before' => $balanceBefore,
                            'balance_after' => $customer->balance,
                            'description' => "تحويل رصيد Libyana من الرقم $sourceNumber بقيمة $value د.ل",
                        ]);

                        $status = true;
                        $account = $customer->id;

                        Log::info("Customer balance updated by $points points (from $value د.ل ÷ $pointCost point cost)");
                    } else {
                        Log::warning("Customer not found for phone number: $normalizedSourceNumber");
                    }
                } elseif ($from === 'Almadar') {
                    $value = floatval($matches[1]);
                    $sourceNumber = $matches[2];
                    $currentBalance = floatval($matches[3]);

                    Log::info("Almadar Message - Value: $value د.ل, Source Number: $sourceNumber, Current Balance: $currentBalance د.ل");

                    $normalizedSourceNumber = $this->normalizePhoneNumber($sourceNumber);

                    $customer = Customer::where('phone_number', $normalizedSourceNumber)->first();

                    if ($customer) {
                        $pointCost = $content->point_cost_almadar ?? $content->point_cost ?? 0.01;
                        $points = round($value / $pointCost);

                        $balanceBefore = $customer->balance;
                        $customer->balance += $points;
                        $customer->save();

                        BalanceTransaction::create([
                            'customer_id' => $customer->id,
                            'type' => 'transfer_in',
                            'amount' => $points,
                            'balance_before' => $balanceBefore,
                            'balance_after' => $customer->balance,
                            'description' => "تحويل رصيد Almadar من الرقم $sourceNumber بقيمة $value د.ل",
                        ]);

                        $status = true;
                        $account = $customer->id;

                        Log::info("Customer balance updated by $points points (from $value د.ل ÷ $pointCost point cost)");
                    } else {
                        Log::warning("Customer not found for phone number: $normalizedSourceNumber");
                    }
                } elseif ($from === 'RED') {
                    // Remove commas from numbers (e.g., "55,000" -> "55000")
                    $value = floatval(str_replace(',', '', $matches[1]));
                    $sourceNumber = $matches[2];
                    $currentBalance = floatval(str_replace(',', '', $matches[3]));

                    Log::info("RED Message - Value: $value د, Source Number: $sourceNumber, Current Balance: $currentBalance د");

                    $normalizedSourceNumber = $this->normalizePhoneNumber($sourceNumber);

                    $customer = Customer::where('phone_number', $normalizedSourceNumber)->first();

                    if ($customer) {
                        $pointCost = $content->point_cost_red ?? $content->point_cost ?? 0.01;
                        $points = round($value / $pointCost);

                        $balanceBefore = $customer->balance;
                        $customer->balance += $points;
                        $customer->save();

                        BalanceTransaction::create([
                            'customer_id' => $customer->id,
                            'type' => 'transfer_in',
                            'amount' => $points,
                            'balance_before' => $balanceBefore,
                            'balance_after' => $customer->balance,
                            'description' => "تحويل رصيد RED من الرقم $sourceNumber بقيمة $value د",
                        ]);

                        $status = true;
                        $account = $customer->id;

                        Log::info("Customer balance updated by $points points (from $value د ÷ $pointCost point cost)");
                    } else {
                        Log::warning("Customer not found for phone number: $normalizedSourceNumber");
                    }
                } elseif ($from === 'VF-Cash') {
                    $value = floatval($matches[1]);
                    $sourceNumber = $matches[2];
                    $currentBalance = floatval($matches[3]);

                    Log::info("VF-Cash Message - Value: $value جنيه, Source Number: $sourceNumber, Current Balance: $currentBalance جنيه");

                    $normalizedSourceNumber = $this->normalizePhoneNumber($sourceNumber);

                    $customer = Customer::where('phone_number', $normalizedSourceNumber)->first();

                    if ($customer) {
                        $pointCost = $content->point_cost_vfcash ?? $content->point_cost ?? 0.01;
                        $points = round($value / $pointCost);

                        $balanceBefore = $customer->balance;
                        $customer->balance += $points;
                        $customer->save();

                        BalanceTransaction::create([
                            'customer_id' => $customer->id,
                            'type' => 'transfer_in',
                            'amount' => $points,
                            'balance_before' => $balanceBefore,
                            'balance_after' => $customer->balance,
                            'description' => "تحويل رصيد VF-Cash من الرقم $sourceNumber بقيمة $value جنيه",
                        ]);

                        $status = true;
                        $account = $customer->id;

                        Log::info("Customer balance updated by $points points (from $value جنيه ÷ $pointCost point cost)");
                    } else {
                        Log::warning("Customer not found for phone number: $normalizedSourceNumber");
                    }
                }

                Message::create([
                    'sender' => $from,
                    'message' => $messageContent,
                    'timestamp' => $timestamp,
                    'status' => $status,
                    'value' => $value,
                    'customer_id' => $account,
                    'source_number' => $sourceNumber,
                    'type' => $type,
                ]);

            } else {
                Log::warning("Message from $from does not match expected patterns and will not be stored.");
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to process message: " . $e->getMessage());
        }

        return response()->json(['status' => 'success']);
    }

  
    private function normalizePhoneNumber($phoneNumber)
    {
        // Remove whitespace
        $phoneNumber = preg_replace('/\s+/', '', $phoneNumber);

        // Remove non-digit characters except +
        $phoneNumber = preg_replace('/[^\d\+]/', '', $phoneNumber);

        // Store original for searching
        $originalNumber = $phoneNumber;

        // Remove leading + if present
        $phoneNumber = ltrim($phoneNumber, '+');

        // Common country codes to try removing for matching
        $countryCodes = ['218', '966', '971', '20', '962', '965', '968', '973', '974', '212', '216', '213'];

        $possibleNumbers = [$originalNumber, $phoneNumber];

        foreach ($countryCodes as $code) {
            if (strpos($phoneNumber, $code) === 0) {
                $withoutCode = substr($phoneNumber, strlen($code));
                $possibleNumbers[] = $withoutCode;
                $possibleNumbers[] = '0' . $withoutCode;
            }
        }

        // Libya specific: if starts with 9, add 0
        if (strpos($phoneNumber, '9') === 0 && strlen($phoneNumber) == 9) {
            $possibleNumbers[] = '0' . $phoneNumber;
        }

        // Try to find customer with any of the possible number formats
        foreach ($possibleNumbers as $num) {
            $customer = Customer::where('phone_number', $num)->first();
            if ($customer) {
                Log::info([$num, 'MATCHED', $customer]);
                return $num;
            }
            // Also try with + prefix
            $customer = Customer::where('phone_number', '+' . $num)->first();
            if ($customer) {
                Log::info(['+' . $num, 'MATCHED', $customer]);
                return '+' . $num;
            }
        }

        Log::info([$phoneNumber, 'NO MATCH FOUND']);

        return $phoneNumber;
    }

    
    private function convertTimestampForMySQL($timestampString)
    {
        try {
            return Carbon::parse($timestampString)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            Log::error("Failed to parse timestamp: " . $timestampString . " Error: " . $e->getMessage());
            return Carbon::now()->format('Y-m-d H:i:s');
        }
    }

    private function sendInvalidAmountEmail($customer, $invalidValue)
    {
        $email = $customer->email;

        if ($email) {
            try {
                Mail::to($email)->send(new InvalidAmountNotification($customer, $invalidValue));

                Log::info("Invalid amount email sent to $email");
            } catch (\Exception $e) {
                Log::error("Failed to send email to $email: " . $e->getMessage());
            }
        } else {
            Log::warning("Customer email not found for customer ID: {$customer->id}");
        }

        $adminEmail = env('ADMIN_EMAIL');

        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new InvalidAmountAdminNotification($customer, $invalidValue));
            } catch (\Exception $e) {
                Log::error("Failed to send email to admin: " . $e->getMessage());
            }
        } else {
            Log::warning("ADMIN_EMAIL is not set in the .env file.");
        }
    }
}
