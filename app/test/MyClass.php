<?php


namespace App\test;


use DI\Annotation\Inject;

class MyClass
{
    private $myDb;

    /**
     * @Inject()
     * @param MyDb $db
     */
    public function __construct(MyDb $db)
    {
        $this->myDb = $db;
    }
}