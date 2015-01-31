<?php

$configuration = require "Configuration.php";

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'null';

return [
    'title' => $configuration['title'],
    'username' => $username,
    'errors' => '',
    'messages' => ''
];