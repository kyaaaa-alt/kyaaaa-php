<?php namespace Apk\Conf;
/**
 *
 *	Using PDO for create a database connection
 */
class Connection
{
    /**
     *	@var $driver, default database driver
     *
     *  [
     *		mysql  - default port 3306
     *		pgsql  - default port 5432
     *		sqlsrv - default port 1433
     *	]
     */
    private $driver = 'mysql';
    /**
     *	@var $mysql, mysql configuration
     */
    private $mysql = [
        'host'     => '127.0.0.1',
        'database' => 'kyaaaa_db',
        'user' 	   => 'root',
        'password' => '',
        'port' 	   => 3306,
        'charset'  => 'utf8',
    ];

    /**
     *	@var $pgsql, postgresql configuration
     */
    private $pgsql = [
        'host'     => '127.0.0.1',
        'database' => 'yourdatabasename',
        'user' 	   => 'yourusername',
        'password' => 'yourpassword',
        'port' 	   => 5432,
    ];

    /**
     *	@var $sqlsrv, sql server configuration
     */
    private $sqlsrv = [
        'host'     => 'yourhostname',
        'database' => 'yourdatabasename',
        'user' 	   => 'yourusername',
        'password' => 'yourpassword',
        'port' 	   => 1433,
    ];

    /**
     *	@var $sqlite, store sqlite database path
     */
    private $sqlite = [
        'path' => 'yourpath/database.db',
    ];

    /**
     *	@var object $connect, Holds the PDO connection object
     */
    private $connect = null;

    /**
     *	@var array $options, PDO mysql configuration options
     */
    private $options = [
        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
    ];

    /**
     *	@throws PDOException
     *	@throws Exception
     */
    public function PDO ($driver = null)
    {
        if ($driver !== null) {
            $this->driver = strtolower($driver);
        }

        try {

            if ($this->driver === 'mysql') {
                $this->connectMySQl();
            } elseif ($this->driver === 'pgsql') {
                $this->connectPostgreSQl();
            } elseif ($this->driver === 'sqlsrv') {
                $this->connectSQlServer();
            } elseif ($this->driver === 'sqlite') {
                $this->connectSQlite();
            }

        } catch(\PDOException $e) {

            exit($e->getMessage());
        }

        return $this->connect;
    }

    /**
     *	Set common PDO attributes
     */
    private function setAttributes ()
    {
        $this->connect->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        $this->connect->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->connect->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
        $this->connect->setAttribute(\PDO::ATTR_CASE, \PDO::CASE_NATURAL);
    }

    /**
     *	Connect PDO to MySQl database
     *	@return void
     */
    private function connectMySQl ()
    {
        $this->connect = new \PDO("mysql:host={$this->mysql['host']};port={$this->mysql['port']};dbname={$this->mysql['database']};charset={$this->mysql['charset']}", $this->mysql['user'], $this->mysql['password'], $this->options);

        $this->setAttributes();
    }

    /**
     *	Connect PDO to PostgreSQl database
     *	@return void
     */
    private function connectPostgreSQl ()
    {
        $this->connect = new \PDO("pgsql:host={$this->pgsql['host']};port={$this->pgsql['port']};dbname={$this->pgsql['database']}", $this->pgsql['user'], $this->pgsql['password']);

        $this->setAttributes();
    }

    /**
     *	Connect PDO to SQl Server database
     *	@return void
     */
    private function connectSQlServer ()
    {
        $this->connect = new \PDO("sqlsrv:Server=".$this->sqlsrv['host'].";Database=".$this->sqlsrv['database']."", $this->sqlsrv['user'], $this->sqlsrv['password']);

        $this->setAttributes();
    }

    /**
     *	Connect PDO to SQlite embedded database
     *	@return void
     */
    private function connectSQlite ()
    {
        $this->connect = new \PDO("sqlite:".$this->sqlite['path']);

        $this->setAttributes();
    }
}

?>