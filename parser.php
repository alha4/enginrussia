<?php
 ini_set("display_errros","on");
 ini_set('error_reporting', E_ALL);
 error_reporting(E_ALL);
 
 namespace Alpha\SimpleParser;
 
 class WebPageParser {
    
    private $webPagesURI;
    
    function __construct($url) {
        $this->webPagesURI = $url;
        //$this->load();
    }
    public function load() {
      try {
        if(!$this->is_loaded_curl_module()) {
         
           throw new Exception("�� ���������� ������ ���������� [curl]");
        }
        $pages_url = $this->webPagesURI;
        
        $m� = curl_multi_init();
        
        foreach($pages_url as $url) {
            
        }
        
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
        echo '����';
    }
 }
 
 $webPageParser = new WebPageParser(array("https://www.fabrikant.ru/market/?action=list_public_auctions&type=531&status_group=sg_published"));
 $webPageParser->load();
 
 
?>