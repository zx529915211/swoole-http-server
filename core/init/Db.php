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

    public function __construct()
    {
        $this->baseDb = new baseDb();
        $this->baseDb->addConnection([
            'driver' => 'mysql',
            'host' => '120.25.161.33',
            'database' => 'gzf',
            'username' => 'root',
            'password' => '86315420',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);
        $this->baseDb->setAsGlobal();
        $this->baseDb->bootEloquent();
    }

    public function __call($name, $arguments)
    {
        return $this->baseDb::$name(...$arguments);
    }
}