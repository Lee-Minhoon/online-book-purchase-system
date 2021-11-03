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

				$query = "SELECT * FROM orders WHERE user_number = '$get_user'";
				$result = mysqli_query($conn, $query);

				$index = 1;
				echo "========== ".$_SESSION['name']." 님의 주문내역 ==========<br>";
				while($row = mysqli_fetch_array($result)){
					echo $index.' / ';
					echo '날짜 : '.$row['date'].' / ';
					echo '주문총액 : '.$row['price'].' ';
					$get_number = $row['number'];
					echo '<button type="submit" name="number" value="'.$get_number.'" formaction="./detail.php">상세보기</button>';
					echo '<br>';
					$index++;
				}
			?>
			<br>
		</form>
	</section>
<?php include "./include/footer.php"; ?>