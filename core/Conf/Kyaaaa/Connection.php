<?php namespace Core\Conf\Kyaaaa;

use Core\Conf\Exception;
use Core\Conf\PDOException;

class Connection {
    public function __construct() {
        $getConfig = new \Core\Conf\Database();
        $this->driver = $getConfig->config()['driver'];
        $this->username = $getConfig->config()['username'];
        $this->password = $getConfig->config()['password'];
        $this->database = $getConfig->config()['database'];
        $this->host = $getConfig->config()['host'];
        $this->port = $getConfig->config()['port'];
        $this->sqlite_path = $getConfig->config()['sqlite_path'];
        $this->charset = 'utf8';
    }
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
            $getConf = new \Core\Conf\App();
            $handler = new \Core\Conf\Kyaaaa\Handler\Run();
            $handler->allowQuit(false);
            $handler->writeToOutput(false);
            if ($getConf->environment() == 'development') {
                $handler->pushHandler(new \Core\Conf\Kyaaaa\Handler\Handler\KyaaaaDevelopmentHandler());
            } else {
                $handler->pushHandler(new \Core\Conf\Kyaaaa\Handler\Handler\KyaaaaProductionHandler());
            }
            $html = $handler->handleException($e);
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
        $this->connect = new \PDO("mysql:host={$this->host};port={$this->port};dbname={$this->database};charset={$this->charset}", $this->username, $this->password, $this->options);
        $this->setAttributes();
    }

    /**
     *	Connect PDO to PostgreSQl database
     *	@return void
     */
    private function connectPostgreSQl ()
    {
        $this->connect = new \PDO("pgsql:host={$this->host};port={$this->port};dbname={$this->database}", $this->username, $this->password);
        $this->setAttributes();
    }

    /**
     *	Connect PDO to SQl Server database
     *	@return void
     */
    private function connectSQlServer ()
    {
        $this->connect = new \PDO("sqlsrv:Server=".$this->host.";Connection=".$this->database."", $this->username, $this->password);
        $this->setAttributes();
    }

    /**
     *	Connect PDO to SQlite embedded database
     *	@return void
     */
    private function connectSQlite ()
    {
        $this->connect = new \PDO("sqlite:".$this->sqlite_path);
        $this->setAttributes();
    }
}

?>