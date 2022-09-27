<?php namespace Core\Models;

use Core\Conf\Kyaaaa\DB;

class HomeModel {
    public function __construct() {
        $this->users = DB::query('users');
    }

    public function get_users_active() {
        $builder = $this->users;
        $builder->select('*');
        $query = $builder->get();
        return $query;
    }

}