<?php

return [
    'paths' => ['api/*'],
    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'], // Các phương thức HTTP được phép
    'allowed_origins' => ['http://fe.123work.abc'], // Đảm bảo domain frontend của bạn được phép
    'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With'], // Các header cho phép
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, // Cho phép cookies hoặc credentials
];
