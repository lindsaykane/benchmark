<?php
require_once 'bootstrap.php';

use src\functions as functions;
use src\results as results;


if (isset($_POST) && !empty($_POST)) {
    session_unset();
    $results = new results();
    $functionName = '';
    foreach($_POST as $key => $value) {
        $functionName = explode('_', $key)[1];
        $results->call($functionName,$value);
    }

    include 'views/results.html';
} else {
    $functions = new functions();
    $methods = $functions->getAllMethods();
    $callableMethods = [];
    foreach ($methods as $method) {
        if (is_callable(array('src\functions', $method)) && $method != 'getAllMethods' && $method != 'call') {
            array_push($callableMethods, $method);
        }
    }

    include 'views/index.html';
}