<?php
  ob_start();
  $Graph = array('A' => array('B', 'E', 'G'),

                 'B' => array('C'),
 
                 'C' => array('D', 'E'),

                'D' => array('F'),

                'E' => array('C', 'F', 'G'),

                'F' => array(),

                'G' => array('A') );
  
   $im  = imagecreatetruecolor(350, 350);
   $x = 2;
   $y = 10;
   $blue  = imagecolorallocate($img,   0,   0, 255);
   foreach($Graph as $v=>$r) {
     
     
     imagearc($im,$x,$y,30,30,0,360,$blue);
     $x+=15;
    
    
   }
   header("Content-Type: image/png");
   imagepng($im);
   imagedestroy($im);
?>