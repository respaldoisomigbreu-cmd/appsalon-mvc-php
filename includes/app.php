<?php
use Model\ActiveRecord;
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv -> safeLoad();

require 'funciones.php';
require 'database.php';



// Conectar a la base de datos
ActiveRecord::setDB($db);