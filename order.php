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
		<form method="post" action="./order.php">
			<?php
				$get_user = $_SESSION['user'];
				$get_hidden = $_POST['hidden'];
				$conn = db_connect();

				//배송지선택 콤보박스
				echo '<select name = "address">';
				echo '<option value="">배송지선택</option>';
				$query = "SELECT * FROM user_has_address WHERE user_number = '$get_user'";
				$result = mysqli_query($conn, $query);
				while($row = mysqli_fetch_array($result)){
					$base = $row['address_base'];
					$detail = $row['address_detail'];
					$zipcode = $row['zipcode'];
					echo "<option value='$base,$detail,$zipcode'>$base $detail</option>";
				}
				echo '</select> ';

				//카드선택 콤보박스
				echo '<select name = "credit">';
				echo '<option value="">카드선택</option>';
				$query = "SELECT * FROM credit WHERE user_number = '$get_user'";
				$result = mysqli_query($conn, $query);
				while($row = mysqli_fetch_array($result)){
					$number = $row['number'];
					$date = $row['date'];
					$kind = $row['kind'];
					echo "<option value='$number,$date,$kind'>$number $kind</option>";
				}
				echo '</select><br><br><br>';

				//주문 목록
				$query = "SELECT * FROM book";
				$result = mysqli_query($conn, $query);

				$index = 0;
				echo "========== ".$_SESSION['name']." 님의 주문 목록 ==========<br>";
				while($row = mysqli_fetch_array($result)){
					if($get_hidden[$index]){
						echo '제목 : '.$row['title'].' / ';
						echo '가격 : '.$row['price'].' / ';
						echo '재고 : '.$row['stock'].' ';
						$book_number = $row['number'];
						echo '<input type="hidden" name="book[]" value="'.$book_number.'">';
						echo '<input type="text" name="inputbox[]" placeholder="주문개수입력">';
						echo '<br>';
					};
					$index++;
				}
			?>
			<br>
			<input type="submit" name="order" value="완료">
			<button type="button" onclick="go_back();">취소</button>
		</form>
	</section>
<?php include "./include/footer.php"; ?>

<?php
	if(isset($_POST['order'])){
		order();
	}

	function order(){
		$get_user = $_SESSION['user'];
		$get_address = $_POST['address'];
		$get_credit = $_POST['credit'];
		$get_book = $_POST['book'];
		$get_input = $_POST['inputbox'];
		$conn = db_connect();

		//주문 공백검사
		if($get_credit == null || $get_address == null){
			alert_back('배송지나 카드선택이 비어있습니다.');
			exit();
		}
		$get_address = explode(',', $get_address);
		$get_credit = explode(',', $get_credit);

		//책 수량 공백검사
		for($index = 0; $index < count($get_input); $index++){
			if($get_input[$index] > 0){
			}else{
				alert_back('주문수량이 비어있습니다.');
				exit();
			}
		}

		//책 재고검사
		for($index = 0; $index < count($get_input); $index++){
			$query = "SELECT * FROM book WHERE number = '$get_book[$index]'";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				if($get_input[$index] > $row['stock']){
					alert_back('재고가 부족합니다.');
					exit();
				}
			}
		}

		//재고 업데이트, 주문총액 구하기
		$total = 0;
		for($index = 0; $index < count($get_input); $index++){
			$query = "SELECT * FROM book WHERE number = '$get_book[$index]'";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_array($result)){
				$update_stock = $row['stock'] - $get_input[$index];
				$query = "UPDATE book SET stock = '$update_stock' WHERE number = '$get_book[$index]'";
				mysqli_query($conn, $query);
				$get_price[] = $row['price'];
				$total += $get_price[$index] * $get_input[$index];
			}
		}

		//할인율 구하기
		$query = "SELECT price FROM user WHERE number = '$get_user'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_array($result);
		$get_price = $row['price'];
		$update = $get_price + $total;
		$discount;
		if($get_price > 5000000){
			$discount = $total * 0.90;
		}else if($get_price > 1000000){
			$discount = $total * 0.95;
		}else{
			$discount = $total;
		}

		//주문 DB삽입
		$get_date = date("Y-m-d");
		$query = "INSERT INTO orders (user_number, date, price, credit_number, credit_date, credit_kind, add_base, add_detail, add_zipcode) VALUES ('$get_user', '$get_date', '$discount', '$get_credit[0]', '$get_credit[1]', '$get_credit[2]', '$get_address[0]', '$get_address[1]', '$get_address[2]')";
		mysqli_query($conn, $query);

		//주문의 책 DB삽입
		$get_last = mysqli_insert_id($conn);
		for($index = 0; $index < count($get_input); $index++){
			$total = $get_price[$index] * $get_input[$index];
			$query = "INSERT INTO order_has_book (order_number, book_number, count, price) VALUES ('$get_last', '$get_book[$index]', '$get_input[$index]', '$total')";
			mysqli_query($conn, $query);
		}

		//유저 주문총액 업데이트
		$query = "UPDATE user SET price = '$update' WHERE number = '$get_user'";
		mysqli_query($conn, $query);
		go_location('./history.php');
	}
?>