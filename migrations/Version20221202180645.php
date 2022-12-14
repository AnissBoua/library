<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221202180645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE livre_panier (livre_id INT NOT NULL, panier_id INT NOT NULL, INDEX IDX_808ECA0737D925CB (livre_id), INDEX IDX_808ECA07F77D927C (panier_id), PRIMARY KEY(livre_id, panier_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE livre_panier ADD CONSTRAINT FK_808ECA0737D925CB FOREIGN KEY (livre_id) REFERENCES livre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livre_panier ADD CONSTRAINT FK_808ECA07F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livre_panier DROP FOREIGN KEY FK_808ECA0737D925CB');
        $this->addSql('ALTER TABLE livre_panier DROP FOREIGN KEY FK_808ECA07F77D927C');
        $this->addSql('DROP TABLE livre_panier');
    }
}
