<?php
namespace src;

use src\functions as functions;

class results
{
    protected $executionTimes = [];
    protected $itemToRemove = array(
        'sku' => 'abc123',
        'price' => 10.05,
        'quantity' => 1
    );
    protected $items = array(
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
    protected $serviceFee = 1.00;
    protected $taxPercentage = 0.06;
    protected $shipping = 2.50;

    public function call(string $functionName, int $numTimes)
    {
        $functions = new functions();
        for($i=1; $i <= $numTimes; $i++) {
            // record start time
            $time_start = microtime(true);

            switch ($functionName) {
                case 'addToCart':
                    $functions->$functionName($this->items);
                    break;
                case 'calculateTotal' :
                    $functions->$functionName($this->items, $this->serviceFee, $this->taxPercentage, $this->shipping);
                    break;
                case 'removeItemFromCart' :
                    $functions->$functionName($this->itemToRemove, $this->items);
                    break;
                default:
                    $functions->$functionName();
                    break;
            }

            // record end time
            $time_end = microtime(true);

            // calculate execution time
            $execution_time = $time_end - $time_start;

            // set session execution times if it has not yet been assigned to session
            if(!array_key_exists('executionTimes',$_SESSION)) {
                $_SESSION['executionTimes'] = $this->executionTimes;
            }

            if(!array_key_exists($functionName, $_SESSION['executionTimes'])) {
                $_SESSION['executionTimes'][$functionName]['time'] = [];
            }

            array_push($_SESSION['executionTimes'][$functionName]['time'], $execution_time);
        }
    }
}