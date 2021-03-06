<?php
header('Content-Type: text/html; charset=utf-8');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Tweets</title>
	<!-- Latest compiled and minified Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

	<!-- Optional Bootstrap theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

	<!-- Custom styles for this template -->
    <link href="/css/custom.css" rel="stylesheet">
</head>

<?php 

require_once ('classes/TwitterWrapper.php');
$TwitterWrapper = new TwitterWrapper();

if(isset($_GET['action'])) {
	$id = $_GET['id'];		
	if($_GET['action'] == 'favourite') {
		$TwitterWrapper->addToFavourites($id);
		echo "<h1>Added to favourites</h1>";
	} else if($_GET['action'] == 'retweet') {
		$TwitterWrapper->retweet($id);
		echo "<h1>Retweeted</h1>";
	} else if($_GET['action'] == 'answer') {
		echo "<form method='POST' action='index.php/?action=sendtweet&id=".$id."'>";
		echo "<p><b>Enter your answer here: </b></p>";
		echo "<textarea class='form-control' rows='3' maxlength='140' name='tweet'>@".$TwitterWrapper->getUsernameFor($id)."</textarea>";
		echo "<input class='btn btn-success' type='submit' value='Send'>";
		echo "</form>";
	} else if($_GET['action'] == 'sendtweet') {
		$tweet = $_POST['tweet'];
		$id = $_GET['id'];
		$TwitterWrapper->answerTo($id, $tweet);
		echo "<h1>Answered</h1>";
	}
}

?>


<body>
	<div class="site-wrapper">
		<div class="site-wrapper-inner">
			<div class="cover-container">
				<div class="masthead clearfix">
					<div class="inner">
						<h3 class="masthead-brand">Tweets</h3>
						<nav>
							<ul class="nav masthead-nav">
								<li class="active"><a href="/">Show tweets</a></li>
								<li><a href="/updatabase.php">Load tweets to DB</a></li>
							</ul>
						</nav>
					</div>
				</div>
				<br /> <br />
				<?php
					$result = $TwitterWrapper->getAllTweetsFromDatabase();
					while ($tweet = $result->fetch_array(MYSQLI_ASSOC)) {
						$id = $tweet['id'];
						$datetime = $tweet['datetime'];
						$year = substr($datetime, -4);
						$date = substr($datetime, 4, 12);
						$text = $tweet['text'];
			
						echo "<div class='highlight'>";
						echo "<h4>".$text."</h4>";
						echo "<br />";
						echo "<div class='row'>";
						echo "<div class='text-left col-md-6'>";
						echo "<a href='/index.php?id=".$id."&action=favourite'><input class='btn btn-success' type='button' name='favourite' value='Favourite'></a>";
						echo "<a href='/index.php?id=".$id."&action=retweet'><input class='btn btn-warning' type='button' name='retweet' value='Retweet'>";
						echo "<a href='/index.php?id=".$id."&action=answer'><input class='btn btn-info' type='button' name='answer' value='Answer'>";
						echo "</div>";
						echo "<div style='margin-top: 2%' class= 'text-right col-md-6'>";
						echo $date.", ".$year;
						echo "</div>";
						echo "</div>";
						echo "</div>";
					} 
				?>
			</div>
		</div>
	</div>
</body>
</html>
