<?php 
require_once("logger.php");
class ChassisSummoner{
	public function goMode(int $mode){
		if($mode<1||$mode>5)
			return false;
		exec("/home/tsinghua/tshuang_driver/serial/build/path". $mode.".exe", $output);
		MCDlog($output);		
	}
}

 ?>