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
		<?php
			$get_books = $_POST['number2'];

			$conn = db_connect();

			$tquery = "SELECT * FROM review WHERE book_number = '$get_books'";
			$tresult = mysqli_query($conn, $tquery);
			while($trow = mysqli_fetch_array($tresult)){
				$name = $trow['user_number'];
				$result = mysqli_query($conn, "SELECT * FROM user WHERE number = '$name'");
				$row = mysqli_fetch_array($result);
				echo $row['name'].' : ';
				echo $trow['review'];
				echo '<br>';
			}
		?>
	</section>
<?php include "./include/footer.php"; ?>