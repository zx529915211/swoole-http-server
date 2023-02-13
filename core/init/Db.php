<?php


namespace core\init;

use core\annotations\Bean;
use Illuminate\Database\Capsule\Manager as baseDb;

/**
 *
 * @method \Illuminate\Database\Query\Builder table(\Closure|\Illuminate\Database\Query\Builder|string $table, string|null $as, string|null $connection)
 */
#[Bean]
class Db
{
    private $baseDb;
    private $dbSource;

    public function __construct()
    {
        $this->baseDb = new baseDb();
        $database_config = Config::get('database');
        foreach ($database_config as $name => $config) {
            $this->baseDb->addConnection($config, $name);
        }
//        $this->baseDb->addConnection(Config::get('database.default'));
        $this->baseDb->setAsGlobal();
        $this->baseDb->bootEloquent();
    }

    public function __call($name, $arguments)
    {
        return $this->baseDb::connection($this->dbSource)->$name(...$arguments);
    }

    /**
     * @return mixed
     */
    public function getDbSource()
    {
        return $this->dbSource;
    }

    /**
     * @param mixed $dbSource
     */
    public function setDbSource($dbSource): void
    {
        $this->dbSource = $dbSource;
    }
}