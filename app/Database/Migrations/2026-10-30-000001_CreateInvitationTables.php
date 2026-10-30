<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInvitationTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'unsigned' => true, 'auto_increment' => true],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 150],
            'password'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'role'       => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'client'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('users');

        $this->forge->addField([
            'id'            => ['type' => 'INTEGER', 'unsigned' => true, 'auto_increment' => true],
            'user_id'       => ['type' => 'INTEGER', 'unsigned' => true],
            'slug'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'theme'         => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'sage'],
            'groom_name'    => ['type' => 'VARCHAR', 'constraint' => 100],
            'groom_nick'    => ['type' => 'VARCHAR', 'constraint' => 50],
            'groom_parents' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'groom_photo'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'bride_name'    => ['type' => 'VARCHAR', 'constraint' => 100],
            'bride_nick'    => ['type' => 'VARCHAR', 'constraint' => 50],
            'bride_parents' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'bride_photo'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'cover_photo'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'wedding_date'  => ['type' => 'DATETIME'],
            'opening_text'  => ['type' => 'TEXT', 'null' => true],
            'quote'         => ['type' => 'TEXT', 'null' => true],
            'quote_source'  => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'music_url'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'gifts'         => ['type' => 'TEXT', 'null' => true],
            'is_published'  => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('user_id');
        $this->forge->createTable('invitations');

        $this->forge->addField([
            'id'            => ['type' => 'INTEGER', 'unsigned' => true, 'auto_increment' => true],
            'invitation_id' => ['type' => 'INTEGER', 'unsigned' => true],
            'title'         => ['type' => 'VARCHAR', 'constraint' => 100],
            'event_at'      => ['type' => 'DATETIME'],
            'end_time'      => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'venue'         => ['type' => 'VARCHAR', 'constraint' => 150],
            'address'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'map_url'       => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('invitation_id');
        $this->forge->createTable('events');

        $this->forge->addField([
            'id'            => ['type' => 'INTEGER', 'unsigned' => true, 'auto_increment' => true],
            'invitation_id' => ['type' => 'INTEGER', 'unsigned' => true],
            'file'          => ['type' => 'VARCHAR', 'constraint' => 255],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('invitation_id');
        $this->forge->createTable('gallery');

        $this->forge->addField([
            'id'            => ['type' => 'INTEGER', 'unsigned' => true, 'auto_increment' => true],
            'invitation_id' => ['type' => 'INTEGER', 'unsigned' => true],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'code'          => ['type' => 'VARCHAR', 'constraint' => 12],
            'phone'         => ['type' => 'VARCHAR', 'constraint' => 25, 'null' => true],
            'opened_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('invitation_id');
        $this->forge->addUniqueKey('code');
        $this->forge->createTable('guests');

        $this->forge->addField([
            'id'            => ['type' => 'INTEGER', 'unsigned' => true, 'auto_increment' => true],
            'invitation_id' => ['type' => 'INTEGER', 'unsigned' => true],
            'guest_id'      => ['type' => 'INTEGER', 'unsigned' => true, 'null' => true],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'attendance'    => ['type' => 'VARCHAR', 'constraint' => 10],
            'pax'           => ['type' => 'INTEGER', 'default' => 1],
            'message'       => ['type' => 'TEXT', 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('invitation_id');
        $this->forge->createTable('rsvps');
    }

    public function down()
    {
        foreach (['rsvps', 'guests', 'gallery', 'events', 'invitations', 'users'] as $t) {
            $this->forge->dropTable($t, true);
        }
    }
}
