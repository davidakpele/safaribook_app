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
	echo "<pre>";
	print_r($data);
	echo "</pre>";
	die();
}