<script src="include/function.js"></script>
<?php
	include "./include/function.php";
	include "./include/common.php";
	include "./include/header.php";
	check_user();
	display_user();
	include "./include/menu.php";
?>
	<section>
		<form method="post" action="./address.php">
			<input type="text" name="base" placeholder="기본주소">
			<input type="text" name="detail" placeholder="상세주소">
			<input type="text" name="zipcode" placeholder="우편번호">
			<input type="submit" name="register" value="배송지등록">
			<br><br>
		</form>
		<form method="post" action="./address.php" onsubmit="return checkbox(this)">
			<?php
				$get_user = $_SESSION['user'];
				$conn = db_connect();

				$query = "SELECT * FROM user_has_address WHERE user_number = '$get_user'";
				$result = mysqli_query($conn, $query);

				echo "========== ".$_SESSION['name']." 님의 배송지 목록 ==========<br>";
				while($row = mysqli_fetch_array($result)){
					echo '<input type="checkbox" name="checkbox[]">';
					echo '<input type="hidden" name="hidden[]" value="0">';
					echo '기본주소 : '.$row['address_base'].' / ';
					echo '상세주소 : '.$row['address_detail'].' / ';
					echo '우편번호 : '.$row['zipcode'];
					echo '<br>';
				}
			?>
			<br>
			<input type="submit" name="delete" value="삭제">
		</form>
	</section>
<?php include "./include/footer.php"; ?>

<?php
	if(isset($_POST['register'])){
		register_address();
	}else if(isset($_POST['delete'])){
		delete_address();
	}

	function register_address(){
		$get_base = $_POST['base'];
		$get_detail = $_POST['detail'];
		$get_zipcode = $_POST['zipcode'];
		$get_user = $_SESSION['user'];
		$conn = db_connect();

		//주소 공백검사
		if($get_base == null || $get_detail == null || $get_zipcode == null){
			alert_location('공백이 있습니다.', './address.php');
			exit();
		}

		//개인주소 중복검사
		$query = "SELECT * FROM user_has_address WHERE user_number = '$get_user' AND address_base = '$get_base' AND address_detail = '$get_detail'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		if($row >= 1){
			alert_location('이미 존재하는 개인 배송지입니다.', './address.php');
			exit();
		}

		//개인배송지 등록성공, 사이트에 존재하는 배송지
		$query = "SELECT * FROM address WHERE base = '$base' AND detail = '$detail'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		if($row >= 1){
			//개인배송지 DB삽입
			$query = "INSERT INTO user_has_address (user_number, address_base, address_detail, zipcode) VALUES ('$get_user', '$get_base', '$get_detail', '$get_zipcode')";
			mysqli_query($conn, $query);
			alert_location('이미 존재하는 개인 배송지입니다.', './address.php');

		//개인배송지 등록성공, 사이트에 존재하지 않는 배송지
		}else{
			//배송지 DB삽입
			$query = "INSERT INTO address (base, detail, zipcode) VALUES ('$get_base', '$get_detail', '$get_zipcode')";
			mysqli_query($conn, $query);

			//개인배송지 DB삽입
			$query = "INSERT INTO user_has_address (user_number, address_base, address_detail, zipcode) VALUES ('$get_user', '$get_base', '$get_detail', '$get_zipcode')";
			mysqli_query($conn, $query);
			go_location('./address.php');
		}
	}

	function delete_address(){
		$get_user = $_SESSION['user'];
		$get_hidden = $_POST['hidden'];
		$conn = db_connect();

		//접속중인 사용자의 개인배송지
		$query = "SELECT * FROM user_has_address WHERE user_number = '$get_user'";
		$result = mysqli_query($conn, $query);
		while($row = mysqli_fetch_array($result)){
			$address_base[] = $row['address_base'];
			$address_detail[] = $row['address_detail'];
		}
		
		//체크박스와 비교하여 삭제
		for($index = 0; $index < count($get_hidden); $index++){
			if($get_hidden[$index]){
				$query = "DELETE FROM user_has_address WHERE address_base = '$address_base[$index]' AND address_detail = '$address_detail[$index]'";
				mysqli_query($conn, $query);
			}
		}
		go_location('./address.php');
	}
?>