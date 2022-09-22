<?php namespace Apk\Conf\System;

class GetView {
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
        $data = extract($this->getData());
        ob_start();
        include(viewPath($view, $data));
        $content = ob_get_contents();
        ob_end_clean();
        require_once viewPath('layout');
    }
}