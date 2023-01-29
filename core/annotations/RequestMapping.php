<?php

namespace core\annotations;

use Attribute;

#[Attribute(Attribute::TARGET_MEHTOD)]
class RequestMapping
{
    public $value = ''; //路径 如api/test
    public $method = []; //GET、POST等
}