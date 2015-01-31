<?php
$configuration = require "Configuration.php";

$injector = new \Auryn\Provider;

$injector->alias("Http\Response", "Http\HttpResponse");
$injector->share("Http\HttpRequest");
$injector->define("Http\HttpRequest", [
    ":get" => $_GET,
    ":post" => $_POST,
    ":cookies" => $_COOKIE,
    ":files" => $_FILES,
    ":server" => $_SERVER,
]);

$injector->alias("Http\Request", "Http\HttpRequest");
$injector->share("Http\HttpResponse");

$injector->define('Mustache_Engine', [
    ':options' => [
        'loader' => new Mustache_Loader_FilesystemLoader(dirname(__DIR__) . '/templates'),
    ],
]);

$injector->alias('AccountingSystem\Template\Engine', 'AccountingSystem\Template\MustacheEngineAdapter');

$createMySQLi = function () {
    global $configuration;
    return new \mysqli($configuration['databaseHost'], $configuration['databaseUsername'], $configuration['databasePassword'], $configuration['databaseDatabase']);
};

$injector->delegate('mysqli', $createMySQLi);
return $injector;