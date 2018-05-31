<?php
namespace core;

use \PDO;

class DB extends PDO
{
    private static $instance = null;
    private $pdo;
    private $query;
    private $results;
    private $count = 0;
    private $error = false;

    private $query_string = "";
    private $bindValues = array();
    private $lastId;

    public function __construct() {

        try {
            // Put your database information
            $this->pdo = new PDO("mysql:host=127.0.0.1;dbname=benchmark","root","cable");
        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function query($sql, $parameters = array()) {
        $this->error = false;

        if ($this->query = $this->pdo->prepare($sql)) {
            $i = 1;
            foreach ($parameters as $param) {
                $this->query->bindValue($i, $param);
                $i++;
            }
            if ($this->query->execute()) {
                // You can PDO::FETCH_OBJ instad of assoc, or whatever you like
                $this->results = $this->query->fetchAll(PDO::FETCH_ASSOC);
                $this->count = $this->query->rowCount();
//                $this->lastId = $this->query->lastInsertId();
            } else {
                $this->error = true;
            }
        }
        return $this;
    }


    public function select($fields = "*") {
        $action = "";
        $this->query_string = "";
        if (is_array($fields)) {
            $action = "SELECT ";
            for ($i = 0; $i < count($fields); $i++) {
                $action .= $fields[$i];
                if ($i != count($fields) - 1)
                    $action .= ', ';
            }
        } else {
            $action = "SELECT * ";
        }
        $this->query_string .= $action;
        return $this;
    }

    public function from($table) {
        $this->query_string .= " FROM {$table} ";
        return $this;
    }

    public function where($where = array()) {
        $keys = array_keys($where);
        $action = " WHERE ";
        for ($i = 0; $i < count($keys); $i++) {
            $action .= $keys[$i] . ' = ?';
            if ($i < count($keys) - 1)
                $action .= ' AND ';
            $this->bindValues[] = $where[$keys[$i]];
        }
        $this->query_string .= $action;
        return $this;
    }

    public function execute() {
        if (!empty($this->query_string))
            $this->query($this->query_string, $this->bindValues);
        $this->bindValues = array();
    }

    public function getQueryString() {
        return $this->query_string;
    }

    public function results() {
        return $this->results;
    }
    public function first() {
        return $this->results[0];
    }
    public function last() {
        return $this->results[$this->count-1];
    }
    public function row($id) {
        return $this->results[$id];
    }
    public function error() {
        return $this->error();
    }
    public function count() {
        return $this->count;
    }
    public function lastId() {
        return $this->lastId;
    }
}
?>
