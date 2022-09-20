<?php namespace Apk\Kontolers;

class HomeKntl {
    public function index() {
        $data['title'] = 'Hello World';
        $data['subtitle'] = 'Kyaaaa Weird PHP Framework';
        return view('home', $data);
    }
}