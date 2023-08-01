<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Database;

use MicroPHP\Framework\Traits\ModelIdeHelper;

class Model extends \Illuminate\Database\Eloquent\Model
{
    use ModelIdeHelper;
}