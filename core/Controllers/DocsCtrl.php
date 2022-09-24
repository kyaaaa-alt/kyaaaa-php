<?php namespace Core\Controllers;

class DocsCtrl {

    public function index() {
        $data['title'] = 'Docs';
        $data['subtitle'] = 'Sample Pages';
        return view('docs', $data);
    }

}