<?php

require_once __DIR__ . '/../private_html/init.php';

if (!isset($_SESSION['user']['google_id'])) {
	echo '<a href="/oauth/google/redirect.php">login google</a>';
} else if (!isset($_SESSION['user']['discord_id'])) {
	echo '<a href="/oauth/discord/redirect.php">login discord</a>';
} else {
	echo '<a href="/add_member.php">join discord</a>';
}
