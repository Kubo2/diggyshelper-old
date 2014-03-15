<?php
// zapnutie output bufferingu (nemám iný spôsob posielania hlavičiek po výstupe) 
// @see http://php.net/ob-start
@ob_start();

// pridaná HTTP hlavička určujúca kódovanie (neviem, čo máš v head.php, ale pre istotu, keďže 
// si mi písal, že ti nejde utf8) -- diakritika by už mala fachať 
@header("Content-Type: text/html; charset=utf-8", true, 200);

// nikde to tu neni
session_start();
?>
<!DOCTYPE HTML>
<html>
<head>
	<style type="text/css">
	td {
		text-align: center;
		width: 300px;
	}

	tr:first-child td {
		background: #33cc00;
	}

	table {
		margin: auto;
	}
	</style>
	<?php include 'includes/head.php'; ?>
</head>
<body>
	<?php include 'includes/header.php'; ?>
	
	<?php include 'includes/menu.php'; ?>
	
<center>
<div id="loginpassage">
<?php
if (!isset($_SESSION['uid'])) {
	echo "<form action='login.php' method='post'>
	<input class='input' type='text' name='username' placeholder='Nickname' value='' />&nbsp;
	<input class='input' type='password' name='password' placeholder='Heslo' value='' />&nbsp;
	<input class='input' type='checkbox' name='remember'> Neodhlasovať ma&nbsp;
	<input type='submit' name='submit' class='input_button' value='Prihlásiť sa' />&nbsp;
	<a class='button_logout' href='#'>Zabudli ste heslo?</a>&nbsp;
	<a class='button_register' href='register.php'>Registrovať sa</a>
	";
} else {
	echo("Prihlásený používateľ: <b>$_SESSION[username]</b> &rsaquo; <a class='button_logout' href='logout.php'>Odhlásiť sa</a>");
}
?>
</div>
</center>
	
	<div id="statistiky">
	
	<?php
	require_once("connect.php");

	$membersCount = mysql_query("SELECT COUNT(*) FROM `users`");
	$newestMember = mysql_query("SELECT `id`, `username` 
FROM `users` 
WHERE `registerdate` = (
	SELECT MAX(`registerdate`) 
	FROM `users`
)
");
	$mostActiveMember = mysql_query("SELECT u.username, p.post_creator AS id, COUNT(p.post_creator) AS posts_count 
FROM posts p
JOIN users u on u.id = p.post_creator 
GROUP BY post_creator
ORDER BY posts_count DESC
LIMIT 1");
	$newestTopic = mysql_query("SELECT id, category_id, topic_title 
from topics 
where topic_date = (
	select max(topic_date) 
	from topics
)");
	$mostViewedTopic = mysql_query("SELECT t.id, t.category_id, t.topic_title, v.views 
FROM (
	SELECT MAX( topic_views ) AS views 
	FROM topics 
) v 
JOIN topics t ON t.topic_views = v.views 
LIMIT 1");
	
	$membersCount = $membersCount ? mysql_fetch_row($membersCount) : array(-1);
	if($newestMember)
		$newestMember = mysql_fetch_assoc($newestMember);
	if($mostActiveMember)
		$mostActiveMember = mysql_fetch_assoc($mostActiveMember);
	if($newestTopic)
		$newestTopic = mysql_fetch_assoc($newestTopic);
	if($mostViewedTopic)
		$mostViewedTopic = mysql_fetch_assoc($mostViewedTopic);



	?>
	
	<p style="text-align: center">
		Na Diggy's Helper už máme ... <?php echo $membersCount[0]; ?> ... užívateľov.
	</p>
	<p style="text-align: center">NAJ zo stránky Diggy's Helper</p>
	<table border="0" style="border-collapse:collapse;">
		<tr>
			<td>
				<font color="#FFF">Najnovší člen</font>
			</td>
			<td>
				<font color="#FFF">Najaktívnejší člen</font>
			</td>
			<td>
				<font color="#FFF">Najnovšia téma</font>
			</td>
			<td>
				<font color="#FFF">Najzobrazovanejšia téma</font>
			</td>
		</tr>
		<tr>
			<td>
				<?php
				if(!$newestMember)
					echo "Nedostupný.";
				else { ?>
				<a class="memberusers" href="./#member=<?php echo $newestMember['id']; ?>" rel="nofollow">
					<?php echo $newestMember['username']; ?>
				</a>
				<?php } ?>
			</td>
			<td>
				<?php
				if(!$mostActiveMember)
					echo "Nedostupný.";
				else { ?>
				<a class="memberusers" href="./#member=<?php echo $mostActiveMember['id']; ?>" rel="nofollow">
					<?php echo $mostActiveMember['username']; ?>
				</a> so svojimi <span style="color:red"><?php echo $mostActiveMember['posts_count']; ?></span> príspevkami
				<?php } ?>
			</td>
			<td>
				<?php
				if(!$newestTopic)
					echo "Žiadna.";
				else { ?>
				<a href="./view_topic.php?tid=<?php echo $newestTopic['id']; ?>&amp;cid=<?php echo $newestTopic['category_id']; ?>">
					<?php echo $newestTopic['topic_title']; ?>
				</a>
				<?php } ?>
			</td>
			<td>
				<?php
				if(!$mostViewedTopic)
					echo "Nie je.";
				else { ?>
				<a href="./view_topic.php?tid=<?php echo $mostViewedTopic['id']; ?>&amp;cid=<?php echo $mostViewedTopic['category_id']; ?>">
					<?php echo $mostViewedTopic['topic_title']; ?>
				</a>
				&rsaquo; Zobrazená <span style="color:red"><?php echo $mostViewedTopic['views']; ?></span> krát
				<?php } ?>
			</td>
		</tr>
	</table>
	</div>
	
	<?php include 'includes/footer.php'; ?>
</body>
</html>