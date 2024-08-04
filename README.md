# Sttanp API SDK

This package is a PHP wrapper for the [stannp.com API](https://www.stannp.com/us/direct-mail-api/guide).

### Requirements

This project works with PHP 8.2+.

You will also need a stannp.com API key.

## Installation

Install with composer:

```
composer require craymend/stannp-php-sdk
```

## Examples

Create an instance of Request. Use testEndpoint() to call `/v1/users/me`

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use Craymend\Staanp\Request;

echo "Query test endpoint\n";

$apiKey = 'your-api-key';
$request = new Request($apiKey);

$response = $request->testEndpoint();
if($response->getSuccess()){
    $data = $response->getData();
    echo "Endpoint Test: SUCCESS\n";
    echo 'Data: ' . json_encode($data) . "\n";
}else{
    $errors = $response->getErrors();
    echo "Endpoint Test: FAIL\n";
    echo 'Errors: ' . json_encode($errors) . "\n";
}
```


More Examples:

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use Craymend\Staanp\Request;

$apiKey = 'your-api-key';
$request = new Request($apiKey);

// Test getting recipient
echo 'Test getting recipient: ' . $uri . "\n";

$recipientId = '111111111111';
$uri = '/v1/recipients/get/' . $recipientId;
$response = $request->get($uri, []);
if($response->getSuccess()){
    $data = $response->getData();
    echo 'Data: ' . json_encode($data) . "\n";
}else{
    $errors = $response->getErrors();
    echo 'Error: ' . json_encode($errors) . "\n";
}

// Test querying an error
echo "Test error query\n";

$uri = '/v1/not-an-endpoint';
$response = $request->get($uri, []);
if($response->getSuccess()){
    $data = $response->getData();
    echo 'Data: ' . json_encode($data) . "\n";
}else{
    $errors = $response->getErrors();
    echo 'Error: ' . json_encode($errors) . "\n";
}
```

## License

MIT