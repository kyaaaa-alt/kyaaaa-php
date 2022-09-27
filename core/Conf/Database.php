<?php namespace Core\Conf;

class Database {
    /**
     *	'driver' = mysql, pgsql, sqlsrv, sqlite
     *  you can leave blank of 'sqlite_path' if using mysql, pgsql, or sqlsrv
     *  mysql  - default port 3306
     *  pgsql  - default port 5432
     *  sqlsrv - default port 1433
     *
     *  'sqlite_path' - path to your sqlite database file.
     *  if you using sqlite, you just need change 'driver' & 'sqlite_path'
     *  and you can leave blank of 'host', 'database', 'user', and 'password'
     */
    public function config() {
        $config = [
            'driver'        =>  'mysql',
            'host'          =>  '127.0.0.1',
            'database'      =>  'kyaaaa_db',
            'username' 	    =>  'root',
            'password'      =>  '',
            'port'          =>  3306,
            'sqlite_path'   =>  ''
        ];
        return $config;
    }
}
