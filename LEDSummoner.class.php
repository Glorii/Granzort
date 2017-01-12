<?php 
require_once("logger.php");
class LEDSummoner{
	public $width, $height, $bitmap, $pathMode, $background;
	private $comPort = "/dev/ttyACM0"; /*change to correct com port */

	public function __construct($width, $height, $bitmap, $pathMode, $background){
		$this->width = $width;
		$this->height = $height;
		$this->bitmap = $bitmap;
		$this->pathMode = $pathMode;
		$this->background = $background;
	}

	private function array2str($bitmap){
		$str = "";
		for ($i=0; $i < $this->width; $i++) {
			for ($j=0; $j < $this->height; $j++) { 
				$str .= $bitmap[$i][$j];
			}
		}
		return $str;
	}
	public function bitmapMode(){
		$fp =fopen($this->comPort, "w");
		fwrite($fp, $this->pathMode . $this->background . $this->array2str($this->bitmap));
		fclose($fp);
		MCDlog("----pathMode = " .$this->pathMode."----background = " . $this->background . $this->array2str($this->bitmap));
	}
}

 ?>