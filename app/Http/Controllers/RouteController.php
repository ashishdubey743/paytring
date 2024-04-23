<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Paytring\Php\Api as Paytring;



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

        // Bicommerce order creation
        $cartData = $request->input('cart');
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
        $amount = $request->input('amount')*100;
        $txnID = uniqid();
        Log::debug("orderid--");
        Log::debug($order->id);
        Log::debug($requestBody);
        $callback_url = env('AppUrl')."/api/callback_at_payment/".$order->id;
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
    public function callback_at_payment(Request $request, $bigcom_orderid){
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
            if($order) return redirect(env('StoreUrl').'/checkout/order-confirmation');
        }
        else{
            return redirect(env('StoreUrl').'/checkout');
        }
    }
}