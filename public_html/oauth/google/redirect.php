<?php

require_once __DIR__ . '../../../../private_html/init.php';

if (isset($_SESSION['user']['google_id'])) {
	header('Location: /');
	return;
}

$query = http_build_query([
	'response_type' => 'code',
	'client_id' => GOOGLE_CLIENT_ID,
	'scope' => join(' ', ['openid', 'email']),
	'redirect_uri' => "https://" . $_SERVER['HTTP_HOST'] . '/oauth/google/callback.php'
]);

$url = 'https://accounts.google.com/o/oauth2/v2/auth?' . $query;

header('Location: ' . $url);
