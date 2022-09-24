<?php

if ( !function_exists('dd') ) {
    function dd($var, $exit = true) {
        echo '<html><head><title>Kyaaaa~ Ge-debug!</title><meta name="viewport" content="width=device-width, initial-scale=1.0"></head><body style="background: #202124;font-size: 15px;"><div style="padding:10px;background:#b12776;margin-bottom:10px;color:#fff;max-width:1920px;font-weight:normal;font-family:Courier New;border-radius: 8px;font-size: 17px;letter-spacing: 1.8px;">Kyaaaa~ Ge-debug!</div><div style="margin-left: 25px;">';
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
}

if( !function_exists('esc') ) {
    function esc($str, $entities = true) {
        if ($entities == false ) {
            $str = htmlspecialchars($str, ENT_QUOTES);
            $str = str_replace("&", "&amp;", $str);
            $str = str_replace(":", "&#58;", $str);
            $str = str_replace(";", "&#59;", $str);
            $str = str_replace("/", "&#47;", $str);
            $str = str_replace(">", "&#62;", $str);
            $str = str_replace("<", "&#60;", $str);
            $str = str_replace("'", "&#39;", $str);
            $str = str_replace("\"", "&quot;", $str);
            $str = str_replace("-", "&#45;", $str);
            $str = str_replace("_", "&#95;", $str);
            $str = str_replace("=", "&#61;", $str);
            $str = str_replace("(", "&#40;", $str);
            $str = str_replace(")", "&#41;", $str);
            $str = str_replace('document.cookie', '[cleanxss]', strtolower($str));
            $str = str_replace('document.domain', '[cleanxss]', strtolower($str));
            $str = str_replace('document.write', '[cleanxss]', strtolower($str));
            $str = str_replace('.parentNode', '[cleanxss]', strtolower($str));
            $str = str_replace('.innerHTML', '[cleanxss]', strtolower($str));
            $str = str_replace('<![CDATA[', '&lt;![CDATA[', strtolower($str));
            $str = str_replace('<comment>', '&lt;comment&gt;', strtolower($str));
            $str = str_replace('<script>', '&#60;script&#62;', strtolower($str));
            $str = str_replace('<!--', '&lt;!--', $str);
            $str = str_replace('-->', '--&gt;', $str);
        } else {
            $str = htmlspecialchars($str, ENT_QUOTES);
        }
        return $str;
    }
}

if( !function_exists('view') ) {
    function view($view, $data = []) {
        $getView = new \Core\Conf\Kyaaaa\GetView($view, $data);
        return $getView->send();
    }
}

if ( !function_exists('viewPath') ) {
    function viewPath($view, $data = []) {
        return __DIR__ . "/../../Views/$view.piew.php";
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