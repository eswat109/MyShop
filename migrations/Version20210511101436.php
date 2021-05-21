<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210511101436 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, id_guest_id INT NOT NULL, id_product_id INT NOT NULL, amount INT NOT NULL, INDEX IDX_BA388B7CA914ACD (id_guest_id), INDEX IDX_BA388B7E00EE68D (id_product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE guest (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, second_name VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, id_guest_id INT NOT NULL, status VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, order_at DATETIME NOT NULL, INDEX IDX_F5299398CA914ACD (id_guest_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_content (id INT AUTO_INCREMENT NOT NULL, id_order_id INT NOT NULL, id_product_id INT NOT NULL, amount INT NOT NULL, INDEX IDX_8BF99E2DD4481AD (id_order_id), INDEX IDX_8BF99E2E00EE68D (id_product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE whishlist (id INT AUTO_INCREMENT NOT NULL, id_guest_id INT NOT NULL, id_product_id INT NOT NULL, INDEX IDX_2E936C6DCA914ACD (id_guest_id), INDEX IDX_2E936C6DE00EE68D (id_product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7CA914ACD FOREIGN KEY (id_guest_id) REFERENCES guest (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7E00EE68D FOREIGN KEY (id_product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398CA914ACD FOREIGN KEY (id_guest_id) REFERENCES guest (id)');
        $this->addSql('ALTER TABLE order_content ADD CONSTRAINT FK_8BF99E2DD4481AD FOREIGN KEY (id_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_content ADD CONSTRAINT FK_8BF99E2E00EE68D FOREIGN KEY (id_product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE whishlist ADD CONSTRAINT FK_2E936C6DCA914ACD FOREIGN KEY (id_guest_id) REFERENCES guest (id)');
        $this->addSql('ALTER TABLE whishlist ADD CONSTRAINT FK_2E936C6DE00EE68D FOREIGN KEY (id_product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7CA914ACD');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398CA914ACD');
        $this->addSql('ALTER TABLE whishlist DROP FOREIGN KEY FK_2E936C6DCA914ACD');
        $this->addSql('ALTER TABLE order_content DROP FOREIGN KEY FK_8BF99E2DD4481AD');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7E00EE68D');
        $this->addSql('ALTER TABLE order_content DROP FOREIGN KEY FK_8BF99E2E00EE68D');
        $this->addSql('ALTER TABLE whishlist DROP FOREIGN KEY FK_2E936C6DE00EE68D');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE guest');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_content');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE whishlist');
    }
}
