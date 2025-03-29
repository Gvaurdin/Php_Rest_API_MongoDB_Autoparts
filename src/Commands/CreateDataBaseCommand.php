<?php

namespace Autoparts\App\Commands;

use Autoparts\App\Services\Database;
use MongoDB\Database as MongoDatabase;

class CreateDataBaseCommand
{
    public function execute()
    {
        try {
            // Получаем подключение через централизованный класс Database
            $database = Database::getInstance()->getDatabase();

            echo "База данных 'autoparts' создана или уже существует.\n";

            // Создание коллекции products с валидацией
            $this->createProductsCollection($database);

            echo "Коллекция 'products' успешно создана или уже существует.\n";
        } catch (\Exception $e) {
            echo "Ошибка подключения к базе данных: " . $e->getMessage() . "\n";
        }
    }

    private function createProductsCollection(MongoDatabase $database)
    {
        $collectionName = 'products';

        // Проверяем, существует ли коллекция
        if ($this->collectionExists($database, $collectionName)) {
            echo "Коллекция '$collectionName' уже существует.\n";
            return;
        }

        // JSON Schema для валидации
        $schema = [
            'validator' => [
                '$jsonSchema' => [
                    'bsonType' => 'object',
                    'required' => ['name', 'price', 'stock'],
                    'properties' => [
                        'name' => [
                            'bsonType' => 'string',
                            'description' => 'Название продукта (обязательно, строка)'
                        ],
                        'price' => [
                            'bsonType' => 'double',
                            'minimum' => 0,
                            'description' => 'Цена (обязательно, число >= 0)'
                        ],
                        'stock' => [
                            'bsonType' => 'int',
                            'minimum' => 0,
                            'description' => 'Количество на складе (обязательно, целое число >= 0)'
                        ],
                        'brand' => [
                            'bsonType' => 'string',
                            'description' => 'Бренд '
                        ],
                        'category' => [
                            'bsonType' => 'string',
                            'description' => 'Категория запчасти'
                        ],
                        'image' => [
                            'bsonType' => 'string',
                            'description'=> 'Ссылка на изображение'
                        ]
                    ]
                ]
            ]
        ];

        // Создание коллекции с валидацией
        $database->createCollection($collectionName, $schema);

        echo "Коллекция '$collectionName' создана с JSON Schema валидацией.\n";
    }

    private function collectionExists(MongoDatabase $database, string $collectionName): bool
    {
        $collections = $database->listCollections();
        foreach ($collections as $collection) {
            if ($collection->getName() === $collectionName) {
                return true;
            }
        }
        return false;
    }
}

