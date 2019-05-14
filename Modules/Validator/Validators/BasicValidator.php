<?php

namespace Application\Modules\Validator\Validators;

use Application\Modules\Validator\Validators\ValidatorInterface as ValidatorInterface;


/**
 * Class BasicValidator
 * @package Application\Modules\Validator\Validators
 */
abstract class BasicValidator implements ValidatorInterface
{

    /**
     * @param $value
     * @param array $options
     * @param string $name
     * @return array
     */
    public static function isValid($value, array $options, string $name): array
    {
        if ((isset($options['required'])) && ($options['required'] === true)) {
            if (!(self::isRequired($value[$options['key']]))) {
                return ['suc' => false, 'errMsg' => ['key' => 'required', 'name' => $name]];
            }
        }
        if (isset($options['like'])) {
            if (!(self::isLike($value[$options['key']], $value[$options['like']]))) {
                return ['suc' => false, 'errMsg' => ['key' => 'like', 'name1' => $name, 'name2' => $options['like']]];
            }
        }
        if (isset($options['unlike'])) {
            if (!(self::isUnlike($value[$options['key']], $value[$options['unlike']]))) {
                return [
                    'suc' => false,
                    'errMsg' => ['key' => 'unlike', 'name1' => $name, 'name2' => $options['unlike']]
                ];
            }
        }
        return ['suc' => true];
    }

    /**
     * @param $value
     * @return bool
     */
    private static function isRequired($value): bool
    {
        if (($value == null) || ($value == '')) {
            return false;
        }
        return true;
    }

    /**
     * @param $value1
     * @param $value2
     * @return bool
     */
    private static function isLike($value1, $value2): bool
    {
        if ($value1 != $value2) {
            return false;
        }
        return true;
    }

    /**
     * @param $value1
     * @param $value2
     * @return bool
     */
    private static function isUnlike($value1, $value2): bool
    {
        if ($value1 == $value2) {
            return false;
        }
        return true;
    }
}