<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250709120844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE facture ADD client_id INT NOT NULL, DROP client, CHANGE statut statut VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641019EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_FE86641019EB6921 ON facture (client_id)');
        $this->addSql('ALTER TABLE ligne_devis ADD devis_id INT DEFAULT NULL, DROP devis');
        $this->addSql('ALTER TABLE ligne_devis ADD CONSTRAINT FK_888B2F1B41DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
        $this->addSql('CREATE INDEX IDX_888B2F1B41DEFADA ON ligne_devis (devis_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641019EB6921');
        $this->addSql('DROP TABLE client');
        $this->addSql('ALTER TABLE ligne_devis DROP FOREIGN KEY FK_888B2F1B41DEFADA');
        $this->addSql('DROP INDEX IDX_888B2F1B41DEFADA ON ligne_devis');
        $this->addSql('ALTER TABLE ligne_devis ADD devis VARCHAR(255) DEFAULT NULL, DROP devis_id');
        $this->addSql('DROP INDEX IDX_FE86641019EB6921 ON facture');
        $this->addSql('ALTER TABLE facture ADD client VARCHAR(255) NOT NULL, DROP client_id, CHANGE statut statut VARCHAR(20) NOT NULL');
    }
}
