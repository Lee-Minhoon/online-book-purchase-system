<script src="include/function.js"></script>
<?php
	include "./include/function.php";
	include "./include/common.php";
	include "./include/header.php";
?>
	<section>
		<form method="post" action="register.php">
			<input type="text" name="name" placeholder="이름">
			<input type="text" name="id" placeholder="아이디">
			<input type="password" name="pw" placeholder="비밀번호">
			<input type="submit" name="submit" value="완료">
			<button type="button" onclick="location.href = './login.php'">취소</button>
		</form>
	</section>
<?php include "./include/footer.php"; ?>

<?php
	if(isset($_POST['submit'])){
		user_register();
	}
	
	function user_register(){
		$get_name = $_POST['name'];
		$get_id = $_POST['id'];
		$get_pw = $_POST['pw'];
		$conn = db_connect();

		//아이디 공백검사
		if($get_name == null || $get_id == null || $get_pw == null){
			alert_location('공백이 있습니다.', './register.php');
			exit();
		}

		//아이디 중복검사
		$query = "SELECT * FROM user WHERE id = '$get_id'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		if($row){
			alert_location('존재하는 ID입니다.', './register.php');
			exit();
		}

		//회원 DB삽입
		$query = "INSERT INTO user (name, id, pw) VALUES ('$get_name', '$get_id', '$get_pw')";
		mysqli_query($conn, $query);

		//장바구니 DB삽입
		$get_last = mysqli_insert_id($conn);
		$get_date = date("Y-m-d");
		$query = "INSERT INTO basket (user_number, date) VALUES ('$get_last', '$get_date')";
		mysqli_query($conn, $query);
		go_location('./login.php');
	}
?>