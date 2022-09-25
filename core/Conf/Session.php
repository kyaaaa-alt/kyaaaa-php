<?php namespace Core\Conf;

class Session {
    /**
     * Session options.
     * @see https://www.php.net/manual/en/session.configuration.php
     */
    public function config() {
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