<?php

use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;

umask(0);

if (file_exists('maintenance.html') && !isset($_COOKIE['access_beta'])) {
	include 'maintenance.html';
	exit;
}

//create an empty file in this directory called 'debug' to enable debugging
if (file_exists(__DIR__ . '/debug')) {
	$loader = require_once __DIR__ . '/../app/autoload.php';
	require_once __DIR__ . '/../app/AppKernel.php';
	$kernel = new AppKernel('dev', true);
} else {
	$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
	require_once __DIR__.'/../app/AppKernel.php';
	$kernel = new AppKernel('prod', false);
	$kernel->loadClassCache();
}

ExceptionHandler::register();

//set base for relative paths
chdir(__DIR__ . '/..');

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
