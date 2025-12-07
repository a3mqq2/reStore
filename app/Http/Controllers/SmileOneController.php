<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmileOneController extends Controller
{
    private $apiBaseUrl = 'https://www.smile.one/br';
    private $apiKey = '37bf91158bebfdd0684279075cf056c3';
    private $uid = "1937786";
    private $email = "Ezo706174@gmail.com";

    public function getProducts()
    {
        try {
            $data = [
                'uid' => $this->uid,
                'email' => $this->email,
                'product' => "mobilelegends",
                'time' => time(),
            ];
            $data['sign'] = $this->generateSign($data);

            $response = Http::timeout(10)->withoutProxy()->asForm()->post($this->apiBaseUrl . '/smilecoin/api/product', $data);

            if ($response->failed()) {
                Log::error('SmileOne API Error - getProducts', [
                    'status' => $response->status()
                ]);
                return ['status' => 'error', 'data' => [], 'message' => 'فشل الاتصال بخدمة SmileOne'];
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('SmileOne Error - getProducts', ['error' => $e->getMessage()]);
            return ['status' => 'error', 'data' => [], 'message' => 'حدث خطأ في الاتصال بالخدمة'];
        }
    }


    public function queryPoints()
    {
        try {
            $data = [
                'uid' => $this->uid,
                'email' => $this->email,
                'product' => "mobilelegends",
                'time' => time(),
            ];
            $data['sign'] = $this->generateSign($data);

            $response = Http::timeout(10)->asForm()->post($this->apiBaseUrl . '/smilecoin/api/querypoints', $data);

            if ($response->failed()) {
                Log::error('SmileOne API Error - queryPoints', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return ['status' => 'error', 'smile_points' => 0, 'message' => 'فشل الاتصال بخدمة SmileOne'];
            }

            $result = $response->json();

            if (!isset($result['smile_points'])) {
                Log::warning('SmileOne API - Invalid Response Structure', ['response' => $result]);
                return ['status' => 'error', 'smile_points' => 0, 'message' => 'استجابة غير صالحة'];
            }

            return $result;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('SmileOne Connection Error - queryPoints', [
                'error' => $e->getMessage()
            ]);
            return ['status' => 'error', 'smile_points' => 0, 'message' => 'خطأ في الاتصال بالخدمة'];

        } catch (\Exception $e) {
            Log::error('SmileOne Unexpected Error - queryPoints', [
                'error' => $e->getMessage()
            ]);
            return ['status' => 'error', 'smile_points' => 0, 'message' => 'حدث خطأ غير متوقع'];
        }
    }


    public function createPurchase(Request $request)
    {
        try {
            $data = [
                'email' => $this->email,
                'uid' => $this->uid,
                'userid' => $request->input('userid'),
                'zoneid' => $request->input('zoneid'),
                'product' => $request->input('product', 'mobilelegends'),
                'productid' => $request->input('productid'),
                'time' => time(),
            ];
            $data['sign'] = $this->generateSign($data);

            $response = Http::timeout(10)->asForm()->post($this->apiBaseUrl . '/smilecoin/api/createorder', $data);

            if ($response->failed()) {
                Log::error('SmileOne API Error - createPurchase', [
                    'status' => $response->status()
                ]);
                return ['status' => 'error', 'message' => 'فشل إنشاء الطلب'];
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('SmileOne Error - createPurchase', ['error' => $e->getMessage()]);
            return ['status' => 'error', 'message' => 'حدث خطأ في إنشاء الطلب'];
        }
    }

    private function generateSign($params)
    {
        $m_key = $this->apiKey;
        
        ksort($params);
        
        $str = '';
        foreach ($params as $k => $v) {
            $str .= $k . '=' . $v . '&';
        }
        
        $str .= $m_key;
        
        // Double MD5 hash
        return md5(md5($str));
    }


    public function roleQuery(Request $request)
    {
        try {
            $product = Product::find($request->product_id);
            $requirements = explode(',', $request->requirements);
            if (!isset($requirements[1])) {
                $requirements[1] = 1;
            }

            $data = [
                'email'     => $this->email,
                'uid'       => $this->uid,
                'userid'    => $requirements[0],
                'zoneid'    => $requirements[1],
                'product'   => $product ? $product->smileone_name : $request->smileone_name,
                'productid' => $product ? $product->variants[1]->smileone_id : $request->smileone_id,
                'time'      => time(),
            ];
            $data['sign'] = $this->generateSign($data);

            $response = Http::timeout(10)->asForm()->post($this->apiBaseUrl . '/smilecoin/api/getrole', $data);

            if ($response->failed()) {
                Log::error('SmileOne API Error - roleQuery', [
                    'status' => $response->status()
                ]);
                return ['status' => 'error', 'message' => 'فشل التحقق من البيانات'];
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('SmileOne Error - roleQuery', ['error' => $e->getMessage()]);
            return ['status' => 'error', 'message' => 'حدث خطأ في التحقق'];
        }
    }
    
    
    public function getProductList($name)
    {
        try {
            $data = [
                'uid' => $this->uid,
                'email' => $this->email,
                'product' => $name,
                'time' => time(),
            ];
            $data['sign'] = $this->generateSign($data);

            $response = Http::timeout(10)->asForm()->post($this->apiBaseUrl . '/smilecoin/api/productlist', $data);

            if ($response->failed()) {
                Log::error('SmileOne API Error - getProductList', [
                    'status' => $response->status()
                ]);
                return ['status' => 'error', 'data' => [], 'message' => 'فشل جلب قائمة المنتجات'];
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('SmileOne Error - getProductList', ['error' => $e->getMessage()]);
            return ['status' => 'error', 'data' => [], 'message' => 'حدث خطأ في جلب المنتجات'];
        }
    }
    
    public function getServerList($name)
    {
        try {
            $data = [
                'uid' => $this->uid,
                'email' => $this->email,
                'product' => $name,
                'time' => time(),
            ];
            $data['sign'] = $this->generateSign($data);

            $response = Http::timeout(10)->asForm()->post($this->apiBaseUrl . '/smilecoin/api/getserver', $data);

            if ($response->failed()) {
                Log::error('SmileOne API Error - getServerList', [
                    'status' => $response->status()
                ]);
                return ['status' => 'error', 'data' => [], 'message' => 'فشل جلب قائمة السيرفرات'];
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('SmileOne Error - getServerList', ['error' => $e->getMessage()]);
            return ['status' => 'error', 'data' => [], 'message' => 'حدث خطأ في جلب السيرفرات'];
        }
    }

    public function checkProviderBalance(Request $request)
    {
        try {
            $product = Product::find($request->product_id);

            if (!$product) {
                return response()->json([
                    'available' => false,
                    'message' => 'المنتج غير موجود'
                ]);
            }

            // Minimum balance threshold to consider service available
            $minBalanceThreshold = 100;

            // Check if product is SmileOne
            if ($product->smileone_name) {
                $balanceResponse = $this->queryPoints();
                $balance = $balanceResponse['smile_points'] ?? 0;

                if ($balance < $minBalanceThreshold) {
                    return response()->json([
                        'available' => false,
                        'message' => 'عذراً، المنتج غير متوفر حالياً. يرجى المحاولة لاحقاً.'
                    ]);
                }

                return response()->json([
                    'available' => true,
                    'message' => 'المنتج متوفر'
                ]);
            }

            // Check if product is Moogold
            if ($product->moogold_id) {
                $moogold = new MoogoldController();
                $balanceResponse = $moogold->getBalance();
                $balance = $balanceResponse['balance'] ?? 0;

                if ($balance < $minBalanceThreshold) {
                    return response()->json([
                        'available' => false,
                        'message' => 'عذراً، المنتج غير متوفر حالياً. يرجى المحاولة لاحقاً.'
                    ]);
                }

                return response()->json([
                    'available' => true,
                    'message' => 'المنتج متوفر'
                ]);
            }

            // Product doesn't use external provider, always available
            return response()->json([
                'available' => true,
                'message' => 'المنتج متوفر'
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking provider balance', ['error' => $e->getMessage()]);
            return response()->json([
                'available' => false,
                'message' => 'حدث خطأ في التحقق من التوفر'
            ]);
        }
    }


    public function syncSmileOneVariants()
    {
        $products = Product::whereNotNull('smileone_name')->get();

        foreach ($products as $product) {
            $productData = $this->getProductList($product->smileone_name);

            if (!isset($productData['data']['product']) || !is_array($productData['data']['product'])) {
                Log::warning('SmileOne - No product data returned', ['product' => $product->smileone_name]);
                continue;
            }

            $items = $productData['data']['product'];

            $product->variants()->delete();

            foreach ($items as $variantData) {
                $variant = $product->variants()->create([
                    'name' => $variantData['spu'] ?? 'Unknown',
                    'smileone_id' => $variantData['id'] ?? null,
                    'smileone_points' => $variantData['price'] ?? 0,
                ]);

                $variant->prices()->updateOrCreate(
                    ['payment_method_id' => 1],
                    ['price' => 0]
                );
            }
        }

        return response()->json(['message' => 'تم مزامنة Variants لجميع منتجات SmileOne بنجاح']);
    }


}
