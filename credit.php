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
		<form method="post" action="./credit.php">
			<input type="text" name="number" placeholder="카드번호">
			<input type="text" name="date" placeholder="유효기간">
			<input type="text" name="kind" placeholder="카드종류">
			<input type="submit" name="register" value="카드등록">
			<br><br>
		</form>
		<form method="post" action="./credit.php" onsubmit="return checkbox(this)">
			<?php
				$get_user = $_SESSION['user'];
				$conn = db_connect();

				$query = "SELECT * FROM credit WHERE user_number = '$get_user'";
				$result = mysqli_query($conn, $query);

				echo "========== ".$_SESSION['name']." 님의 카드 목록 ==========<br>";
				while($row = mysqli_fetch_array($result)){
					echo '<input type="checkbox" name="checkbox[]">';
					echo '<input type="hidden" name="hidden[]" value="0">';
					echo '카드번호 : '.$row['number'].' / ';
					echo '유효기간 : '.$row['date'].' / ';
					echo '카드종류 : '.$row['kind'];
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
		register_credit();
	}else if(isset($_POST['delete'])){
		delete_credit();
	}

	function register_credit(){
		$get_number = $_POST['number'];
		$get_date = $_POST['date'];
		$get_kind = $_POST['kind'];
		$get_user = $_SESSION['user'];
		$conn = db_connect();

		//카드 공백검사
		if($get_number == null || $get_date == null || $get_kind == null){
			alert_location('공백이 있습니다.', './credit.php');
			exit();
		}

		//개인주소 중복검사
		$query = "SELECT * FROM credit WHERE number = '$get_number'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		if($row >= 1){
			alert_location('이미 존재하는 카드입니다.', './credit.php');
			exit();
		}

		//카드 DB삽입
		$query = "INSERT INTO credit (number, user_number, date, kind) VALUES ('$get_number', '$get_user', '$get_date', '$get_kind')";
		mysqli_query($conn, $query);
		go_location('./credit.php');
	}

	function delete_credit(){
		$get_user = $_SESSION['user'];
		$get_hidden = $_POST['hidden'];
		$conn = db_connect();

		//접속중인 사용자의 카드
		$query = "SELECT * FROM credit WHERE user_number = '$get_user'";
		$result = mysqli_query($conn, $query);
		while($row = mysqli_fetch_array($result)){
			$card_number[] = $row['number'];
		}
		
		//체크박스와 비교하여 삭제
		for($index = 0; $index < count($get_hidden); $index++){
			if($get_hidden[$index]){
				$query = "DELETE FROM credit WHERE number = '$card_number[$index]'";
				mysqli_query($conn, $query);
			}
		}
		go_location('./credit.php');
	}
?>