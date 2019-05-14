<?php

namespace Application\Modules\Validator\Validators;

use Application\Modules\Validator\Validators\ValidatorInterface as ValidatorInterface;

/**
 * Class EmailValidator
 * @package Application\Modules\Validator\Validators
 */
abstract class EmailValidator implements ValidatorInterface
{

    /**
     * @param $value
     * @param array $options
     * @param string $name
     * @return array
     */
    public static function isValid($value, array $options, string $name): array
    {
        if (!(self::isEmail($value))) {
            return [
                'suc' => false,
                'errMsg' => ['key' => 'initEmail', 'name' => $name]
            ];
        }
        return ['suc' => true];
    }

    /**
     * @param string $value
     * @return bool
     */
    private static function isEmail(string $value): bool
    {
        $re = "/^[\w-\.]+@[\w-]+\.[a-z]{2,4}$/i";
        if (preg_match($re, $value)) {
            return true;
        }
        return false;
    }
}