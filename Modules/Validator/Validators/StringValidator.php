<?php

namespace Application\Modules\Validator\Validators;

use Application\Modules\Validator\Validators\ValidatorInterface as ValidatorInterface;

/**
 * Class StringValidator
 * @package Application\Modules\Validator\Validators
 */
abstract class StringValidator implements ValidatorInterface
{

    /**
     * @param $value
     * @param array $options
     * @param string $name
     * @return array
     */
    public static function isValid($value, array $options, string $name): array
    {
        if (isset($options['options']['min'])) {
            if (!(self::isMin($value, $options['options']['min']))) {
                return [
                    'suc' => false,
                    'errMsg' => ['key' => 'minString', 'name' => $name, 'number' => $options['options']['min']]
                ];
            }
        }
        if (isset($options['options']['max'])) {
            if (!(self::isMax($value, $options['options']['max']))) {
                return [
                    'suc' => false,
                    'errMsg' => ['key' => 'maxString', 'name' => $name, 'number' => $options['options']['max']]
                ];
            }
        }
        return ['suc' => true];
    }

    /**
     * @param string $value
     * @param int $min
     * @return bool
     */
    private static function isMin(string $value, int $min): bool
    {
        if (strlen($value) < $min) {
            return false;
        }
        return true;
    }

    /**
     * @param string $value
     * @param int $max
     * @return bool
     */
    private static function isMax(string $value, int $max): bool
    {
        if (strlen($value) > $max) {
            return false;
        }
        return true;
    }
}