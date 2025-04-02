<?php

use Autoparts\App\Commands\CreateDataBaseCommand;
use Autoparts\App\Commands\SeedDataBaseCommand;
use Autoparts\App\Services\Database;
use MongoDB\Operation\Find;

require __DIR__ . '/../vendor/autoload.php';

header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

header('Access-Control-Allow-Headers: Content-Type, Authorization');

header('Content-Type: application/json; charset=utf-8');

// для поддержки preflight-запросов
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // отправляем успешный ответ на preflight-запрос
    http_response_code(200);
    exit();
}

// подключаем маршруты только если не в CLI
if (php_sapi_name() !== 'cli') {
    require __DIR__ . '/../routes/web.php';
} else {
    // при запуске из командной строки выполняем создание базы данных и заполнение
    $database = Database::getInstance();
    $db = $database->getDatabase();

    $existingCollections = iterator_to_array($db->listCollections());
    $existingCollections = array_map(fn($collection) => $collection->getName(), $existingCollections);

    // проверяем наличие коллекции products
    if (!in_array('products', $existingCollections)) {
        echo "Коллекция 'products' отсутствует. Создаем коллекции и заполняем данные...\n";
        
        // создаем коллекции
        $command = new CreateDataBaseCommand();
        $command->execute();

        // заполнение данные в products
        echo "Заполнение данных в коллекции 'products'...\n";
        $seedCommand = new SeedDataBaseCommand();
        $seedCommand->execute();
    } else {
        echo "Коллекция 'products' уже существует. Действия не требуются.\n";
    }
}