<?php
/**
    AJAX FOR PHP :)

   * x_get($address, $data)
   * x_post($address, $data)
   * x_put($address, $data);
   * x_delete($address, $data);
   * setHeader($string)
   * clearHeaders()
   * setFormUrlEncodedType()
   * setJsonType()

   Usage:
   $cn = new xHTTP();
   try{
        $response = $cn -> get('http://google.com');
        //echo $response['response'];
        echo 'request success';
    }catch(Exception $e){
        echo 'request error';
    }
*/
class xHTTP{
    private $ch;
    private $headers = array();
    public function __construct(){
        $this -> ch = curl_init();
        $this -> clearHeaders();
    }
    /**
        parse request header data and
            return human readable array
    */
    private function parse_headers($headers){
        $head = array();
        foreach($headers as $k => $v){
            $t = explode(':', $v, 2);
            if(isset($t[1])){
                $head[ trim($t[0]) ] = trim( $t[1] );
            }else{
                $head[] = $v;
                if(preg_match("#HTTP/[0-9\.]+\s+([0-9]+)#",$v, $out))
                    $head['response_code'] = intval($out[1]);
            }
        }
        return $head;
    }
    /**
        basic method to send request
    */
    private function x_request($address, $type = 'GET', $data = null){
        $headers = $this -> getHeaders();
        $opts = array(
            'http' => array(
                'method' => $type,
                'ignore_errors' => true,
                'header' => $headers
            ),
            'ssl' => array(
                'verify_peer' => false,
                'allow_self_signed' => true
            )
        );

        if($data !== null){
            if($type == 'GET'){
                $address .= '?'.http_build_query($data);
            }else{
                $opts['http']['content'] = http_build_query($data);
            }
        }

        $context = stream_context_create($opts);
        $response = @file_get_contents($address, false, $context);

        $this -> clearHeaders();
        if($response === false){
            throw new Exception('request error');
        }
        $response = array('response' => $response, 'headers' => $this -> parse_headers($http_response_header));

        return $response;
    }
    private function getHeaders(){
        return implode("\r\n", $this -> headers)."\r\n";
    }
    public function setHeader($str){
        $this -> headers[] = $str;
    }
    public function clearHeaders(){
        $this -> headers = array();
    }
    public function setFormUrlEncodedType(){
        $this -> setHeader('Content-Type: application/x-www-form-urlencoded');
    }
    public function setJsonType(){
        $this -> setHeader('Content-Type: application/json');
    }
    public function x_post($address, $data){
        try{
            $result = $this -> x_request($address, 'POST', $data);
        }catch(Exception $e){
            throw new Exception($e -> getMessage());
        }
        return $result;
    }
    public function x_get($address, $data){
        try{
            $result = $this -> x_request($address, 'GET', $data);
        }catch(Exception $e){
            throw new Exception($e -> getMessage());
        }
        return $result;
    }
    public function x_put($address, $data){
        try{
            $result = $this -> x_request($address, 'PUT', $data);
        }catch(Exception $e){
            throw new Exception($e -> getMessage());
        }
        return $result;
    }
    public function x_delete($address){
        try{
            $result = $this -> x_request($address, 'DELETE');
        }catch(Exception $e){
            throw new Exception($e -> getMessage());
        }
        return $result;
    }
}
?>
