<?php namespace Core\Conf;

use Core\Conf\Kyaaaa\Router;

class Routes {
    public function kyaaaaRun() {

        $router = new Router();
        $router->get('/', [\Core\Controllers\HomeCtrl::class,'index']);
        $router->get('/docs', [\Core\Controllers\DocsCtrl::class,'index']);

        // Middleware Example (START)
        $router->post('/post', [\Core\Controllers\HomeCtrl::class,'post'])->middleware(function(){
            if(!csrf()->isValidRequest()){
                throw new Exception('Token Not Valid');
            }
        });

        $router->get('/items/:id', function($id) {
            $sess = session();
            $notif = $sess->getFlash('notif');
            echo $notif;
        })->middleware(function($id){
            if(!filter_var($id,FILTER_VALIDATE_INT)){
                throw new Exception($id . ' must be an intiger');
                exit;
            }
        });
        // Middleware Example (END)

        $router->run();
    }
}