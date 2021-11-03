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
				$get_user = $_SESSION['user'];
				$conn = db_connect();

				$query = "SELECT * FROM basket_has_book WHERE basket_number = '$get_user'";
				$result = mysqli_query($conn, $query);

				echo "========== ".$_SESSION['name']." 님의 장바구니 ==========<br>";
				while($row = mysqli_fetch_array($result)){
					echo '<input type="checkbox" name="checkbox[]">';
					echo '<input type="hidden" name="hidden[]" value="0">';
					$basket_book = $row['book_number'];
					$book_query = "SELECT * FROM book WHERE number = '$basket_book'";
					$book_result = mysqli_query($conn, $book_query);
					$book_row = mysqli_fetch_array($book_result);
					echo '제목 : '.$book_row['title'].' / ';
					echo '가격 : '.$book_row['price'].' / ';
					echo '재고 : '.$book_row['stock'];
					echo '<br>';
				}
			?>
			<br>
			<input type="submit" value="주문" formaction="./order.php">
			<input type="submit" name="delete" value="삭제" formaction="./basket.php">
		</form>
	</section>
<?php include "./include/footer.php"; ?>

<?php
	if(isset($_POST['delete'])){
		delete_basket();
	}

	function delete_basket(){
		$get_user = $_SESSION['user'];
		$get_hidden = $_POST['hidden'];
		$conn = db_connect();

		$query = "SELECT * FROM basket_has_book WHERE basket_number = '$get_user'";
		$result = mysqli_query($conn, $query);
		while($row = mysqli_fetch_array($result)){
			$basket_book[] = $row['book_number'];
		}

		for($index = 0; $index < count($get_hidden); $index++){
			if($get_hidden[$index]){
				$query = "DELETE FROM basket_has_book WHERE book_number = '$basket_book[$index]'";
				mysqli_query($conn, $query);
			}
		}
		go_location('./basket.php');
	}
?>