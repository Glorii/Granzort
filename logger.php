<?php 

//This function returns the number of bytes that were written to the file, or FALSE on failure.
function MCDlog($content){
	$result = file_put_contents("MCDlog.txt", php_self().date('y-m-d h:i:s', time()).":".$content."\n", FILE_APPEND);
	return $result;
}

function php_self(){
	$php_self = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'],'/')+1);
	return $php_self;
}
	
 ?>