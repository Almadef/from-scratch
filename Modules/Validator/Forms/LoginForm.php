<?php

namespace Application\Modules\Validator\Forms;

use Application\Modules\Validator\Forms\FormInterface as FormInterface;

/**
 * Class LoginForm
 * @package Application\Modules\Validator\Forms
 */
abstract class LoginForm implements FormInterface
{
    /**
     * @return array
     */
    public static function getValidatorSpecification(): array
    {
        return [
            'validationRules' => [
                'login' => [
                    'base' => [
                        'required' => true,
                        'unlike' => 'password'
                    ],
                    'type' => [
                        'name' => 'string',
                        'options' => [
                            'min' => 6,
                            'max' => 50
                        ],
                    ],
                ],
                'password' => [
                    'base' => [
                        'required' => true
                    ],
                    'type' => [
                        'name' => 'string',
                        'options' => [
                            'min' => 6,
                            'max' => 50
                        ],
                    ],
                ],
            ],
            'idErrorDiv'=>'errorMsg'
        ];
    }
}