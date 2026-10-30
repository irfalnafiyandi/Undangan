<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRsvpAndGuestFeatures extends Migration
{
    public function up()
    {
        $this->forge->addColumn('invitations', [
            'live_url'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'gift_address' => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addColumn('guests', [
            'quota'         => ['type' => 'INTEGER', 'default' => 2],
            'checked_in_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addColumn('rsvps', [
            'event_ids' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('invitations', ['live_url', 'gift_address']);
        $this->forge->dropColumn('guests', ['quota', 'checked_in_at']);
        $this->forge->dropColumn('rsvps', 'event_ids');
    }
}
