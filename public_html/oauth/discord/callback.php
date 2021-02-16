<?php

require_once __DIR__ . '../../../../private_html/init.php';

if (!isset($_SESSION['user']['google_id'])) {
	header('Location: /');
	return;
}

$body = [
	'grant_type' => 'authorization_code',
	'code' => $_GET["code"],
	'redirect_uri' => 'https://' . $_SERVER['HTTP_HOST'] . '/oauth/discord/callback.php',
	'client_id' => DISCORD_CLIENT_ID,
	'client_secret' => DISCORD_CLIENT_SECRET
];

$resp = $client->makeRequest('https://discordapp.com/api/oauth2/token', 'POST', $body);
if ($resp['responseStatus'] !== 200) {
	die('Något gick fel');
}

$accessToken = json_decode($resp['body'])->access_token;

$userResp = $client->makeRequest('https://discordapp.com/api/users/@me', 'GET', '', ["Authorization: Bearer $accessToken"]);
if ($userResp['responseStatus'] !== 200) {
	die('Något gick fel');
}

$discordId = json_decode($userResp['body'])->id;

$user = $db->run('SELECT * FROM discord_inviter_users WHERE discord_id != ? AND discord_id != "" AND google_id = ?;', [$idToken->sub, $_SESSION['user']['google_id']])->fetch();

if ($user) {
	die('Du har redan kopplat ett Discord konto');
}

$db->run('UPDATE discord_inviter_users SET discord_id = ? WHERE google_id = ?', [$discordId, $_SESSION['user']['google_id']]);

$_SESSION['user']['discord_id'] = $discordId;
$_SESSION['user']['discord_access_token'] = $accessToken;

header('Location: /');
