<?php namespace Apk\Conf;

use Kyaaaa\Controller\UserController;
use Kyaaaa\Middlleware\UserValidation;
use Kyaaaa\Routing\Router;

class KyaaaaRoute {
    public function kyaaaaRun() {
        $router = new Router();
        $router->get('/', [\Apk\Kontolers\HomeKntl::class,'index']);
        $router->get('/docs', [\Apk\Kontolers\DocsKntl::class,'index']);
        $router->run();
    }
}