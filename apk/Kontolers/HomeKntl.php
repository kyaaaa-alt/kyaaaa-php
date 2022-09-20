<?php namespace Apk\Kontolers;

class HomeKntl {
    public function index() {
        $data['title'] = 'Hello World';
        return view('home', $data);
    }
}