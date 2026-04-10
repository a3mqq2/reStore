<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Variant;
use App\Models\VariantPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MoogoldController extends Controller
{
    /*-------------------------------------------------
    | Credentials
    -------------------------------------------------*/
    private string $baseUrl; // Base URL
    private string $partnerId;   // API Key
    private string $secretKey;                        // Secret Key




    public function __construct()
    {
        $this->baseUrl = config('services.moogold.base_url');
        $this->partnerId = config('services.moogold.partner_id');
        $this->secretKey = config('services.moogold.secret_key');
    }

    /*-------------------------------------------------
    | Public End-points
    -------------------------------------------------*/

    /**
     * List all products (or filter by category if provided).
     */
    public function getProductList($categoryId = 1)
    {
        $payload = [
            'path' => 'product/list_product',
            'category_id' => $categoryId
        ];

        return $this->callMoogold($payload, 'product/list_product');
    }

    /**
     * Fetch detailed info about a single product.
     */
    public function getProductDetail($id)
    {
        $payload = [
            "path" => "product/product_detail",
            'product_id' => $id
        ];

        return $this->callMoogold($payload, 'product/product_detail');
    }

    /**
     * Check current partner balance / points.
     */
    public function getBalance()
    {
        try {
            $payload = [
                'path' => 'user/balance',
                'data' => [],
            ];

            $response = $this->callMoogold($payload, 'user/balance');

            if ($response->failed()) {
                Log::error('Moogold API Error - getBalance', [
                    'status' => $response->status()
                ]);
                return ['status' => 'error', 'balance' => 0, 'message' => 'فشل الاتصال بخدمة Moogold'];
            }

            $result = $response->json();

            if (!isset($result['balance'])) {
                Log::warning('Moogold API - Invalid Response', ['response' => $result]);
                return ['status' => 'error', 'balance' => 0];
            }

            return $result;

        } catch (\Exception $e) {
            Log::error('Moogold Error - getBalance', ['error' => $e->getMessage()]);
            return ['status' => 'error', 'balance' => 0, 'message' => 'حدث خطأ في الاتصال'];
        }
    }

    /**
     * Create purchase / top-up order.
     */
    public function createOrder(Request $request)
    {
        $data = $request->all();
        $data['partnerOrderId'] = 'ORD-' . time() . '-' . rand(1000, 9999);

        $payload = [
            'path' => 'order/create_order',
            'data' => $data,
        ];

        Log::info('Moogold Create Order - Request', [
            'payload' => $payload,
        ]);

        $response = $this->callMoogold($payload, 'order/create_order');

        Log::info('Moogold Create Order - Response', [
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        return $response;
    }



    public function getOrderDetail(string $orderId)
    {
        Log::info('Moogold Order Detail - Request', [
            'order_id' => $orderId,
        ]);

        $response = $this->callMoogold([
            'path' => 'order/order_detail',
            'data' => [
                'order_id' => $orderId,
            ],
        ], 'order/order_detail');

        Log::info('Moogold Order Detail - Response', [
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        return $response;
    }

    public function serverList($id)
    {
        $payload = [
            'path' => 'product/server_list',
            'product_id' => $id,
        ];

        return $this->callMoogold($payload, 'product/server_list');
    }

    /*-------------------------------------------------
    | Low-level helper
    -------------------------------------------------*/
    private function callMoogold(array $payload, string $path)
    {
        $payloadJson = json_encode($payload, JSON_UNESCAPED_UNICODE);
        $timestamp   = (string) time();
        $stringToSign = $payloadJson . $timestamp . $path;
        $auth         = hash_hmac('sha256', $stringToSign, $this->secretKey);
        $authBasic = base64_encode("{$this->partnerId}:{$this->secretKey}");

        $response = Http::withHeaders([
                'timestamp'     => $timestamp,
                'auth'          => $auth,
                'Authorization' => 'Basic ' . $authBasic,
                'Content-Type'  => 'application/json',
            ])
            ->send('POST', $this->baseUrl . $path, [
                'body' => $payloadJson,
            ]);

        Log::info('Moogold API', [
            'path'     => $path,
            'status'   => $response->status(),
            'body'     => $response->body(),
            'response' => $response->json(),
        ]);

        return $response;
    }



    
    public function syncAllMoogoldProducts()
    {
        $products = Product::whereNotNull('moogold_id')->get();

        foreach ($products as $product) {
            $product->variants()->delete();

            $productDetails = $this->getProductDetail($product->moogold_id)->json();
            if (isset($productDetails['Variation']) && is_array($productDetails['Variation'])) {
                foreach ($productDetails['Variation'] as $variantData) {
                    $variant = Variant::create([
                        'product_id' => $product->id,
                        'name' => $variantData['variation_name'],
                        'moogold_id' => $variantData['variation_id'],
                        'moogold_usd' => $variantData['variation_price'],
                    ]);

                    VariantPrice::create([
                        'variant_id' => $variant->id,
                        'payment_method_id' => 1,
                        'price' => 0,
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Moogold products synchronized successfully.']);
    }


}
