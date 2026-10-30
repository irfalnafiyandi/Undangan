<?php

namespace App\Models;

use CodeIgniter\Model;

class GuestModel extends Model
{
    protected $table         = 'guests';
    protected $allowedFields = ['invitation_id', 'name', 'code', 'phone', 'quota', 'opened_at', 'checked_in_at'];

}
