<?php
require (__DIR__ . '/Database.php');
require (__DIR__ . '/System/QueryBuilder.php');
if( !function_exists('view') ) {
    function view($view, $data = []) {
        return new Apk\Conf\Response($view, $data);
    }
}

if ( !function_exists('viewPath') ) {
    function viewPath($view, $data = []) {
        return __DIR__ . "/../Piews/$view.piew.php";
    }
}