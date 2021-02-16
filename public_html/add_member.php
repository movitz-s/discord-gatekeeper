<?php
require_once __DIR__ . '/../private_html/init.php';

if (!isset($_SESSION['user']['discord_access_token'])) {
	header('Location: /');
	return;
}

$ldapConn = ldap_connect("ldaps://ad.ssis.nu") or die("Vi kunde inte koppla till LDAP. (1)");

$bind = ldap_bind($ldapConn, LDAP_USER . "@ad.ssis.nu", LDAP_PASSWORD) or die("Vi kunde inte koppla till LDAP. (2)");

$s = ldap_search($ldapConn, "OU=Elever,DC=ad,DC=ssis,DC=nu", "(|(cn=" . explode("@", $_SESSION['user']['email'])[0] . "))", array("cn", "givenName", "sn", "memberOf")) or die("Vi kunde inte koppla till LDAP. (3)");
$info = ldap_get_entries($ldapConn, $s) or die("Vi kunde inte koppla till LDAP. (4)");

$name = $info[0]['givenname'][0] . ' ' . $info[0]['sn'][0];

$klass = '';
foreach ($info[0]['memberof'] as $group) {
	$tmp = substr($group, 3, 5);

	if (preg_match('/..\d\d./', $tmp)) {
		$klass = $tmp;
		break;
	}
}

if (!isset($klassToRoleId[$klass])) {
	die('Kunde inte hitta din klass. Be en admin att lägga till dig manuellt.');
}

$body = [
	'access_token' => $_SESSION['user']['discord_access_token'],
	'nick' => "$name ($klass)", // TODO HANDLE IF NAME TOO LONG
	'roles' => [$klassToRoleId[$klass]]
];

$url = 'https://discordapp.com/api/guilds/' . DISCORD_GUILD_ID . '/members/'. $_SESSION['user']['discord_id'];

$resp = $client->makeRequest($url, 'PUT', json_encode($body), ['Authorization: Bot ' . DISCORD_BOT_TOKEN, 'Content-Type: application/json']);

header('Location: https://discord.com/channels/' . DISCORD_GUILD_ID);