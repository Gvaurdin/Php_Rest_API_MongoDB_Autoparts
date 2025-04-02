<?php

namespace Autoparts\App\Services;

use MongoDB\Client;
use MongoDB\Database as MongoDatabase; // псевдоним для класса MongoDatabase

class Database
{
    private static ?Database $instance = null;
    private Client $client;
    private MongoDatabase $database;

    private function __construct()
    {
        // подключаемся к MongoDB через строку подключения из переменной окружения
        $this->client = new Client(getenv('MONGO_URI'));
        $this->database = $this->client->selectDatabase('autoparts');
    }

    // реализуем паттерн singleton для обеспечения единственного экземпляра подключения к базе
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // получаем бд
    public function getDatabase(): MongoDatabase 
    {
        return $this->database;
    }
}
