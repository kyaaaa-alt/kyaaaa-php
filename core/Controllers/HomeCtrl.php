<?php namespace Core\Controllers;

use Core\Models\HomeModel;

class HomeCtrl {
    public function __construct() {
        $this->HomeModel = new HomeModel();
    }

    public function index() {
        $get_user = $this->HomeModel->get_users_active();
        dd($get_user); // use d() or dd() for dump!
        $data['title'] = 'Hello World!';
        $data['subtitle'] = 'Kyaaaa-PHP Framework';
        return view('home', $data);
    }
}