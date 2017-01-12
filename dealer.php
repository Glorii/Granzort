<?php
require_once("LEDSummoner.class.php");
require_once("ChassisSummoner.class.php");
require_once("logger.php");
const WIDTH = 192;
const HEIGHT = 144;
//$bitmap = initBitmap();
$bitmap = array();
if (isset($_POST["bitmap"])) {
  $bitmap = json_decode($_POST["bitmap"]);
}else{
  echo "error!";
}
$pathMode = $_POST["pathMode"];
$background = $_POST["background"];
MCDlog("$_POST\[\"pathMode\"\]=".$pathMode);

$ls = new LEDSummoner(WIDTH, HEIGHT, $bitmap, $pathMode, $background);
$cs = new ChassisSummoner();
$ls->bitmapMode();
// $cs->goMode($pathMode);

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