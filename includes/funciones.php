<?php

function debuguear($variable): string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

//Funcion para sanitizar el HTML
function s($html): string { 
    $s = htmlspecialchars($html);
    return $s;
}

    //funcion que revisa si el servicio es el ultimo, para no mostrar un guion despues
function esUltimo(string $actual, string $proximo): bool {
    if($actual !== $proximo){
        return true;
    }
    return false;
}
//funcion que revisa si el usuario esta autenticado, sino lo esta redirecciona al login

function isAuth() : void {

    if(!isset($_SESSION['login'])){
        header('Location: /');
    }
}

function isAdmin() : void {
    if(!isset($_SESSION['admin'])){
        header('Location: /');
    }
}