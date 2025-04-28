<?php

class BaseModel {
    protected $db;
    
    public function __construct() {
        // Get database connection from the global scope
        global $db;
        $this->db = $db;
    }
}