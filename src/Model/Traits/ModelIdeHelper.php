<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Model\Traits;

/**
 * @method static static      updateOrCreate(array $attributes, array $values = [])
 * @method static static|null find(string|int $id, $columns = ['*'])
 * @method static static      create(array $attributes = [])
 */
trait ModelIdeHelper
{
    public static function findByField(string $key, string|int $value): ?static
    {
        /** @var static $data */
        return self::query()->where($key, $value)->first();
    }

    public static function findByWhere(array $where): ?static
    {
        /** @var static $data */
        return self::query()->where($where)->first();
    }

    public static function newModelInstance(): static
    {
        return new static();
    }
}
