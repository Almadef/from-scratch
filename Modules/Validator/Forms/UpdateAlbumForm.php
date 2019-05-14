<?php

namespace Application\Modules\Validator\Forms;

use Application\Modules\Validator\Forms\FormInterface as FormInterface;

/**
 * Class UpdateAlbumForm
 * @package Application\Modules\Validator\Forms
 */
abstract class UpdateAlbumForm implements FormInterface
{
    /**
     * @return array
     */
    public static function getValidatorSpecification(): array
    {
        return [
            'validationRules' => [
                'updateTitleAlbum' => [
                    'base' => [
                        'required' => true
                    ],
                    'type' => [
                        'name' => 'string',
                        'options' => [],
                    ],
                ],
                'updatePerformersAlbum' => [
                    'base' => [
                        'required' => true
                    ],
                    'type' => [
                        'name' => 'select',
                        'options' => [],
                    ],
                ],
            ],
            'idErrorDiv'=>'errorMsgUpadte'
        ];
    }
}