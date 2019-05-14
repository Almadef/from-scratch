<?php

namespace Application\Modules\Validator\Validators;

use Application\Modules\Validator\Validators\ValidatorInterface as ValidatorInterface;

/**
 * Class SelectValidator
 * @package Application\Modules\Validator\Validators
 */
abstract class SelectValidator implements ValidatorInterface
{

    /**
     * @param $value
     * @param array $options
     * @param string $name
     * @return array
     */
    public static function isValid($value, array $options, string $name): array
    {
        if (!(is_array($value))) {
            return [
                'suc' => false,
                'errMsg' => ['key' => 'initSelect', 'name' => $name]
            ];
        }
        return ['suc' => true];
    }
}