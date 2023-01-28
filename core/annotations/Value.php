<?php

namespace core\annotations;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Value
{
    public $name = '';
}