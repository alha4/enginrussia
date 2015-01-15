<?php
 namespace AlphaSimpleWebParser;
 
 header("Content-type: text/html; charset=windows-1251");
 //ini_set("display_errors","on");
 //ini_set('error_reporting', E_ALL);
 error_reporting(E_ALL);
 
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
 class MultiCurlLoader implements HttpLoader {
    function load($url) {}
 }
 
 class XpathDataRender implements DataRender {
    
    function render($stringHTML) {
        
     $dom = new \DomDocument();
     $dom->preserveWhiteSpace = false;
     $dom->loadHTML($stringHTML);

     $xpath = new \DOMXpath($dom);
     $nodes = $xpath->query('//*[@id="searchResultsTable"]/tbody/tr');

     $finds_nodes = array();
  
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
       
       $finds_nodes[] = array("img"=>$attr_value[0],"tipi"=>$tipi,"title"=>$title,"m2"=>$m2,"oda"=>$oda,"flyat"=>$flyat,"lian"=>$ilan,"lice"=>$lice);
     }
    
    return $finds_nodes;
  }
 
 }
 
 class SimpleCurlLoader implements HttpLoader {
    
    function load($url) {
        
      $ch = curl_init();
   
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.9.168 Version/11.51");
      curl_setopt($ch, CURLOPT_TIMEOUT, 20);

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
        
        $dataRender = $render->render($result);
        
        if(false !== $result) {
            
            $this->parse($dataRender);
            
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
    private function parse($dataCollection) {
        $htmTable = '<table width="80%" cellpadding="5" cellspacing="3" border="1" align="center">';
        foreach($dataCollection as $item) {
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
 
 $webPageParser = new WebPageParser("http://www.sahibinden.com/satilik/antalya?sorting=price_asc");
 $webPageParser->load(new SimpleCurlLoader(),new XpathDataRender());
 
?>