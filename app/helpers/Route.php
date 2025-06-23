<?php

function redirect($url=''){
	
   if (!defined('ROOT')) {
        define('ROOT', '/'); 
    }

    if (!empty($url)) {
        header('Location: ' . ROOT . $url);
        exit(); 
    }
}

function dnd($data){
    header("Content-Type: application/json");
    echo json_encode($data, JSON_PRETTY_PRINT);
	die();
}