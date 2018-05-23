<?php

namespace PUS\Util\Database;

use Monolog\Logger;
use Medoo\Medoo;

class DatabaseHelper
{
    private $db;
    private $tableNameRedirects = "redirects";
    private $tableNameHits = "hits";
    private $logger;

    public function __construct(Logger $logger, string $adaptor, string $host, string $port, string $user, string $pass, string $base) {
        $this->db = new Medoo([
            'database_type' => $adaptor,
            'server' => $host,
            'port' => $port,
            'username' => $user,
            'password' => $pass,
            'database_name' => $base
        ]);

        $this->logger = $logger;
    }

    public function getRedirect(string $name) {
        $result = $this->db->select($this->tableNameRedirects, [
            "url"
        ],[
            "name[=]" => $name
        ]);

        return is_array($result) && is_array($result[0]) && count($result[0]) ? $result[0]['url'] : "";
    }

    public function saveRedirect(string $name, string $url) {
        $result = $this->db->insert($this->tableNameRedirects, [
            "name" => $name,
            "url" => $url
        ]);

        return $result->rowCount() == 1;
    }

    public function getNumOfRedirects() {
        return $this->db->count($this->tableNameRedirects);
    }

    public function saveHit(string $name, string $ip) {
        $this->db->insert($this->tableNameHits, [
            "name" => $name,
            "ip" => $ip
        ]);
        $this->db->update($this->tableNameRedirects, [
            "hits[+]" => 1
        ], [
            "name[=]" => $name
        ]);
    }
}