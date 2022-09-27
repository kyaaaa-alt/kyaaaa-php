<?php namespace Core\Controllers;

use Core\Models\HomeModel;

class HomeCtrl {
    public function __construct() {
        $this->HomeModel = new HomeModel();
    }

    public function index() {
        $data['title'] = 'Hello World';
        $data['subtitle'] = 'Kyaaaa-PHP Framework';
        return view('home', $data);
    }

}