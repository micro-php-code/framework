<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Model;

use MicroPHP\Framework\Model\Traits\ModelIdeHelper;

class Model extends \Illuminate\Database\Eloquent\Model
{
    use ModelIdeHelper;
}
