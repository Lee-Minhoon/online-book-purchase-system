<script src="include/function.js"></script>
<?php
	session_cache_limiter("private_no_expire");
	include "./include/function.php";
	include "./include/common.php";
	include "./include/header.php";
	check_user();
	display_user();
	include "./include/menu.php";
?>
	<section>
		<form method="post" onsubmit="return checkbox(this)">
			<?php
				$get_number = $_POST['number'];
				$conn = db_connect();

				$query = "SELECT * FROM orders WHERE number = '$get_number'";
				$result = mysqli_query($conn, $query);

				echo "========== 상세보기 ==========<br>";
				while($row = mysqli_fetch_array($result)){
					echo '주문번호 : '.$row['number'].'<br>';
					echo '회원번호 : '.$row['user_number'].'<br>';
					echo '주문날짜 : '.$row['date'].'<br>';
					echo '주문총액 : '.$row['price'].'<br>';
					echo '카드번호 : '.$row['credit_number'].'<br>';
					echo '유효기간 : '.$row['credit_date'].'<br>';
					echo '카드종류 : '.$row['credit_kind'].'<br>';
					echo '기본주소 : '.$row['add_base'].'<br>';
					echo '상세주소 : '.$row['add_detail'].'<br>';
					echo '우편번호 : '.$row['add_zipcode'].'<br><br>';
				}

				$query = "SELECT * FROM order_has_book WHERE order_number = '$get_number'";
				$result = mysqli_query($conn, $query);
				while($row = mysqli_fetch_array($result)){
					$get_book[] = $row['book_number'];
					$get_count[] = $row['count'];
					$get_price[] = $row['price'];
					$get_star[] = $row['star'];
				}

				echo '<input type="hidden" name="order" value="'.$get_number.'">';
				for($index = 0; $index < count($get_book); $index++){
					$query = "SELECT * FROM book WHERE number = '$get_book[$index]'";
					$result = mysqli_query($conn, $query);
					while($row = mysqli_fetch_array($result)){
						echo '주문한책 : '.$row['title'].' / ';
						echo '책 가격 : '.$row['price'].' / ';
						echo '주문개수 : '.$get_count[$index].' / ';
						echo '소계 : '.$get_price[$index].' / ';
						if($get_star[$index] == null){
							$book_number = $row['number'];
							echo '<input type="hidden" name="book[]" value="'.$book_number.'">';
							echo '<input type="text" name="inputbox[]" placeholder="별점입력"><br>';
						}else{
							echo '별점 : '.$get_star[$index].'<br>';
						}
					}
				}
			?>
			<br><br>
			<input type="submit" name="star" value="별점주기">
			<button type="button" onclick="location.href = './history.php'">뒤로가기</button>
		</form>
	</section>
<?php include "./include/footer.php"; ?>

<?php
	if(isset($_POST['star'])){
		give_star();
	}

	function give_star(){
		$get_order = $_POST['order'];
		$get_book = $_POST['book'];
		$get_input = $_POST['inputbox'];
		$conn = db_connect();

		//별점 범위검사
		for($index = 0; $index < count($get_input); $index++){
			if($get_input[$index] < 0 || $get_input[$index] > 5){
				alert_back('별점은 0점이상 5점이하만 입력가능합니다.');
				exit();
			}
		}

		//별점 DB삽입
		for($index = 0; $index < count($get_input); $index++){
			$query = "UPDATE order_has_book SET star = '$get_input[$index]' WHERE order_number = '$get_order' AND book_number = '$get_book[$index]'";
			mysqli_query($conn, $query);
		}

		echo '<form name="form" method="post" action="./detail.php">';
		echo '<input type="hidden" name="number" value="'.$get_order.'">';
		echo '<script>document.form.submit();</script>';
		echo '</form>';
	}
?>