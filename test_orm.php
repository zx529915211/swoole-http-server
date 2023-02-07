<?php
require_once __DIR__ . "/vendor/autoload.php";

use Illuminate\Database\Capsule\Manager as Capsule;

//$db = new Capsule;
//
//$db->addConnection([
//    'driver' => 'mysql',
//    'host' => '120.25.161.33',
//    'database' => 'gzf',
//    'username' => 'root',
//    'password' => '86315420',
//    'charset' => 'utf8',
//    'collation' => 'utf8_unicode_ci',
//    'prefix' => '',
//]);
//
//// Set the event dispatcher used by Eloquent models... (optional)
//
//// Make this Capsule instance available globally via static methods... (optional)
//$db->setAsGlobal();
//
//// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
//$db->bootEloquent();

$db = new \core\init\Db();
$data = $db->table('user')->where('id','=','1')->get();
foreach ($data as $user){
    echo $user->name;
}
//
//$data = \App\models\Test::all();
//foreach ($data as $user){
//    var_dump($user->name);
//}