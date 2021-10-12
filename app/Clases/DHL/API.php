<?php

namespace App\Clases\DHL;
use Illuminate\Support\Facades\Http;

class API
{

    /**
     * @var CurlHandle|false|resource
     */
    private $curl;

    public function __construct()
    {
        $this->curl = curl_init();
    }

    private function CALL_API(string $method,string $url,$data,$user_password=false)
    {
        $response = null;
        switch ($method)
        {
            case "POST":
                //curl_setopt($this->curl, CURLOPT_POST, 1);
                $response = Http::post($url, $data);
                
                break;
            case "PUT":
                
                break;
            default:
                break;
        }

        return $response;
    }

    public function POST (string $url)
    {
        return $this->CALL_API("POST",$url, []);
    }

    public function POST_DATA(string $url,string $data)
    {
        return $this->CALL_API("POST",$url,$data);
    }

    public function POST_DATA_AUT(string $url,string $data,string $user,string $password)
    {
        $up="$user:$password";
        return $this->CALL_API("POST",$url,$data,$up);
    }

    public function JSON_POST_DATA_AUT(string $url,array $data,string $user,string $passord):string
    {
        $up="$user:$passord";
        $json=json_encode($data,true);
        return Http::post($url, $data);
    }

    public function JSON_POST_DATA_AUT_ARRAY(string $url,array $data,string $user,string $passord):array
    {
        $up="$user:$passord";
        $response = null;

        $json=json_encode($data,true);
        try {
            $response = Http::withBasicAuth($user, $passord)->post($url, $data);
            
            if (!isset($response))
            {
                throw new \Exception("no hay respuesta paqueteria");
            }

            return json_decode($response,true);
        }
        catch (\Exception $ex)
        {

            return ["problema" => $ex->getMessage()];
        }


    }

    public function PUT (string $url)
    {
        return $this->CALL_API("PUT",$url);
    }

    public function PUT_DATA(string $url,string $data)
    {
        return $this->CALL_API("PUT",$url,$data);
    }

    public function PUT_DATA_AUT(string $url,string $data,string $user,string $password)
    {
        $up="$user:$password";
        return $this->CALL_API("PUT",$url,$data,$up);
    }

    public function JSON_PUT_DATA_AUT(string $url,array $data,string $user,string $passord):string
    {
        $up="$user:$passord";
        $json=json_encode($data,true);
        return $this->CALL_API("PUT",$url,$json,$up);
    }

    public function JSON_PUT_DATA_AUT_ARRAY(string $url,array $data,string $user,string $passord):array
    {
        $up="$user:$passord";
        $json=json_encode($data,true);
        return json_decode($this->CALL_API("PUT",$url,$json,$up));
    }

}