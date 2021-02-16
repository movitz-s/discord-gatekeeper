<?php

require_once __DIR__ . '../../../../private_html/init.php';

if (isset($_SESSION['user']['google_id'])) {
	header('Location: /');
	return;
}

$body = [
	'grant_type' => 'authorization_code',
	'code' => $_GET["code"],
	'redirect_uri' => 'https://' . $_SERVER['HTTP_HOST'] . '/oauth/google/callback.php',
	'client_id' => GOOGLE_CLIENT_ID,
	"client_secret" => GOOGLE_CLIENT_SECRET
];

$resp = $client->makeRequest('https://oauth2.googleapis.com/token', $body, 'POST');
if ($resp['status'] !== 200) {
	die('Något gick fel');
}

$idToken = json_decode(base64_decode(explode(".", $resp['body']->id_token)[1]));

$user = $db->run('SELECT * FROM discord_inviter_users WHERE google_id = ?;', [$idToken->sub])->fetch();

if (!$user) {
	$db->run('INSERT INTO discord_inviter_users (google_id, school_email) VALUES (?, ?);', [$idToken->sub, $idToken->email]);
}

$_SESSION['user'] = [
	'google_id' => $idToken->sub,
	'email' => $idToken->email
];

header('Location: /');