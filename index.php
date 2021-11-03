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
		<form method="post" onsubmit="return checkbox(this)">
			<?php
				$conn = db_connect();

				$query = "SELECT * FROM book";
				$result = mysqli_query($conn, $query);

				echo "========== 책 목록 ==========<br>";
				while($row = mysqli_fetch_array($result)){
					echo '<input type="checkbox" name="checkbox[]">';
					echo '<input type="hidden" name="hidden[]" value="0">';
					echo '제목 : '.$row['title'].' / ';
					echo '가격 : '.$row['price'].' / ';
					echo '재고 : '.$row['stock'].' / ';
					$star = 0;
					$index = 0;
					$get_number = $row['number'];
					$temp_query = "SELECT * FROM order_has_book WHERE book_number = '$get_number'";
					$temp_result = mysqli_query($conn, $temp_query);
					while($temp_row = mysqli_fetch_array($temp_result)){
						if($temp_row['star'] != null){
							$get_order[] = $temp_row['order_number'];
							$get_star[] = $temp_row['star'];
							$star += $temp_row['star'];
							$index++;
						}
					}
					$aver = $star / $index;
					echo '별점 : '.round($aver, 2).' ';
					echo '<button type="submit" name="number" value="'.$get_number.'" formaction="./write.php">리뷰작성</button>';
					$tvalue = 0;
					$tquery = "SELECT * FROM review WHERE book_number = '$get_number'";
					$tresult = mysqli_query($conn, $tquery);
					while($trow = mysqli_fetch_array($tresult)){
						$tvalue++;
					}
					echo '   ';

					echo '<button type="submit" name="number2" value="'.$get_number.'" formaction="./review.php">리뷰보기 : '.$tvalue.'건 </button>';

					echo '<br>';
					
					for($index = 0; $index < count($get_order); $index++){
						$temp_query = "SELECT * FROM orders WHERE number = '$get_order[$index]'";
						$temp_result = mysqli_query($conn, $temp_query);
						while($temp_row = mysqli_fetch_array($temp_result)){
							$get_user = $temp_row['user_number'];
							$temp_temp_query = "SELECT * FROM user WHERE number = '$get_user'";
							$temp_temp_result = mysqli_query($conn, $temp_temp_query);
							$temp_temp_row = mysqli_fetch_array($temp_temp_result);
							echo $temp_temp_row['name'].'유저가 ';
							echo $get_order[$index].'번 주문에서 ';
							echo '별점'.$get_star[$index].'점 부여';
							echo '<br>';
						}
					}
					unset($get_order);
					unset($get_star);
				}
			?>
			<br>
			<input type="submit" value="주문" formaction="./order.php">
			<input type="submit" name="basket" value="담기" formaction="./index.php">
		</form>
	</section>
<?php include "./include/footer.php"; ?>

<?php
	if(isset($_POST['basket'])){
		basket_books();
	}

	function basket_books(){
		$get_user = $_SESSION['user'];
		$get_hidden = $_POST['hidden'];
		$conn = db_connect();

		//책 목록
		$query = "SELECT * FROM book";
		$result = mysqli_query($conn, $query);
		while($row = mysqli_fetch_array($result)){
			$book_number[] = $row['number'];
		}
		
		//장바구니에 고른 책 DB삽입
		for($index = 0; $index < count($get_hidden); $index++){
			if($get_hidden[$index]){
				$query = "INSERT INTO basket_has_book (basket_number, book_number) VALUES ('$get_user', '$book_number[$index]')";
				mysqli_query($conn, $query);
			}
		}
		go_location('./basket.php');
	}
?>