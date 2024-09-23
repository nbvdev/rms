<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240919204527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ingredient (
          id SERIAL NOT NULL,
          name VARCHAR(255) NOT NULL,
          title VARCHAR(255) NOT NULL,
          unit_type VARCHAR(255) NOT NULL,
          unit_calories INT NOT NULL,
          is_vegetarian BOOLEAN NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX u_ingredient_name ON ingredient (name)');
        $this->addSql('CREATE TABLE recipe (
          id SERIAL NOT NULL,
          name VARCHAR(255) NOT NULL,
          title VARCHAR(255) NOT NULL,
          description VARCHAR(255) NOT NULL,
          good_for_person_count INT NOT NULL,
          cooking_duration INT NOT NULL,
          created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          owner_user_id INT NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX u_receipt_name ON recipe (name)');
        $this->addSql('CREATE TABLE recipe_ingredient (
          id SERIAL NOT NULL,
          recipe_id INT NOT NULL,
          ingredient_id INT NOT NULL,
          unit_quantity INT NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_22D1FE1359D8A214 ON recipe_ingredient (recipe_id)');
        $this->addSql('CREATE INDEX IDX_22D1FE13933FE08C ON recipe_ingredient (ingredient_id)');
        $this->addSql('ALTER TABLE
          recipe_ingredient
        ADD
          CONSTRAINT FK_22D1FE1359D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          recipe_ingredient
        ADD
          CONSTRAINT FK_22D1FE13933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX u_receipt_ingredient ON recipe_ingredient (recipe_id, ingredient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE recipe_ingredient DROP CONSTRAINT FK_22D1FE1359D8A214');
        $this->addSql('ALTER TABLE recipe_ingredient DROP CONSTRAINT FK_22D1FE13933FE08C');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_ingredient');
    }
}
