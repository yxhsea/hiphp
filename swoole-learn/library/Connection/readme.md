
### MySQL

```
$connection = new \Library\Connection\Driver\Mysql([
        'host'     => '10.11.1.172',
        'user'     => 'root',
        'password' => '1234',
        'database' => 'guest',
        'port'     => 3307,
    ]);
$conn = $connection->getConnection();
$res = $conn->query("select * from user where id = 1");
var_dump($res)
```

### Redis

```
$connection = new \Library\Connection\Driver\Redis([
    'host' => '10.11.1.172',
    'port' => 6379
]);
$conn = $connection->getConnection();
$res = $conn->get('union_account_system:brand_account_3');
var_dump($res);
```

### PostgreSQL

```
$connection = new Library\Connection\Driver\PostgreSQL([
    'host'     => '10.11.1.172',
    'user'     => 'default',
    'password' => 'secret',
    'database' => 'default',
    'port'     => 5432,
]);
$conn = $connection->getConnection();
$result = $conn->query("select * from student");
$arr = $conn->fetchAll($result);
var_dump($arr);
```
