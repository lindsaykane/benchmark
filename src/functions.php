<?php
namespace src;

use core\DB as DB;

class functions {
    protected $total = 0;
    protected $cart = [];

    public function getAllMethods()
    {
        $methods = get_class_methods($this);

        return $methods;
    }

    public function getProducts()
    {
        $db = new DB();

        $db->query("SELECT * FROM products ORDER BY name");

        return $db->results();
    }

    public function addToCart(array $items)
    {
        array_push($this->cart,$items);

        return $this->cart;
    }

    public function getCart()
    {
        return $this->cart;
    }

    protected function calculateTax(float $dollarAmount, float $taxPercentage)
    {
        return ($dollarAmount * $taxPercentage);
    }

    protected function calculateSubTotal(array $items)
    {
        foreach ($items as $key => $item) {
            $this->total += $item['price'] * $item['quantity'];
        }
    }

    public function calculateTotal(array $items, float $serviceFee, float $taxPercentage, float $shipping)
    {
        // sum the total for all items by multiplying each item's price by its quantity
        $this->calculateSubTotal($items);

        // calculate the tax to add to the subtotal
        $this->total += $this->calculateTax($this->total, $taxPercentage);

        // add service fee and shipping to subtotal with tax
        $this->total += $serviceFee + $shipping;

        return number_format($this->total,2,'.','');
    }

    public function removeItemFromCart(array $item, array $cart) {
        $keyToMatch = array_search($item['sku'], array_column($cart[0], 'sku'));

        if($keyToMatch) {
            if($item['quantity'] == $cart[$keyToMatch]['quantity']) {
                array_splice($cart[0], $keyToMatch, 1);
            } else {
                $cart[0][$keyToMatch]['quantity'] = $item['quantity'];
            }
        }
    }
}