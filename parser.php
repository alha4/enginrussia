<?php
 namespace Alpha4SimpleWebParser;
 
 function error_debug_trace() {
   ini_set("display_errors","on");
   ini_set('error_reporting', E_ALL);
   error_reporting(E_ALL);
 }
 error_debug_trace();
 interface HttpLoader {
    function load($url);
 }

 interface DataRender {
    function render($HTML); 
 }
 
 interface DataCollection {
    function set($key,$value);
    function get();
 }
 
 interface DataBulder {
    function buld($data);
 }
 
 class MultiCurlLoader implements HttpLoader {
    function load($url) {}
 }
 
 class XpathDataRender implements DataRender {
    
    private $bulder;
    
    private $XExp;
    
    function __construct(DataBulder $bulder,$expression) {
        $this->bulder = $bulder;
        $this->XExp = $expression;
    }
    
    function render($stringHTML) {
        
     $dom = new \DomDocument();
     $dom->preserveWhiteSpace = false;
     $dom->loadHTML($stringHTML);
     // echo $stringHTML; 
     $xpath = new \DOMXpath($dom);
     $nodes = $xpath->query($this->XExp);
     
     if($nodes->length !== 0) {
        
         $this->bulder->buld($nodes);
     } else {
         print_r($nodes);
         echo $this->XExp;
         exit('данные не найдены');
     }
  }
 
 }
 
 class ListPage implements DataBulder {
    
   function buld($nodes) {
        
     if($nodes->length !== 0) {
        
     $data = array();
    
     foreach($nodes as $item) {
    
      $childItems = $item->childNodes;
      
      $img = $childItems->item(0);
      $img = $img->firstChild->nextSibling->firstChild->nextSibling;
      
      $tipi = $childItems->item(1);
      $tipi = $tipi->nextSibling->nodeValue;
      
      $title = $childItems->item(3);
      $title = $title->nextSibling->firstChild->nextSibling->firstChild->nodeValue;
      
      $m2 = $childItems->item(5);
      $m2 = $m2->nextSibling->nodeValue;
      
      $oda = $childItems->item(7);                         
      $oda = $oda->nextSibling->nodeValue;
      
      $flyat = $childItems->item(9); 
      $flyat = $flyat->nextSibling->nodeValue;
      
      $ilan = $childItems->item(11); 
      $ilan = $ilan->nextSibling->nodeValue;
      
      $lice = $childItems->item(13); 
      $lice = $lice->nextSibling->nodeValue;
      
      $img = $img->attributes;
       
      $attr_value = array();
      foreach($img as $name => $attrNode) {
          $attr_value[] = $attrNode->nodeValue;
      }
       
      $data[] = array("img"=>$attr_value[0],"tipi"=>$tipi,"title"=>$title,"m2"=>$m2,"oda"=>$oda,"flyat"=>$flyat,"lian"=>$ilan,"lice"=>$lice); 
    }
    
    $htmTable = '<table width="80%" cellpadding="5" cellspacing="3" border="1" align="center">';

    foreach($data as $item) {
          $htmTable.= '<tr>';
          
          foreach($item as $row=>$text_node) {
            if($row == 'img') {
                
               $htmTable.= '<td><img src="'.$text_node.'"></td>';
               continue;
            }   
            $htmTable.= '<td>'.$text_node.'</td>';
          
          }
    }
        
    $htmTable.= '</table>';
    echo $htmTable;
   }
  }
 }
 
 class SimpleCurlLoader implements HttpLoader {
    
    function load($url) {
        
      $ch = curl_init();
   
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_HEADER, 0);  
      curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
      curl_setopt($ch, CURLOPT_TIMEOUT, 30);

      $body = curl_exec($ch);
      
      curl_close($ch);
      
      if( $body ) {
          return $body;
      }
     
      return false; 
    }
 }

 class WebPageParser {
    
    private $webPagesURI;
    
    private $dataRender;
    
    function __construct($url) {
        $this->webPagesURI = $url;
    }
    public function load(HttpLoader $loader,DataRender $render) {
        
      try {
        if(!$this->is_loaded_curl_module()) {
         
           throw new \Exception("Не установлен модуль расширение [curl]");
        }
        $pages_url = $this->webPagesURI;
        
        $result = $loader->load($pages_url);
    
        if(false !== $result) {
            
            $render->render($result);
            
        } else {
            
            echo 'failed curl request';
        }
        
      } catch(Exception $err) {
        
         exit($err->getMessage()); 
      }
    }
    private function is_loaded_curl_module() {

        if( !extension_loaded("curl") ) {
           return false;
        }
        return true;
    }
 }
 
  class DetailPage implements DataBulder {
    
    function buld($nodes) {
        
       $data = array();
       
       $childItemsDetail = $nodes->item(1);
    
       foreach($childItemsDetail as $item) {
        
        
          //$data[] = $item->nodeName; //array();
       }
       $data = $childItemsDetail->textContent;
       //print_r($nodes);
     //  print_r($childItems);
       print_r($data);
    }
    
 }
 //$webPageParser = new WebPageParser("http://www.sahibinden.com/satilik/antalya?sorting=price_asc");
 //$webPageParser->load(new SimpleCurlLoader(),new XpathDataRender(new ListPage(),'//*[@id="searchResultsTable"]/tbody/tr'));
 
 $webPageParser2 = new WebPageParser("http://www.sahibinden.com/ilan/emlak-konut-satilik-sok-sok-sok-caddeye-sifir-guneyli-1-plus1-yuksek-giris-55.000tl-196381582/detay");
 $webPageParser2->load(new SimpleCurlLoader(),new XpathDataRender(new DetailPage(),'//*[@id="classifiedDetail"]/div[1]/div[2]/*'));

?>