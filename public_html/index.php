<?php

require_once __DIR__ . '/../private_html/init.php';

if (!isset($_SESSION['user']['google_id'])) {
	$stepOne = true;
} else if (!isset($_SESSION['user']['discord_id'])) {
	$stepTwo = true;
} else {
	$stepThree = true;
}

?>

<html>

<head>
	<title>SSIS Discord</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>

<body>
	<div class="container d-flex align-items-center justify-content-center" style="height: 100vh;">
		<div class="row">
			<div class="col text-center">
				<h5>1. Logga in med din skolmejl</h5>

				<a class="btn btn-primary mx-auto <?= !isset($stepOne) ? 'disabled' : '' ?>" href="/oauth/google/redirect.php">
					Logga in
				</a>
			</div>

			<div class="col text-center">
				<h5>2. Logga in med Discord</h5>

				<a class="btn btn-primary <?= !isset($stepTwo) ? 'disabled' : '' ?>" href="/oauth/discord/redirect.php">
					Logga in
				</a>
			</div>

			<div class="col text-center">
				<h5>3. Anslut till servern</h5>
				<a class="btn btn-primary <?= !isset($stepThree) ? 'disabled' : '' ?>" href="/add_member.php">Gå med!</a>
			</div>
		</div>
	</div>
</body>

</html>