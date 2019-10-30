<?php
 // Class HttpCurl
class HttpCurl {
    private $_ch, $_cookie, $_info, $_body, $_error;
       
    public function __construct() {
        if (!function_exists('curl_init')) {
            throw new Exception('cURL not enabled!');
        }
    }
   
    public function get($url) {
        $this->_ch = curl_init();
    curl_setopt($this->_ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:22.0) Gecko/20100101 Firefox/22.0');
    curl_setopt($this->_ch, CURLOPT_POST, 0);       
        curl_setopt($this->_ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($this->_ch, CURLOPT_MAXREDIRS, 5);  
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($this->_ch, CURLOPT_COOKIEFILE, getcwd () . '/facebook_cookie' );
        curl_setopt($this->_ch, CURLOPT_COOKIEJAR, getcwd () . '/facebook_cookie' );
    curl_setopt($this->_ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt($this->_ch, CURLOPT_SSL_VERIFYHOST, false );    
        curl_setopt($this->_ch, CURLOPT_URL, $url);
        $this->_body = curl_exec($this->_ch);
        $this->_info  = curl_getinfo($this->_ch);
        $this->_error = curl_error($this->_ch);
        curl_close($this->_ch);         
    }
     
    public function post($url,  $post_data) {
        $this->_ch = curl_init();
    curl_setopt($this->_ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:22.0) Gecko/20100101 Firefox/22.0');
        curl_setopt($this->_ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($this->_ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($this->_ch, CURLOPT_HEADER, 0); 
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($this->_ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt($this->_ch, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt($this->_ch, CURLOPT_ENCODING, "" ); 
    curl_setopt($this->_ch, CURLOPT_POST, TRUE);
    curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($this->_ch, CURLOPT_COOKIEFILE, getcwd () . '/facebook_cookie' );
        curl_setopt($this->_ch, CURLOPT_COOKIEJAR, getcwd () . '/facebook_cookie' );    
        curl_setopt($this->_ch, CURLOPT_URL, $url);
        $this->_body = curl_exec($this->_ch);
        $this->_info  = curl_getinfo($this->_ch);
        $this->_error = curl_error($this->_ch);
    curl_close($this->_ch);         
    }  
   
    // Get http_code
    public function getStatus() {
        return $this->_info[http_code];
    }
       
    // Get web page header information
    public function getHeader() {
        return $this->_info;
    }
     public function getHandle() {
        return $this->_ch;
    }
    // Get web page content
    public function getBody() {
        return $this->_body;
    }
       
    public function __destruct() {
    }      
     
}
   
?>