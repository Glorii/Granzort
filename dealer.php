<?php
require_once("LEDSummoner.class.php");
require_once("ChassisSummoner.class.php");
const WIDTH = 192;
const HEIGHT = 144;
//$bitmap = initBitmap();
if (isset($_POST["bitmap"])) {
  $bitmap = json_decode(["bitmap"]);
}else{
  echo "error!";
}
$pathMode = $_POST["pathMode"];
$background = $_POST["background"];
$ls = new LEDSummoner(WIDTH, HEIGHT, $bitmap, $pathMode, $background);
$cs = new ChassisSummoner();
$ls->bitmapMode($pathMode);
$cs->gogogo($pathMode);

// function initBitmap(){
//   $bitmap = array();
//   for ($i=0; $i < HEIGHT; $i++) { 
//     $bitmap[$i] = array();
//     for ($j=0; $j < WIDTH; $j++) { 
//       $bitmap[$i][$j] = 0;
//     }
//   }
//   return $bitmap;
// }
?>