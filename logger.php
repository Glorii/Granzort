<?php 
//This function returns the number of bytes that were written to the file, or FALSE on failure.
function MCDlog(string $content){
	return file_put_contents("MCDlog.txt", php_self().date('y-m-d h:i:s', time()).":".$content, FILE_APPEND);
}

function php_self(){
	$php_self = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'],'/')+1);
	return $php_self;
}
	
 ?>