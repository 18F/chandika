<?php
setlocale(LC_MONETARY, 'en_US');
spl_autoload_register(function ($class) {
    $path = str_replace("\\", "/", $class);
    include 'classes/' . $path . '.php';
});