<?php

namespace Autoparts\App\Controllers;

use Autoparts\App\Models\Product;
use Autoparts\App\Services\Service;
use MongoDb\BSON\ObjectID;

class ProductController
{
    private Product $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    public function index()
    {
        $products = $this->productModel->getAll();
        Service::jsonResponse(true, "Список продуктов", $products);
    }

    public function show($id)
    {
        // преобразуем строку $id в mongoDB objectId
        $objectId = new ObjectId($id);
    
        $product = $this->productModel->getById($objectId);
    
        if (!$product) {
            Service::jsonResponse(false, "Продукт не найден", []);
            return;
        }
    
        Service::jsonResponse(true, "Детали продукта", $product);
    }

    public function store()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if(!$data || !isset($data['name'], $data['price']))
        {
            Service::jsonResponse(false,'Некорректные данные', []);
            return;
        }

        $result = $this->productModel->create($data);
        Service::jsonResponse(true, "Продукт создан", ['inserted_id' => $result->getInsertedId()]);
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
    
        if (!$data) {
            Service::jsonResponse(false, 'Нет данных для обновления', []);
            return;
        }
    
        $objectId = new ObjectId($id);
    
        $result = $this->productModel->update($objectId, $data);
    
        Service::jsonResponse(true, 'Продукт обновлен', ["modified_count" => $result->getModifiedCount()]);
    }

    public function delete($id)
    {
        $objectId = new ObjectId($id);
    
        $result = $this->productModel->delete($objectId);
    
        if ($result->getDeletedCount() === 0) {
            Service::jsonResponse(false, "Продукт не найден или уже был удален", []);
            return;
        }
    
        Service::jsonResponse(true, "Продукт удален", ["deleted_count" => $result->getDeletedCount()]);
    }
}