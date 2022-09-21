<?php namespace Apk\Modols;

use Kyaaaa\System\DB;

class HomeModol {
    public function get_users() {
        return DB::query('users')->all()->get();
    }
}