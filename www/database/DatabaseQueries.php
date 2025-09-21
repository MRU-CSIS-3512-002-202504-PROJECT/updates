<?php

require path_to('core/DatabaseHelper.php');

class DatabaseQueries
{
    private $db_helper;

    public function __construct()
    {
        $config = require 'config.php';
        $this->db_helper = new DatabaseHelper($config);
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    public function disconnect()
    {
        $this->db_helper->close_connection();
    }


}
