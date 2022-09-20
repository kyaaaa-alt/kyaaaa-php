<?php namespace Apk\Conf;

class Response {
    protected $view;

    public function __construct($view, $data = []) {
        $this->view = $view;
        $this->data = $data;
    }

    public function getView() {
        return $this->view;
    }

    public function getData() {
        return $this->data;
    }

    public function send() {
        $view = $this->getView();
        $data = $this->getData();
//        $content = file_get_contents(viewPath($view, $data));
        ob_start();
        include(viewPath($view, $data));
        $content = ob_get_contents();
        ob_end_clean();
        require_once viewPath('layout');

    }
}