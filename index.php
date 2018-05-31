<?php
require_once 'bootstrap.php';

use src\functions as functions;
use src\results as results;


if (isset($_POST) && !empty($_POST)) {
    session_unset();
    $results = new results();
    // loop over each form value, and call the corresponding method the number of times specified
    foreach ($_POST as $key => $value) {
        $functionName = explode('_', $key)[1];
        $results->call($functionName, $value);
    }
    // get the min, max, mean, and median for the results of each method
    $results->compare();
    include 'views/results.html';
} elseif (isset($_GET) && isset($_GET['action']) && $_GET['action'] == 'exportFile') {
    $results = new results();
    $results->exportFile();
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