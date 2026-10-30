<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table         = 'events';
    protected $allowedFields = ['invitation_id', 'title', 'event_at', 'end_time', 'venue', 'address', 'map_url'];

}
