<?php
namespace src;

use src\functions as functions;

class results
{
    const DOWNLOADFILEPATH = __DIR__ . '/../../tmp';
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

    protected function getMean(array $times)
    {
        $count = count($times);
        $sum = array_sum($times);
        $total = $sum / $count;

        return $total;
    }

    protected function getMedian(array $times)
    {
        rsort($times);
        $middle = round(count($times), 2);
        $total = $times[$middle-1];

        return $total;
    }

    public function compare()
    {
        foreach($_SESSION['executionTimes'] as $method => $timesArray) {
            $_SESSION['executionTimes'][$method]['comparators'] = [];
            $_SESSION['executionTimes'][$method]['comparators']['min'] = min($timesArray['time']);
            $_SESSION['executionTimes'][$method]['comparators']['max'] = max($timesArray['time']);
            $_SESSION['executionTimes'][$method]['comparators']['mean'] = $this->getMean($timesArray['time']);
            $_SESSION['executionTimes'][$method]['comparators']['median'] = $this->getMedian($timesArray['time']);
        }
    }

    protected function exportCSV(string $fileName)
    {
        // write array to csv file
        $output = fopen('tmp/' . $fileName,'w+');
        header("Content-Type:application/csv");
        header("Content-Disposition:attachment;filename=$fileName");

        foreach($_SESSION['executionTimes'] as $method => $timesArray) {
            fputcsv($output, array('Execution Times for ' . $method));
            fputcsv($output, $timesArray['time']);
            fputcsv($output, array('Min','Max','Mean','Median'));
            fputcsv($output, $_SESSION['executionTimes'][$method]['comparators']);

        }
        fclose($output);
    }

    public function exportFile()
    {
        $basePathForDownload = 'tmp/';

        $fileName = 'results.csv';
        $this->exportCSV($fileName);

        $result['pathForDownload'] = $basePathForDownload . $fileName;

        header('Content-Type: application/json');
        die(json_encode($result));
    }
}