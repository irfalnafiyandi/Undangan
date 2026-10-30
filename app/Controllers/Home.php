<?php

namespace App\Controllers;

use App\Models\InvitationModel;

class Home extends BaseController
{
    public function index(): string
    {
        $demo = (new InvitationModel())->where('is_published', 1)->orderBy('id', 'ASC')->first();

        return view('home', ['demo' => $demo]);
    }
}
