<?php

require_once 'bootstrap.php';

use src\functions as functions;

//$functions = new functions();
//
//$items = array(
//    array(
//        'sku' => 'abc123',
//        'price' => 10.05,
//        'quantity' => 1
//    ),
//    array(
//        'sku' => 'cde456',
//        'price' => 13.75,
//        'quantity' => 2
//    )
//);
//
//$cart = new functions();
//
//$productsInCart = $cart->addToCart($items);
//echo '<hr />';
//var_dump($items);

//$products = new functions();
//
//$myProducts = $products->getProducts();
//var_dump(base64_encode(serialize($myProducts)));


//$test = new functions();
//$callGetProducts = $test->call('getProducts',[],10);
//var_dump($_SESSION['executionTimes']);




$itemToRemove = array(
    'sku' => 'abc123',
    'price' => 10.05,
    'quantity' => 1
);

$cartBeforeRemoval = array(
    array(
        'sku' => 'abc123',
        'price' => 10.05,
        'quantity' => 1
    ),
    array(
        'sku' => 'cde456',
        'price' => 13.75,
        'quantity' => 2
    )
);

$cartAfterRemoval = array(
    array(
        'sku' => 'cde456',
        'price' => 13.75,
        'quantity' => 2
    )
);
$cart = new functions();
$removeFromCart = $cart->removeItemFromCart($itemToRemove, $cartBeforeRemoval);

var_dump($cartAfterRemoval);
echo '<hr />';
var_dump($removeFromCart);