<?php
spl_autoload_register(function($class){
    $class = str_replace('\\','/',str_replace('App\\', '', $class));
    $file =  __DIR__ . "/../" . $class . ".php";
    if(file_exists($file)){
        require_once($file);
    }
})
?>
