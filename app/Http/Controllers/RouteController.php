<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Paytring\Php\Api as Paytring;
use Illuminate\Support\Facades\Validator;
use Session;

class RouteController extends Controller
{
    public function install(Request $request){
        $payload = array(
            'client_id' => env('ClientId'),
            'client_secret' => env('ClientSecret'),
            'redirect_uri' => env('AppUrl').'/auth/callback',
            'grant_type' => 'authorization_code',
            'code' => $request->get('code'),
            'scope' => $request->get('scope'),
            'context' => $request->get('context'),
        );
        $response = Http::post('https://login.bigcommerce.com/oauth2/token', $payload, [
            'exceptions' => false,
        ]);
    
        if ($response->successful()) {
            $data = $response->json();
            list($context, $storeHash) = explode('/', $data['context'], 2);
            $accessToken = $data['access_token'];
            Log::debug($accessToken);

            $storeHash = $data['context'];
            $array = explode('/', $storeHash);
            $storeHash = $array[1];
            $email = $data['user']['email'];
            
            self::createScript($storeHash, $accessToken, 'https://checkout-sdk.bigcommerce.com/v1/loader.js', 'jquery.js', 'head');
            self::createScript($storeHash, $accessToken, 'https://code.jquery.com/jquery-3.7.1.js', 'checkout-sdk.js', 'head');
            self::createScript($storeHash, $accessToken, 'https://pay.paytring.com/iframe.v1.0.0.js', 'paytring.js', 'head');

            self::createScript($storeHash, $accessToken, env('AppUrl').'/js/app.js', 'app.js', 'footer');
            
        } else {
            // Handle non-200 response
            $statusCode = $response->status();
            $errorMessage = $response->body();
            // Handle or log the error appropriately
            Log::error("Error obtaining OAuth2 token - Status Code: $statusCode, Message: $errorMessage");
        }
    }

    public function createScript($storeHash, $accessToken, $file, $name, $location){
        $payload = array(
            'name' => $name,
            'description' => "This will be centralised js file in project",
            'src' => $file,
            'auto_uninstall' => true, 
            'location' => $location,
            'visibility' => 'all_pages',
            'kind' => 'src',
        );
        $response = Http::withHeaders(['X-Auth-Token' => $accessToken])->post('https://api.bigcommerce.com/stores/'.$storeHash.'/v3/content/scripts', $payload, [
            'exceptions' => false
        ]);
    }

    


    public function load(Request $request){
      
    }


    public function generate_paytring_payment_req(Request $request){


        $rules = [
            'cart.lineItems.physicalItems.*.name' => 'required|string',
            'cart.lineItems.physicalItems.*.quantity' => 'required|integer|min:1',
            'cart.lineItems.physicalItems.*.originalPrice' => 'required|numeric|min:0',
            'billingAddress.firstName' => 'required|string',
            'billingAddress.lastName' => 'required|string',
            'billingAddress.address1' => 'required|string',
            'billingAddress.city' => 'required|string',
            'billingAddress.postalCode' => 'required|string',
            'billingAddress.country' => 'required|string',
            'billingAddress.countryCode' => 'required|string|size:2',
            'billingAddress.email' => 'required|email',
            'amount' => 'required|numeric|min:0.01',
        ];
    
        // Validate the request data
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Bicommerce order creation
        $cartData = $request->input('cart');
        // Log::debug($cartData);
        $shippingAddress = $request->input('shippingAddress');
        $billingAddress = $request->input('billingAddress');
        
        $requestBody = [
          "status_id" => 7, // This value might come from elsewhere in your code
          "billing_address" => [
            "first_name" => $billingAddress['firstName'],
            "last_name" => $billingAddress['lastName'],
            "street_1" => $billingAddress['address1'],
            "city" => $billingAddress['city'],
            "state" => "N/A", // Assuming stateOrProvince holds the state data
            "zip" => $billingAddress['postalCode'],
            "country" => $billingAddress['country'],
            "country_iso2" => $billingAddress['countryCode'], // Assuming countryCode holds the ISO 2 code
            "email" => $billingAddress['email'], // Billing email might be different from cart email
          ],
          "products" => [],
        ];
        
        // Loop through cart items and add them to the products array
        foreach ($cartData['lineItems']['physicalItems'] as $item) {
          $requestBody['products'][] = [
            "name" => $item['name'],
            "quantity" => $item['quantity'],
            "price_inc_tax" => $item['originalPrice'], // Assuming originalPrice holds the price including tax
            "price_ex_tax" => $item['originalPrice']-10, // Assuming originalPrice holds the price including tax
            // You might need to calculate price_ex_tax based on your data structure
          ];
        }

        $order = Http::withHeaders(['X-Auth-Token' => env('AccessToken'), 'Accept' => 'application/json'])->post('https://api.bigcommerce.com/stores/'.env('StoreHash').'/v2/orders', $requestBody, [
            'exceptions' => false
        ]);
        $order = json_decode($order);

        

        // Paytring order creation
        $paytring = new Paytring(env('ApiKey'), env('ApiSecret'));
        Log::debug($request->input('amount'));
        $cartid = $request->input('cartid');
        $amount = $request->input('amount')*100;
        $txnID = uniqid();
        Log::debug("orderid--");
        Log::debug($order->id);
        Log::debug($requestBody);
        $callback_url = env('AppUrl')."/api/callback_at_payment/".$order->id."/".$cartid;
        $customer = [
                    'name'=> $billingAddress['firstName'].' '.$billingAddress['lastName'], 
                    'email'=> $billingAddress['email'], 
                    'phone'=> $billingAddress['phone']
                    ];
        $paymentData = "";
       
        $data = $paytring->CreateOrder($amount, $txnID, $callback_url, $customer, $paymentData);
        $data = json_decode($data, true);
        $hashed_url = $data['url'];
        
        return response()->json(["message"=>"success", "url"=> base64_decode($hashed_url)]);
    }

    // Order status acknowledgement after payment
    public function callback_at_payment(Request $request, $bigcom_orderid, $cartid){
        Log::debug("order_id".$bigcom_orderid);
        
        $api_key = env('ApiKey');
        $api_secret = env('ApiSecret');
        $paytring = new Paytring($api_key, $api_secret);
        $order = $paytring->FetchOrder($request->input('order_id'));
        $data = json_decode($order, true);
        $orderStatus = $data['order']['order_status'];
        if($orderStatus == 'success'){
            // Bigcommerce order payment status update
            $order = Http::withHeaders(['X-Auth-Token' => env('AccessToken'), 'Accept' => 'application/json'])->put('https://api.bigcommerce.com/stores/'.env('StoreHash').'/v2/orders/'.$bigcom_orderid, ['status_id'=> 10], [
                'exceptions' => false
            ]);
            Log::debug("cartid Success");
            Log::debug($cartid);
            Http::withHeaders(['X-Auth-Token' => env('AccessToken'), 'Accept' => 'application/json'])->delete('https://api.bigcommerce.com/stores/'.env('StoreHash').'/v3/carts/'.$cartid);
            if($order) return redirect('/order-confirmation/'.$bigcom_orderid);
        }
        else{
            $order = Http::withHeaders(['X-Auth-Token' => env('AccessToken'), 'Accept' => 'application/json'])->put('https://api.bigcommerce.com/stores/'.env('StoreHash').'/v2/orders/'.$bigcom_orderid, ['status_id'=> 5], [
                'exceptions' => false
            ]);
            if($order) return redirect('/payment-failed/'.$bigcom_orderid);
            
        }
    }

    public function order_confirmation($orderid){
        // get order based on order id
        $order = Http::withHeaders(['X-Auth-Token' => env('AccessToken'), 'Accept' => 'application/json'])->get('https://api.bigcommerce.com/stores/'.env('StoreHash').'/v2/orders/'.$orderid);
            $order = json_decode($order);
            $products = $order->products;
            if($products){
                $products_url = $products->url;
                $products = Http::withHeaders(['X-Auth-Token' => env('AccessToken'), 'Accept' => 'application/json'])->get($products_url);
                $products = json_decode($products);
            }
        return view('order-confirmation', compact('order', 'products'));
    }

    public function payment_failed($orderid){
        // get order based on order id
        $order = Http::withHeaders(['X-Auth-Token' => env('AccessToken'), 'Accept' => 'application/json'])->get('https://api.bigcommerce.com/stores/'.env('StoreHash').'/v2/orders/'.$orderid);
            $order = json_decode($order);
            $products = $order->products;
            if($products){
                $products_url = $products->url;
                $products = Http::withHeaders(['X-Auth-Token' => env('AccessToken'), 'Accept' => 'application/json'])->get($products_url);
                $products = json_decode($products);
            }
        return view('payment-failed', compact('order', 'products'));
    }
}