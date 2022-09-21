<?php namespace Apk\Modols;

use Kyaaaa\System\DB;

class HomeModol {
    public function get_users() {
        $query = DB::query('users')->all()->get();
        return $query;
    }
}