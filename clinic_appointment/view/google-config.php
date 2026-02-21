<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../vendor/vendor/autoload.php'; // ✅ Goes two levels up from /view to project root


$client = new Google_Client();
$clientID = getenv('GOOGLE_CLIENT_ID');
$clientSecret = getenv('GOOGLE_CLIENT_SECRET');
$client->setRedirectUri('https://fourtunersjruclinic.infinityfreeapp.com/google-callback.php');
$client->addScope("email");
$client->addScope("profile");