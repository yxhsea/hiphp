### Redis

```
$connection = new \Library\Connection\Driver\Redis([
    'host' => '10.11.1.172',
    'port' => 6379
]);
$conn = new Library\Cache\Adapter\Redis($connection);
$conn->set("test", "value");
```
