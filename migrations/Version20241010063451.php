<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241010063451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE asignatura (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) DEFAULT NULL, descripcion VARCHAR(255) DEFAULT NULL, horas INT DEFAULT NULL, profesor VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE asignatura_curso (asignatura_id INT NOT NULL, curso_id INT NOT NULL, INDEX IDX_1A62C200C5C70C5B (asignatura_id), INDEX IDX_1A62C20087CB4A1F (curso_id), PRIMARY KEY(asignatura_id, curso_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE curso (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) DEFAULT NULL, descripcion VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE asignatura_curso ADD CONSTRAINT FK_1A62C200C5C70C5B FOREIGN KEY (asignatura_id) REFERENCES asignatura (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE asignatura_curso ADD CONSTRAINT FK_1A62C20087CB4A1F FOREIGN KEY (curso_id) REFERENCES curso (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE asignatura_curso DROP FOREIGN KEY FK_1A62C200C5C70C5B');
        $this->addSql('ALTER TABLE asignatura_curso DROP FOREIGN KEY FK_1A62C20087CB4A1F');
        $this->addSql('DROP TABLE asignatura');
        $this->addSql('DROP TABLE asignatura_curso');
        $this->addSql('DROP TABLE curso');
    }
}
