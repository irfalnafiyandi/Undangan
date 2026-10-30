<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

// Halaman undangan publik: /u/{slug}, /u/{slug}/{kode-tamu} atau /u/{slug}?to=Nama
$routes->get('u/(:segment)', 'Invitation::show/$1');
$routes->get('u/(:segment)/(:segment)', 'Invitation::show/$1/$2');
$routes->post('u/(:segment)/rsvp', 'Invitation::rsvp/$1');

$routes->group('admin', static function ($routes) {
    $routes->get('login', 'Admin\Auth::login');
    $routes->post('login', 'Admin\Auth::attempt');
    $routes->get('logout', 'Admin\Auth::logout');

    $routes->group('', ['filter' => 'auth'], static function ($routes) {
        $routes->get('/', 'Admin\Invitations::index');
        $routes->get('invitations/new', 'Admin\Invitations::new');
        $routes->post('invitations', 'Admin\Invitations::create');
        $routes->get('invitations/(:num)', 'Admin\Invitations::edit/$1');
        $routes->post('invitations/(:num)', 'Admin\Invitations::update/$1');
        $routes->post('invitations/(:num)/delete', 'Admin\Invitations::delete/$1');

        $routes->post('invitations/(:num)/events', 'Admin\Manage::addEvent/$1');
        $routes->post('invitations/(:num)/events/(:num)/delete', 'Admin\Manage::deleteEvent/$1/$2');
        $routes->post('invitations/(:num)/gallery', 'Admin\Manage::addGallery/$1');
        $routes->post('invitations/(:num)/gallery/(:num)/delete', 'Admin\Manage::deleteGallery/$1/$2');
        $routes->post('invitations/(:num)/guests', 'Admin\Manage::addGuests/$1');
        $routes->post('invitations/(:num)/guests/(:num)/delete', 'Admin\Manage::deleteGuest/$1/$2');
        $routes->post('invitations/(:num)/rsvps/(:num)/delete', 'Admin\Manage::deleteRsvp/$1/$2');

        $routes->get('users', 'Admin\Users::index');
        $routes->post('users', 'Admin\Users::create');
        $routes->post('users/(:num)/delete', 'Admin\Users::delete/$1');
    });
});
