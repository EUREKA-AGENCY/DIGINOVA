<?php

return [
    'api_key' => env('OBITSMS_API_KEY'),
    'base_url' => env('OBITSMS_BASE_URL', 'https://obitsms.com/api/v2/bulksms'),
    'default_sender' => env('OBITSMS_DEFAULT_SENDER', 'DIGINOVA'),
];
