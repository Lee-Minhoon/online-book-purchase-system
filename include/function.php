<?php
	session_start();
	
	function db_connect(){
		$conn = mysqli_connect("localhost", "root", "1234", "books");
		return $conn;
	}

	function check_user(){
		if(!isset($_SESSION['id'])){
			echo "<script>location.href = './login.php'</script>";
		}
	}

	function display_user(){
		echo '<section>';
		$get_user = $_SESSION['user'];
		$conn = db_connect();
		$query = "SELECT * FROM user WHERE number = '$get_user'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		$get_price = $row['price'];
		echo '현재로그인중 : ';
		echo $_SESSION['name'];
		echo '[';
		echo $_SESSION['id'];
		echo ', ';
		if($get_price > 5000000){
			echo 'VVIP';
		}else if($get_price > 1000000){
			echo 'VIP';
		}else{
			echo 'N';
		}
		echo '(주문금액 : '.$get_price.')';
		echo ']';
		echo '</section>';
	}

	function go_location($href){	
		echo "<script>location.href = '$href';</script>";
	}

	function alert_back($alert){
		echo "<script>if(!alert('$alert')) history.back();</script>";
	}

	function alert_location($alert, $href){
		echo "<script>if(!alert('$alert')) location.href = '$href';</script>";
	}
?>