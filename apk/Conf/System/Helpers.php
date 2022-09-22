<?php

if ( !function_exists('dd') ) {
    function dd($var, $exit = true) {
        echo '<html><head><title>Kyaaaa~ Ge-debug!</title><meta name="viewport" content="width=device-width, initial-scale=1.0"></head><body style="background: #202124;font-size: 15px;"><div style="padding:10px;background:#b12776;margin-bottom:10px;color:#fff;max-width:768px;font-weight:normal;font-family:Courier New;border-radius: 8px;font-size: 17px;letter-spacing: 1.8px;">Kyaaaa~ Ge-debug!</div><div style="margin-left: 25px;">';
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
        echo '</div></body></html>';
        if ($exit) {
            exit;
        }
    }

    function esc($data = NULL, $depth = 0) {
        global $sanitized;
        if ($data === NULL and $depth == 0) {
            if ($sanitized) {
                return;
            }
            else {
                $GLOBALS['sanitized'] = True;
            }
        }

        if ($depth < 0 or !is_int($depth)) {
            $depth = 0;
        }

        $depth = $depth + 1;

        if ($depth > 10) {
            return NULL;
        }

        if ($data === NULL and $depth == 1) {
            if (isset($_GET)) {
                $_GET = sanitize($_GET, $depth);
            }
            if (isset($_POST)) {
                $_POST = sanitize($_POST, $depth);
            }
            if (isset($_COOKIE)) {
                $_COOKIE = sanitize($_COOKIE, $depth);
            }
            if (isset($_REQUEST)) {
                $_REQUEST = sanitize($_REQUEST, $depth);
            }
            if (isset($_FILES)) {
                $_FILES = sanitize($_FILES, $depth);
            }
        }

        $type = gettype($data);
        $output = NULL;
        if ($type === "boolean" and is_bool($data)) {
            if ($data) {
                $data = true;
            }
            else {
                $data = false;
            }
            $output = (bool)$data;
        }
        else if ($type === "integer" and is_int($data) and is_numeric($data)) {
            $data = intval($data);
            $data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
            $output = (int)$data;
        }
        else if ($type === "double" and is_float($data) and is_numeric($data)) {
            $data = filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT);
            $output = (double)$data;
        }
        else if ($type === "string") {
            $data = trim($data);
            if (filter_var($data, FILTER_VALIDATE_EMAIL)) {
                // If $data is an email, leave it alone.
            }
            else {
                $data = stripslashes($data);
                $data = strip_tags($data);
                $data = htmlspecialchars($data);
                $data = filter_var($data, FILTER_SANITIZE_STRING);
                $data = addslashes($data);
            }
            $output = (string)$data;
        }
        else if ($type === "array" and is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = sanitize($value, $depth);
            }
            $output = (array)$data;
        }
        else if ($type === "object" and is_object($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = sanitize($value, $depth);
            }
            $output = (object)$data;
        }
        else if ($type === "resource" and is_resource($data)) {
        }
        else if ($type === "NULL" and is_null($data)) {
        }
        else if ($type === "unknown type") {
        }
        return $output;
    }
}

if( !function_exists('view') ) {
    function view($view, $data = []) {
        $getView = new \Apk\Conf\System\GetView($view, $data);
        return $getView->send();
    }
}

if ( !function_exists('viewPath') ) {
    function viewPath($view, $data = []) {
        return __DIR__ . "/../../Piews/$view.piew.php";
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

if ( !function_exists('url') ) {
    function url($segments = '') {
        $url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http")."://".$_SERVER['HTTP_HOST'].str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
        if ($segments != '') {
            return $url . $segments;
        } else {
            return $url;
        }

    }
}