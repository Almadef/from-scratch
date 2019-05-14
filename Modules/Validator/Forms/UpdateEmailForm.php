<?php

namespace Application\Modules\Validator\Forms;

use Application\Modules\Validator\Forms\FormInterface as FormInterface;

/**
 * Class UpdateEmailForm
 * @package Application\Modules\Validator\Forms
 */
abstract class UpdateEmailForm implements FormInterface
{
    /**
     * @return array
     */
    public static function getValidatorSpecification(): array
    {
        return [
            'validationRules' => [
                'newEmail' => [
                    'base' => [
                        'required' => true
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
            ],
            'idErrorDiv'=>'errorUpdateEmailMsg'
        ];
    }
}