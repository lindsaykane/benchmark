<?php
namespace tests;

require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use PHPUnit\Framework\TestCase;
use src\functions as functions;

define('BASE64_SERIALIZED_PRODUCTS','YTozOntpOjA7YTo0OntzOjI6ImlkIjtzOjE6IjEiO3M6NDoibmFtZSI7czo1OiJBZHVsdCI7czozOiJza3UiO3M6NjoiQUJDMTIzIjtzOjU6InByaWNlIjtzOjI6IjE1Ijt9aToxO2E6NDp7czoyOiJpZCI7czoxOiIyIjtzOjQ6Im5hbWUiO3M6NToiQ2hpbGQiO3M6Mzoic2t1IjtzOjY6IkRFRjQ1NiI7czo1OiJwcmljZSI7czoyOiIxMCI7fWk6MjthOjQ6e3M6MjoiaWQiO3M6MToiMyI7czo0OiJuYW1lIjtzOjY6IlNlbmlvciI7czozOiJza3UiO3M6NjoiR0hJNzg5IjtzOjU6InByaWNlIjtzOjQ6IjEyLjUiO319');

class CalculateTotalTest extends TestCase
{
    public function testGetProducts()
    {
        $products = new functions();

        $output = base64_encode(serialize($products->getProducts()));
        $this->assertEquals(
            BASE64_SERIALIZED_PRODUCTS,
            $output,
            'The products should match'
        );
    }

    public function testAddToCart()
    {
        $items = array(
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

        $cart = new functions();

        $output = $cart->addToCart($items);
        $this->assertEquals(
            $items,
            $output[0],
            'The items in the cart should match'
        );
    }

    public function testGetCart()
    {
        $items = array(
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

        $cart = new functions();

        $input = $cart->addToCart($items);
        $output = $cart->getCart();
        $this->assertEquals(
            $input,
            $output,
            'The items in the cart should match'
        );
    }

    public function testCalculateTotal()
    {
        $items = array(
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

        $serviceFee = 1.00;
        $taxPercentage = 0.06;
        $shipping = 2.50;

        $calcTotal = new functions();

        $output = $calcTotal->calculateTotal($items, $serviceFee, $taxPercentage, $shipping);
        $this->assertEquals(
            43.30,
            $output,
            'The total should add up to $43.30'
        );
    }
}