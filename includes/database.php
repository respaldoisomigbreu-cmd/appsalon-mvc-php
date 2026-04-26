<?php

$db = mysqli_connect(
                        $_ENV['DB_HOST'], 
                        $_ENV['DB_USER'], 
                        $_ENV['DB_PASS'], 
                        $_ENV['DB_NAME'],
                    );    
    
$db->set_charset('utf8mb4 COLLATE utf8mb4_unicode_ci');

    if(!$db){
        echo "Error: no se pudo conectar a MySQL.";
        echo "Error de depuración: " . mysqli_connect_error();
        echo "Error de depuración: " . mysqli_connect_errno();
        exit;
    }
