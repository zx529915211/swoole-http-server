<?php

namespace core\annotations;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Bean
{
    public $name = '';
}