<?php

namespace Application\Modules\Validator\Forms;

use Application\Modules\Validator\Forms\FormInterface as FormInterface;
use Application\Modules\Validator\Validators\StringValidator as StringValidator;
use Application\Modules\Validator\Validators\EmailValidator as EmailValidator;

/**
 * Class SignupForm
 * @package Application\Modules\Validator\Forms
 */
abstract class SignupForm implements FormInterface
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
                'email' => [
                    'base' => [
                        'required' => true,
                        'unlike' => 'login'
                    ],
                    'type' => [
                        'name' => 'email',
                        'options' => []
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
                'repeat_password' => [
                    'base' => [
                        'required' => true,
                        'like' => 'password'
                    ],
                    'type' => [
                        'name' => 'string',
                        'options' => []
                    ],
                ],
            ],
            'idErrorDiv'=>'errorMsg'
        ];
    }
}