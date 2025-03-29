<?php

namespace Autoparts\App\Services;

class Service
{
    public static function jsonResponse($success, $message, $data = [])
    {
        echo json_encode([
            "success"=> $success,
            "message"=> $message,
            "data"=> $data
        ]);
    } 
}