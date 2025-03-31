<?php

use Autoparts\App\Commands\CreateDataBaseCommand;
use Autoparts\App\Commands\SeedDataBaseCommand;
use Autoparts\App\Services\Database;
use MongoDB\Operation\Find;

require __DIR__ . '/../vendor/autoload.php';

// Разрешить все домены (можно указать конкретный домен вместо *)
header('Access-Control-Allow-Origin: *');

// Разрешить методы
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

// Разрешить заголовки
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Указываем, что ответ в формате JSON
header('Content-Type: application/json; charset=utf-8');

// Для поддержки preflight-запросов
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Отправить успешный ответ на preflight-запрос
    http_response_code(200);
    exit();
}

// Подключаем маршруты только если не в CLI
if (php_sapi_name() !== 'cli') {
    require __DIR__ . '/../routes/web.php';
} else {
    // Только при запуске из командной строки выполняем создание базы данных и заполнение
    $database = Database::getInstance();
    $db = $database->getDatabase();

    // Получаем список существующих коллекций
    $existingCollections = iterator_to_array($db->listCollections());
    $existingCollections = array_map(fn($collection) => $collection->getName(), $existingCollections);

    // Проверяем наличие коллекции 'products'
    if (!in_array('products', $existingCollections)) {
        echo "Коллекция 'products' отсутствует. Создаем коллекции и заполняем данные...\n";
        
        // Создание коллекций
        $command = new CreateDataBaseCommand();
        $command->execute();

        // Заполнение данных в 'products'
        echo "Заполнение данных в коллекции 'products'...\n";
        $seedCommand = new SeedDataBaseCommand();
        $seedCommand->execute();
    } else {
        echo "Коллекция 'products' уже существует. Действия не требуются.\n";
    }
}