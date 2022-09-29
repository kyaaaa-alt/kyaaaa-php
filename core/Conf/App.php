<?php namespace Core\Conf;

class App {
    /**
     * Environment : This is for displaying error on the website, on production mode, all errors will be hidden.
     * @return string 'development' or 'production'
     */
    public function environment() {
        return 'development';
    }

    /**
     * Timezone config.
     * @see https://www.php.net/manual/en/timezones.php
     */
    public function app_timezone() {
        return 'Asia/Jakarta';
    }

    /**
     * Session config.
     * @see https://www.php.net/manual/en/session.configuration.php
     */
    public function session() {
        \Core\Conf\Kyaaaa\Session::config([
            'name'            => 'kyaaaa_session',
            'cookie_lifetime' => 86400, // Seconds
            'cookie_domain' => '',
            'cookie_samesite' => '',
            'cookie_path' => '/',
            'cache_expire' => '180',
            'cache_limiter' => 'nocache'
        ]);
    }
}
