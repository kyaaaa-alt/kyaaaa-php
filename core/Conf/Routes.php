<?php namespace Core\Conf;

use Core\Conf\Kyaaaa\Router;

class Routes {
    public function kyaaaaRun() {

        $router = new Router();
        $router->get('/', [\Core\Controllers\HomeCtrl::class,'index']);
        $router->get('/docs', [\Core\Controllers\DocsCtrl::class,'index']);

        // Middleware Example
        $router->get('/items/:id', function($id) {
            echo "id #:".$id;
        })->middleware(function($id){
            if(!filter_var($id,FILTER_VALIDATE_INT)){
                echo "id should provide an integer";
                exit;
            }
        });

        $router->run();
    }
}