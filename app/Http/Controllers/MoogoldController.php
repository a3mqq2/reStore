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
    public function getProductList()
    {
        $payload = [
            'path' => 'product/list_product',
            'category_id' => 1
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
        $payload = [
            'path' => 'order/create_order',
            'data' => $request->all(),
            "partnerOrderId" => env('PARTNER_ORDER_ID'),
        ];
        return $this->callMoogold($payload, 'order/create_order');
    }



    public function getOrderDetail(string $orderId)
    {
        return $this->callMoogold([
            'path' => 'order/order_detail',
            'data' => [
                'order_id' => $orderId,
            ],
        ], 'order/order_detail');
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
                'timestamp'    => $timestamp,
                'auth'         => $auth,
                'Authorization'=> 'Basic ' . $authBasic,
            ])
            ->withBody($payloadJson, 'application/json')
            ->post($this->baseUrl . $path);

        Log::info('Moogold API', [
            'path'     => $path,
            'status'   => $response->status(),
            'response' => $response->json(),
        ]);

        return $response;
    }



 
public function syncAllMoogoldProducts()
{
    $products = Product::whereNotNull('moogold_id')->get();

    foreach ($products as $product) {
        $product->variants()->delete();
        $productDetails = $this->getProductDetail($product->moogold_id);



        if (isset($productDetails['Variation']) && is_array($productDetails['Variation'])) {
            foreach ($productDetails['Variation'] as $variantData) {
                $variant = Variant::create([
                    'product_id' => $product->id,
                    'name' => $variantData['variation_name'],
                    'moogold_id' => $variantData['variation_id'],
                ]);

                $variantPrice = new VariantPrice();
                $variantPrice->variant_id = $variant->id;
                $variantPrice->payment_method_id = 1;
                $variantPrice->price = 0;
                $variantPrice->save();
            }
        }
    }

    return response()->json(['message' => 'Moogold products synchronized successfully.']);
}

}
