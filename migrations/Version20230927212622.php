<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230927212622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shop (id INT AUTO_INCREMENT NOT NULL, shop_owner_id INT NOT NULL, shop_category_id INT NOT NULL, name VARCHAR(64) NOT NULL, description LONGTEXT NOT NULL, open_hours VARCHAR(64) NOT NULL, city VARCHAR(64) NOT NULL, address VARCHAR(64) DEFAULT NULL, UNIQUE INDEX UNIQ_AC6A4CA25E237E06 (name), INDEX IDX_AC6A4CA2A5849B6F (shop_owner_id), INDEX IDX_AC6A4CA2C0316BF2 (shop_category_id), INDEX shop_owner_shop_category_city_idx (shop_owner_id, shop_category_id, city), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, UNIQUE INDEX UNIQ_DDF4E3575E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_owner (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(64) NOT NULL, UNIQUE INDEX UNIQ_876BC2EE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop ADD CONSTRAINT FK_AC6A4CA2A5849B6F FOREIGN KEY (shop_owner_id) REFERENCES shop_owner (id)');
        $this->addSql('ALTER TABLE shop ADD CONSTRAINT FK_AC6A4CA2C0316BF2 FOREIGN KEY (shop_category_id) REFERENCES shop_category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop DROP FOREIGN KEY FK_AC6A4CA2A5849B6F');
        $this->addSql('ALTER TABLE shop DROP FOREIGN KEY FK_AC6A4CA2C0316BF2');
        $this->addSql('DROP TABLE shop');
        $this->addSql('DROP TABLE shop_category');
        $this->addSql('DROP TABLE shop_owner');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
