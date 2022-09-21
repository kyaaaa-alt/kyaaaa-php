<?php
if( !function_exists('view') ) {
    function view($view) {
        return new Apk\Conf\Response($view);
    }
}

if ( !function_exists('viewPath') ) {
    function viewPath($view) {
        return __DIR__ . "/../Piews/$view.piew.php";
    }
}