<?php 
require_once("logger.php");
class ChassisSummoner{

	public function gogogo($pathMode){
		switch ($pathMode) {
			case 1:
				$this->goMode1();
				break;
			case 2:
				$this->goMode2();
				break;
			case 3:
				$this->goMode3();
				break;
			case 4:
				$this->goMode4();
				break;
			case 5:
				$this->goMode5();
				break;								
			default:
				# code...
				break;
		}
	}

	private function goMode1(){
		exec("/home/tsinghua/tshuang_driver/serial/build/main.exe 1", $output);
		MCDlog($output);
	}
	private function goMode2(){
		exec("/home/tsinghua/tshuang_driver/serial/build/main.exe 2", $output);
		MCDlog($output);
	}
	private function goMode3(){
		exec("/home/tsinghua/tshuang_driver/serial/build/main.exe 3", $output);
		MCDlog($output);
	}
	private function goMode4(){
		exec("/home/tsinghua/tshuang_driver/serial/build/main.exe 4", $output);
		MCDlog($output);
	}
	private function goMode5(){
		exec("/home/tsinghua/tshuang_driver/serial/build/main.exe 5", $output);
		MCDlog($output);
	}
}

 ?>