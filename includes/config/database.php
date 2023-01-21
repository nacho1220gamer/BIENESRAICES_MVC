<?php

function conectarDB() : mysqli {
    $db = new mysqli(
        $_ENV['DB_HOST'], 
        $_ENV['DB_USER'], 
        $_ENV['DB_PASS'], 
        $_ENV['DB_DB']
    );

    $db->set_charset("utf8");

    if(!$db){
        echo "Error no se pudo ejecutar";
        exit;
    } 

    return $db;

}