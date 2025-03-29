<?php

namespace Autoparts\App\Models;

use Autoparts\App\Services\Database;
use MongoDB\Collection;
use MongoDB\BSON\ObjectId;

class Product
{
    private Collection $collection;

    public function __construct()
    {
        $database = Database::getInstance()->getDatabase();

        $this->collection = $database->selectCollection("products");
    }

    public function getCount()
    {
        return $this->collection->count();
    }

    public function getAll()
    {
        return iterator_to_array($this->collection->find());
    }

    public function getById($id)
    {
        return $this->collection->findOne(['_id' => new ObjectId ($id)]);
    }

    public function create($data)
    {
        if (isset($data['price'])) {
            $data['price'] = (float) $data['price']; 
        }
        if (isset($data['stock'])) {
            $data['stock'] = (int) $data['stock'];
        }
    
        return $this->collection->insertOne($data);
    }

    public function update($id, $data)
    {
        $filter = ['_id' => new ObjectId($id)];
    
        if (isset($data['price'])) {
            $data['price'] = (float) $data['price']; 
        }
        if (isset($data['stock'])) {
            $data['stock'] = (int) $data['stock'];
        }
    
        $update = ['$set' => $data];
    
        return $this->collection->updateOne($filter, $update);
    }

    public function delete($id)
    {
        return $this->collection->deleteOne(['_id' => new ObjectId($id)]);
    }
}