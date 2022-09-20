<?php namespace Apk\Kontolers;

class DocsKntl {
    public function index() {
        $data['title'] = 'Docs KntL';
        return view('docs', $data);
    }
}