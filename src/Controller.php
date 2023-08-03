<?php

declare(strict_types=1);

namespace MicroPHP\Framework;

use JsonSerializable;
use MicroPHP\Framework\Http\Response;
use Respect\Validation\Validator;

class Controller
{
    public function validate(array $rules, array $data): void
    {
        Validator::allOf(...$rules)->assert($data);
    }

    protected function json(mixed $data): Response
    {
        if ($data instanceof JsonSerializable) {
            $result = $data->jsonSerialize();
        } else {
            $result = $data;
        }
        if (is_array($result)) {
            $result = json_encode($result);
        }

        return new Response(200, ['Content-Type' => 'application/json; charset=utf-8'], $result);
    }
}
