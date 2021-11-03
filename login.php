<script src="include/function.js"></script>
<?php
	include "./include/function.php";
	include "./include/common.php";
	include "./include/header.php";
?>
	<section>
		<form method="post" action="login.php">
			<input type="text" name="id" placeholder="아이디">
			<input type="password" name="pw" placeholder="비밀번호">
			<input type="submit" name="submit" value="로그인">
			<button type="button" onclick="location.href = './register.php'">회원가입</button>
		</form>
	</section>
<?php include "./include/footer.php"; ?>

<?php
	if(isset($_POST['submit'])){
		login();
	}
	
	function login(){
		$get_id = $_POST['id'];
		$get_pw = $_POST['pw'];
		$conn = db_connect();

		//로그인 성공
		$query = "SELECT * FROM user WHERE id = '$get_id'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		if($get_id == $row['id'] && $get_pw == $row['pw']){
			$_SESSION['id'] = $row['id'];
			$_SESSION['name'] = $row['name'];
			$_SESSION['user'] = $row['number'];
			go_location('./index.php');

		//비밀번호 틀림
		}else if($get_id == $row['id'] && $get_pw != $row['pw']){
			alert_location('비밀번호가 틀렸습니다.', './login.php');

		//아이디 틀림
		}else{
			alert_location('아이디가 틀렸습니다.', './login.php');
		}
	}
?>