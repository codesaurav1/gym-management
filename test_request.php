<?php
// Simulate HTTP request to router
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/api/hello';

ob_start();
require __DIR__ . '/public/index.php';
$output = ob_get_clean();

echo $output . PHP_EOL;
