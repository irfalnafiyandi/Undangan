<?php

namespace App\Models;

use CodeIgniter\Model;

class RsvpModel extends Model
{
    protected $table         = 'rsvps';
    protected $allowedFields = ['invitation_id', 'guest_id', 'name', 'attendance', 'pax', 'message'];
    protected $useTimestamps = true;
    protected $updatedField  = '';
}
