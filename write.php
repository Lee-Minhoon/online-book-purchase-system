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
		<form method="post" action="./write.php">
			
			<input type="text" name="review" placeholder="리뷰를 입력하세요">
			<?php
				$get_books = $_POST['number'];
				echo '<input type="hidden" name="hidden" value="'.$get_books.'"';
			?>
			<br>
			<input type="submit" name="callreview" value="완료">
		</form>
	</section>
<?php include "./include/footer.php"; ?>

<?php
	if(isset($_POST['callreview'])){
		call_review();
	}

	function call_review(){
		$get_user = $_SESSION['user'];
		$get_review = $_POST['review'];
		$get_hidden = $_POST['hidden'];
		$conn = db_connect();

		//책 목록
		$query = "INSERT INTO review (user_number, book_number, review) VALUES ('$get_user', '$get_hidden', '$get_review')";
		mysqli_query($conn, $query);
		go_location('./index.php');
	}
?>