<?php
/* 
 * Use to make HTTP requests
 * Wraps native php curl_* functions
 */

namespace Libraries\TinyPHP;
class HttpClient
{
    private $_endpoint;
    private $_method = "POST";
    private $_requestHeaders = array();
    private $_responseHeaders = array();
    private $_options = array();
    private $_data = array();
    private $_rawResponse;
    
    public function execute()
    {
        if(!$this->_endpoint){
            throw new \Exception("HttpClient Endpoint Not Set.");
        }
        $curl = curl_init($this->_endpoint);
        
        if(!in_array($this->_method,array("GET","POST","PUT","DELETE"))){
            throw new \Exception("Invalid HTTP Method");
        }
        if($this->_method != "GET" && $this->_method != "POST"){
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->_method);
        }
        if($this->_method == "POST"){
            curl_setopt($curl, CURLOPT_POST, true);
        }

        if(!empty($this->_data) || $this->_data){
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->_data);
        }
        
        $responseHeaders = false;
        foreach($this->_options as $optionSet){
            curl_setopt($curl, $optionSet['name'], $optionSet['value']);
            if($optionSet['name'] == CURLOPT_HEADER && $optionSet['value']){
                $responseHeaders = true;
            }
        }
        
        $headers = array();
        foreach($this->_requestHeaders as $headerSet){
            $headers[] = $headerSet['name'] . ": " . $headerSet['value'];
        }
        if(!empty($headers)){
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $this->_rawResponse = curl_exec($curl);
        
        
        if($this->_rawResponse && $responseHeaders){
            $responseArray = explode("\r\n\r\n", $this->_rawResponse);
            $headerBlock = $responseArray[0];
            $this->_rawResponse = $responseArray[1];
            
            $headerLines = explode("\r\n", $headerBlock);
            foreach($headerLines as $headerLine){
                if(strpos($headerLine,":") !== false){
                    $key = strstr($headerLine, ":", true);
                    $value = trim(substr(strstr($headerLine, ":"),1));
                    $this->_responseHeaders[$key] = $value;
                }else{
                    $this->_responseHeaders[] = $headerLine;
                }
            }
        }
    }
    
    public function getRawResponse()
    {
        return $this->_rawResponse;
    }

    public function setRawResponse($rawResponse)
    {
        $this->_rawResponse = $rawResponse;
        return $this;
    }
    
    public function getData()
    {
        return $this->_data;
    }

    public function setData($data)
    {
        $this->_data = $data;
        return $this;
    }
    
    public function getEndpoint()
    {
        return $this->_endpoint;
    }

    public function getMethod()
    {
        return $this->_method;
    }

    public function getRequestHeaders()
    {
        return $this->_requestHeaders;
    }

    public function getResponseHeaders()
    {
        return $this->_responseHeaders;
    }

    public function setRequestHeader($headerName,$value)
    {
        $this->_requestHeaders[] = array("name" => $headerName, "value" => $value);
        return $this;
    }

    public function setResponseHeaders($responseHeaders)
    {
        $this->_responseHeaders = $responseHeaders;
        return $this;
    }

    public function getOptions()
    {
        return $this->_options;
    }

    public function setEndpoint($endpoint)
    {
        $this->_endpoint = $endpoint;
        return $this;
    }

    public function setMethod($method)
    {
        $this->_method = $method;
        return $this;
    }
    
    public function setOption($option_name,$value)
    {
        $this->_options[] = array("name" => $option_name, "value" => $value);
        return $this;
    }
    
}