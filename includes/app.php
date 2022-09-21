<?php 
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__); //Enviorement variables route
$dotenv->safeload(); //If cant get env variables dont crashes

require 'funciones.php';
require 'database.php';

// DB conect
use Model\ActiveRecord;
ActiveRecord::setDB($db);