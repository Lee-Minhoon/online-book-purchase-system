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
		<button type="button" onclick="location.href = './address.php'">배송지</button>
		<button type="button" onclick="location.href = './credit.php'">카드</button>
		<button type="button" onclick="location.href = './basket.php'">장바구니</button>
		<button type="button" onclick="location.href = './history.php'">주문내역</button>
	</section>
<?php include "./include/footer.php"; ?>