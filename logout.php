<script src="include/function.js"></script>
<?php
	include "./include/function.php";
	session_start();
	$var = session_destroy();
	if($var){
		go_location('./login.php');
	}
?>