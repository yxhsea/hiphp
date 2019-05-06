<?php

require_once './bootstrap/app.php';

use Symfony\Component\Console\Application;
use \Symfony\Component\Console\Helper\HelperSet;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Symfony\Component\Console\Helper\QuestionHelper;

$application = new Application('HiPHP Command');

$helperSet = new HelperSet();

try {
    $connection = DriverManager::getConnection([
        'driver'    => 'pdo_mysql',
        'dbname'    => 'hiphp',
        'user'      => 'root',
        'password'  => '1234',
        'host'      => '10.11.1.172',
        'port'      => '3307',
        'charset'   => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ]);
    $helperSet->set(new ConnectionHelper($connection), 'db');
} catch (Exception $e) {

}

if (class_exists(QuestionHelper::class)) {
    $helperSet->set(new QuestionHelper(), 'question');
}

$application->setHelperSet($helperSet);

$application->add(new \App\Console\Commands\DemoCommand());
$application->addCommands([
    new Doctrine\Migrations\Tools\Console\Command\ExecuteCommand(),
    new Doctrine\Migrations\Tools\Console\Command\GenerateCommand(),
    new Doctrine\Migrations\Tools\Console\Command\LatestCommand(),
    new Doctrine\Migrations\Tools\Console\Command\MigrateCommand(),
    new Doctrine\Migrations\Tools\Console\Command\StatusCommand(),
    new Doctrine\Migrations\Tools\Console\Command\VersionCommand(),
    new Doctrine\Migrations\Tools\Console\Command\UpToDateCommand(),
]);

try {
    $application->run();
} catch (Exception $e) {
    exit($e);
}
