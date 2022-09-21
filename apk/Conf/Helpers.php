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

if ( !function_exists('d') ) {
    function d($var, $exit = true) {
        var_dump($var);
        if ($exit) {
            exit;
        }
    }
}

if ( !function_exists('dd') ) {
    function dd($var, $exit = true) {
        echo '<body style="background: #202124;font-size: 15px;">';
        if (is_array($var) || is_object($var)) {
            ini_set("highlight.keyword", "#ff79c6;  font-weight: bolder");
            ini_set("highlight.string", "#c1f953; font-weight: lighter; ");
            ini_set("highlight.default", "#fff; font-weight: lighter; ");

            ob_start();
            highlight_string("<?php\r" . var_export($var, true) . " ?>");
            $highlighted_output = ob_get_clean();

            $highlighted_output = str_replace( ["&lt;?php","?&gt;"] , '', $highlighted_output );

            echo $highlighted_output;
        } else {
            echo "<pre style='color: #fff;font-size: 15px;'>";
            var_dump($var);
            echo "</pre>";
        }
        echo '</body>';
        if ($exit) {
            exit;
        }
    }
}