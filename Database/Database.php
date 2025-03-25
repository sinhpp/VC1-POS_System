<?php

namespace Database;

use PDO;
use PDOException;

class Database
{
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'pos_system';
    
    private $dbh;
    private $stmt;
    private $error;
    
    public function __construct()
    {
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        
        // Create PDO instance
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }
    
    /**
     * Prepare statement with query
     * 
     * @param string $sql
     * @return void
     */
    public function query($sql, $params = [])
    {
        $this->stmt = $this->dbh->prepare($sql);
        
        if (!empty($params)) {
            foreach ($params as $param => $value) {
                $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
                $this->stmt->bindValue($param, $value, $type);
            }
        }
        
        $this->stmt->execute();
    }
    
    /**
     * Get single record as array
     * 
     * @return array|false
     */
    public function single($sql = null, $params = [])
    {
        if ($sql) {
            $this->query($sql, $params);
        }
        
        return $this->stmt->fetch();
    }
    
    /**
     * Get result set as array of arrays
     * 
     * @return array
     */
    public function resultSet($sql = null, $params = [])
    {
        if ($sql) {
            $this->query($sql, $params);
        }
        
        return $this->stmt->fetchAll();
    }

    // Prevent object cloning
    private function __clone() {}

    // Prevent object unserialization
    public function __wakeup() {}
}

