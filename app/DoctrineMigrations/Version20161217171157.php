<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

class Version20161217171157 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, rate NUMERIC(10, 2) NOT NULL, INDEX IDX_64C19C1A76ED395 (user_id), UNIQUE INDEX UNIQ_64C19C15E237E06A76ED395 (name, user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql("ALTER TABLE task ADD category_id CHAR(36) NOT NULL COMMENT '(DC2Type:uuid)'");

        $tasks = $this->connection->fetchAll('SELECT DISTINCT user_id FROM task');

        if (!empty($tasks)) {
            $unsortedCategory = [
                'name' => 'Неотсортированное',
                'description' => 'Автоматически созданная во время миграции категория.',
                'rate' => 20.00,
            ];

            foreach ($tasks as $task) {
                $categoryId = Uuid::uuid4();

                $this->addSql('INSERT INTO category (id, name, description, rate, user_id) VALUES (?, ?, ?, ?, ?)', [
                    $categoryId,
                    $unsortedCategory['name'],
                    $unsortedCategory['description'],
                    $unsortedCategory['rate'],
                    $task['user_id'],
                ]);

                $this->addSql('UPDATE task SET category_id = ? WHERE user_id = ?', [
                    $categoryId,
                    $task['user_id'],
                ]);
            }
        }

        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25A76ED395');
        $this->addSql('ALTER TABLE task DROP user_id');
        $this->addSql('ALTER TABLE task CHANGE rate rate NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2512469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_527EDB2512469DE2 ON task (category_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("ALTER TABLE task ADD user_id CHAR(36) NOT NULL COLLATE utf8_unicode_ci COMMENT '(DC2Type:uuid)'");
        $this->addSql('UPDATE task t LEFT JOIN category c ON c.id = t.category_id SET t.user_id = c.user_id, t.rate = COALESCE(t.rate, c.rate)');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB2512469DE2');
        $this->addSql('ALTER TABLE task DROP category_id');
        $this->addSql('DROP TABLE category');
        $this->addSql('ALTER TABLE task CHANGE rate rate NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_527EDB25A76ED395 ON task (user_id)');
    }
}
