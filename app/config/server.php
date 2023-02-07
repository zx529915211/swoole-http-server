<?php
return [
    'http1' => [
        'ip' => '0.0.0.0',
        'port' => '8000',
        'callbacks' => [
            'request' => [\core\server\HttpServer::class, 'onRequest'],
        ]
    ],
    'http2' => [
        'ip' => '0.0.0.0',
        'port' => '8001',
        'callbacks' => [
            'request' => [\core\server\HttpServer::class, 'onRequest'],
        ]
    ],

];