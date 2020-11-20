<?php

class MySQL {

    protected $mysql_link;
    protected $server = 'localhost';
    protected $user = 'root';
    protected $password = 'q,~b%r!fBYs_JR3J';
    protected $selected_db = 'unipd_inventory';
    
    public function __construct() {
       $this->link = mysqli_connect($this->server,$this->user,$this->password,$this->selected_db);
    }

    public function link() {
        return $this->link;
    }

    public function close() {
        return mysqli_close($this->link);
    }

}