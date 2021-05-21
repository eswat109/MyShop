<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200804011351 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, id_guest_id INT NOT NULL, status VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, order_at DATETIME NOT NULL, INDEX IDX_F5299398CA914ACD (id_guest_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_content (id INT AUTO_INCREMENT NOT NULL, id_order_id INT NOT NULL, id_product_id INT NOT NULL, amount INT NOT NULL, INDEX IDX_8BF99E2DD4481AD (id_order_id), INDEX IDX_8BF99E2E00EE68D (id_product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398CA914ACD FOREIGN KEY (id_guest_id) REFERENCES guest (id)');
        $this->addSql('ALTER TABLE order_content ADD CONSTRAINT FK_8BF99E2DD4481AD FOREIGN KEY (id_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_content ADD CONSTRAINT FK_8BF99E2E00EE68D FOREIGN KEY (id_product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_content DROP FOREIGN KEY FK_8BF99E2DD4481AD');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_content');
    }
}
