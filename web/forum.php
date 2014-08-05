<?php

if(isset($_COOKIE[ini_get('session.name')])) session_start();

// <production server> only
error_reporting(E_ALL & ~E_WARNING/* | ~E_NOTICE*/);

// 200 can be replaced, but Content-Type header is neccessary
header("Content-Type: text/html; charset=utf-8", true, 200);

if(FALSE === require('connect.php')) {
	if(!defined('DB_ERROR'))
		define('DB_ERROR', true);
	header("Retry-After: 600", true, 503);
}

// pre odkomentovanie doctypu jednoducho odstráň sekvenciu -- zo začiatku aj z konca
?>
<!--DOCTYPE HTML-->
<html>
<head>
	<?php include 'includes/head.php'; ?>
</head>
<body>
	<?php
		include 'includes/header.php';
		include 'includes/menu.php';
		include 'includes/submenu.php';
	?>
	
<div id="forum">
	<div id="content">
	<?php if(defined('DB_ERROR')) { ?>
		<p>
			Ľutujeme, ale nič tu nieje. Naša databáza je momentálne na niekoľko minút 
			nedostupná. Už pracujeme na rýchlom vyriešení problému. Prosím, skúste 
			sa sem vrátiť o chvíľočku.
		</p>
	<?php	} else {
		$crs = mysql_query(' SELECT `id`, `category_title`, `category_description` FROM `categories` ORDER BY `category_title` ASC ');

		if($crs && mysql_num_rows($crs) > 0) {
			while(list($cId, $cTitle, $cDescription) = mysql_fetch_row($crs)) { ?>
		<a class="cat_links categories links" href="./view.php?cid=<?php echo($cId) ?>">
		<?php echo($cTitle), '<br>', $cDescription; // TODO: fixnúť menší font ?>
		</a>
			<?php }
		} else {
			echo "<p>Zatial nie sú k dispozícii žiadne kategórie.</p>";
		}
	}
	?>
</div>
</div>
	<?php include 'includes/footer.php'; ?>
</body>
</html>