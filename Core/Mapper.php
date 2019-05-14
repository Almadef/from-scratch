<?php

namespace Application\Core;

use Application\Settings\DB as SettingsDB;

/**
 * Class Mapper
 */
abstract class Mapper
{
    /**
     * @var \PDO
     */
    protected $db;

    /**
     * Model constructor.
     */
    public function __construct()
    {
        $settingsDB = new SettingsDB();
        $db_host = $settingsDB->db_host;
        $db_user = $settingsDB->db_user;
        $db_pass = $settingsDB->db_pass;
        $db_name = $settingsDB->db_name;
        $this->db = new \PDO("mysql:host=" . $db_host . ";dbname=$db_name", $db_user, $db_pass);
    }
}