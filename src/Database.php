<?php
namespace App;

class Database {
    private $pdo;
    
    public function __construct($config) {
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8";
        $this->pdo = new \PDO($dsn, $config['username'], $config['password']);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
    
    public function getPdo() {
        return $this->pdo;
    }
}
?>