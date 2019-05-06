<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190505103029 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        if (!$schema->hasTable("test")) {
            $table = $schema->createTable("test");
            $table->addColumn("id", "integer");
            $table->addColumn("name", "string");
        }
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        if ($schema->hasTable("test")) {
            $schema->dropTable("test");
        }
    }
}
