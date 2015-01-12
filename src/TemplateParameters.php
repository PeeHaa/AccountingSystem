<?php

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'null';

return [
	'title' => 'Accounting System',
    'username' => $username,
    'errors' => '',
    'messages' => ''
];