<?php
 ini_set("display_errros","on");
 ini_set('error_reporting', E_ALL);
 error_reporting(E_ALL);
 
 namespace Alpha\SimpleWebParser;
 
 interface HttpLoader {
    function load();
 }
 
 interface dataRender {
    function render();
 }
 
 class MultiLoader implements HttpLoader {
    #code
 }
 
 class SimpleLoader implements HttpLoader{
    #code
 }
 
 abstract class HttpException extends Exception {
    #code
 }
 
 
 class WebPageParser {
    
    private $webPagesURI;
    
    function __construct($url) {
        $this->webPagesURI = $url;
        //$this->load();
    }
    public function load(HttpLoader $loader) {
      try {
        if(!$this->is_loaded_curl_module()) {
         
           throw new Exception("Не установлен модуль расширение [curl]");
        }
        $pages_url = $this->webPagesURI;
        
        $loader->load($pages_url);
        
        /*$mс = curl_multi_init();
        
        foreach($pages_url as $url) {
            
        }*/
        
      } catch(Exception $err) {
         echo 'errir load modules';  
         echo $err->getMessage(); 
      }
    }
    private function is_loaded_curl_module() {

        if( !extension_loaded("curl") ) {
           return false;
        }
        return true;
    }
    public function parse() {
        echo 'тест';
    }
 }
 
 $webPageParser = new WebPageParser(array("https://www.fabrikant.ru/market/?action=list_public_auctions&type=531&status_group=sg_published"));
 $webPageParser->load();
 
 
?>