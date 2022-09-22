<?php namespace Apk\Kontolers;

class DocsKntl {

    public function index() {
        $data['title'] = 'Docs KntL';
        $data['subtitle'] = 'Kyaaaa Weird PHP Framework';
        return view('docs', $data);
    }

}