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
            $storeHash = $data['context'];
            $array = explode('/', $storeHash);
            $storeHash = $array[1];
            $email = $data['user']['email'];

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

    public function create_hash(){
        $params = [
            "amount" => "100",
            "currency" => "INR",
            "callback_url" => "https://httpbin.org/post",
            "cname" => "John Doe",
            "email" => "johndoe@email.com",
            "key" => "test_key",
            "phone" => "8930395227",
            "receipt_id" => "TXN0438400150988993",
            "notes" => [
                "udf1" => "udf1",
                "udf2" => "udf2"
            ],
        ];
        ksort($params);

        // Step 2: Join string values with "|" (excluding objects)
        $value_string = "";
        foreach ($params as $key => $value) {
        if (is_string($value)) {
            $value_string .= $value . "|";
        }
        }

        $value_string .= "test_secret";

        // Step 4: Create hash using SHA512 and convert to lowercase
        $hash = hash_hmac('sha512', $value_string, 'test_secret', true);
        $hash = strtolower(bin2hex($hash));

        // Add hash to the parameters
        $params["hash"] = $hash;
    }

    public function load(){
        // $params = [
        //     "amount" => "100",
        //     "currency" => "INR",
        //     "callback_url" => "https://httpbin.org/post",
        //     "cname" => "John Doe",
        //     "email" => "johndoe@email.com",
        //     "key" => "test_key",
        //     "phone" => "8930395227",
        //     "receipt_id" => "TXN0438400150988993",
        //     "notes" => [
        //         "udf1" => "udf1",
        //         "udf2" => "udf2"
        //     ],
        // ];

        $params = [
            "amount" => "100",
            "txnID" => "ere3455",
            "callback_url" => "https://httpbin.org/post",
            "cname" => "John Doe",
            "email" => "johndoe@email.com",
            "key" => "test_key",
            "phone" => "8930395227",
            "receipt_id" => "TXN0438400150988993",
            "notes" => [
                "udf1" => "udf1",
                "udf2" => "udf2"
            ],
        ];

        $api_key = "test_key";
        $api_secret = "test_secret";
        $paytring = new Paytring($api_key, $api_secret);
        $hash = $paytring->CreateOrder("100", "tersud", "https://httpbin.org/post", ['name'=> 'john', 'email'=> 'jo@gmail.com', 'phone'=> '2113242342'], "test");
        $data = json_decode($hash, true);
        $url = $data['url'];
          
        Log::debug($url);
    }
}