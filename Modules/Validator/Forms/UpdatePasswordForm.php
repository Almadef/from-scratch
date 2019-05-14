<?php

namespace Application\Modules\Validator\Forms;

use Application\Modules\Validator\Forms\FormInterface as FormInterface;

/**
 * Class UpdatePasswordForm
 * @package Application\Modules\Validator\Forms
 */
abstract class UpdatePasswordForm implements FormInterface
{
    /**
     * @return array
     */
    public static function getValidatorSpecification(): array
    {
        return [
            'validationRules' => [
                'newPassword' => [
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
                'repeatNewPassword' => [
                    'base' => [
                        'required' => true,
                        'like' => 'newPassword'
                    ],
                    'type' => [
                        'name' => 'string',
                        'options' => []
                    ],
                ],
                'oldPassword' => [
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
            'idErrorDiv'=>'errorUpdatePasswordMsg'
        ];
    }
}