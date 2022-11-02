<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221102200108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE exercise (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_AEDAD51C9D86650F (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workout (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ended_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_649FFB729D86650F (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workout_history (id INT AUTO_INCREMENT NOT NULL, workout_id INT NOT NULL, exercise_id INT NOT NULL, history_order INT NOT NULL, sets INT NOT NULL, reps INT NOT NULL, weight DOUBLE PRECISION DEFAULT NULL, duration DOUBLE PRECISION DEFAULT NULL, INDEX IDX_5B7C1CE3268F2D43 (workout_id), INDEX IDX_5B7C1CE35A726995 (exercise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exercise ADD CONSTRAINT FK_AEDAD51C9D86650F FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE workout ADD CONSTRAINT FK_649FFB729D86650F FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE workout_history ADD CONSTRAINT FK_5B7C1CE3268F2D43 FOREIGN KEY (workout_id) REFERENCES workout (id)');
        $this->addSql('ALTER TABLE workout_history ADD CONSTRAINT FK_5B7C1CE35A726995 FOREIGN KEY (exercise_id) REFERENCES exercise (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercise DROP FOREIGN KEY FK_AEDAD51C9D86650F');
        $this->addSql('ALTER TABLE workout DROP FOREIGN KEY FK_649FFB729D86650F');
        $this->addSql('ALTER TABLE workout_history DROP FOREIGN KEY FK_5B7C1CE3268F2D43');
        $this->addSql('ALTER TABLE workout_history DROP FOREIGN KEY FK_5B7C1CE35A726995');
        $this->addSql('DROP TABLE exercise');
        $this->addSql('DROP TABLE workout');
        $this->addSql('DROP TABLE workout_history');
    }
}
