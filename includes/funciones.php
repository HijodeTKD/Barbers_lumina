<?php

//Debug data
function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Sanitize HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

//Is last id element on foreach?
function esUltimo(string $actual, string $siguiente): bool{
    if($actual !== $siguiente){
        return true;
    }
    return false;
}

//Is user auth?
function estaAutenticado(): void{
    if(!isset($_SESSION['login'])){
        header('Location: /');
    }
}

//User is a Admin?
function isAdmin(): void{
    if(!isset($_SESSION['admin'])){
        header('Location:/');
    }
}