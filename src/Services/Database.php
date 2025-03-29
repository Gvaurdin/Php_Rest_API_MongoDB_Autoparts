<?php

namespace Autoparts\App\Services;

use MongoDB\Client;
use MongoDB\Database as MongoDatabase; // Псевдоним для класса MongoDatabase

class Database
{
    private static ?Database $instance = null;
    private Client $client;
    private MongoDatabase $database; // Используем MongoDatabase как тип

    private function __construct()
    {
        // Подключаемся к MongoDB через URI из переменных окружения
        $this->client = new Client(getenv('MONGO_URI'));
        $this->database = $this->client->selectDatabase('autoparts');
    }

    // Реализуем паттерн Singleton для обеспечения единственного экземпляра
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Получаем базу данных
    public function getDatabase(): MongoDatabase // Возвращаем MongoDatabase
    {
        return $this->database;
    }
}
