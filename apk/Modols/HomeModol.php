<?php namespace Apk\Modols;

use Kyaaaa\System\DB;

class HomeModol {
    public function get_users_active() {
        $query = DB::query('users')->select('*')->where('status >=', 1)->get();
        return $query;
    }
}