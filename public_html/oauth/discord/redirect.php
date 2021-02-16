<?php

require_once __DIR__ . '../../../../private_html/init.php';

if (!isset($_SESSION['user']['google_id'])) {
	header('Location: /');
	return;
}

$query = http_build_query([
	'response_type' => 'code',
	'client_id' => DISCORD_CLIENT_ID,
	'scope' => join(' ', ['identify', 'guilds.join']),
	'redirect_uri' => 'https://' . $_SERVER['HTTP_HOST'] . '/oauth/discord/callback.php'
]);

header("Location: https://discordapp.com/api/oauth2/authorize?$query");
