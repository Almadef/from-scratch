<?php

namespace Application\Modules\Validator\Forms;

/**
 * Interface FormInterface
 * @package Application\Modules\Validator\Forms
 */
interface FormInterface
{
    /**
     * @return array
     */
    public static function getValidatorSpecification():array;
}