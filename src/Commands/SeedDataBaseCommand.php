<?php

namespace Autoparts\App\Commands;

use Autoparts\App\Models\Product;
use Autoparts\App\Models\Order;
use Autoparts\App\Models\User;

class SeedDataBaseCommand
{
    public function execute()
    {
        $this->seedProducts();
        echo "Database seeding completed successfully!\n";
    }

    private function seedProducts()
    {
        $productModel = new Product();

        // Проверяем, есть ли уже продукты
        if ($productModel->getCount() > 0) {
            echo "Products already seeded.\n";
            return;
        }

        $products = [
            ["name" => "Brake Pads", "description" => "High-quality brake pads", "price" => 29.99, "category" => "Brakes", "stock" => 50],
            ["name" => "Oil Filter", "description" => "Durable oil filter", "price" => 9.99, "brand" => "Mann", "stock" => 100],
            ["name" => "Battery", "description" => "12V car battery", "price" => 99.99, "brand" => "Varta", "category" => "Electrical", "stock" => 20],
            ["name" => "Air Filter", "price" => 19.99, "brand" => "K&N", "category" => "Filters", "stock" => 75],
            ["name" => "Spark Plug", "description" => "Iridium spark plug", "price" => 14.99, "stock" => 60],
        ];

        foreach ($products as $product) {
            $productModel->create($product);
        }
        echo "Products seeded successfully.\n";
    }

}

