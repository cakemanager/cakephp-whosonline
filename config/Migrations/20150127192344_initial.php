<?php

use Phinx\Migration\AbstractMigration;

class Initial extends AbstractMigration
{

    /**
     * Migrate Up.
     *
     * @return void
     */
    public function up() {

        $table = $this->table('whosonline_usermetas', [
            'id'          => false,
            'primary_key' => 'id',
        ]);
        $table
                ->addColumn('id', 'integer', [
                    'limit' => '11',
                ])
                ->addColumn('user_id', 'integer', [
                    'limit' => '11',
                ])
                ->addColumn('last_seen', 'datetime', [
                ])
                ->addColumn('last_login', 'datetime', [
                ])
                ->addColumn('passed_logins', 'integer', [
                    'limit' => '11',
                ])
                ->addColumn('failed_logins', 'integer', [
                    'limit' => '11',
                ])
                ->addColumn('password_requests', 'integer', [
                    'limit' => '11',
                ])
                ->create();
    }

    /**
     * Migrate Down.
     *
     * @return void
     */
    public function down() {

        $this->dropTable('whosonline_usermetas');
    }

}
