<?php

namespace Application\Modules\Validator\Validators;


/**
 * Interface ValidatorInterface
 * @package Application\Modules\Validator\Validators
 */
interface ValidatorInterface
{
    /**
     * @param $value
     * @param array $options
     * @param string $name
     * @return array
     */
    public static function isValid($value, array $options, string $name): array;
}