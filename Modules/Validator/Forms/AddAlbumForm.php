<?php

namespace Application\Modules\Validator\Forms;

use Application\Modules\Validator\Forms\FormInterface as FormInterface;

/**
 * Class AddAlbumForm
 * @package Application\Modules\Validator\Forms
 */
abstract class AddAlbumForm implements FormInterface
{
    /**
     * @return array
     */
    public static function getValidatorSpecification(): array
    {
        return [
            'validationRules' => [
                'addTitleAlbum' => [
                    'base' => [
                        'required' => true
                    ],
                    'type' => [
                        'name' => 'string',
                        'options' => [],
                    ],
                ],
                'addPerformersAlbum' => [
                    'base' => [
                        'required' => true
                    ],
                    'type' => [
                        'name' => 'select',
                        'options' => [],
                    ],
                ],
            ],
            'idErrorDiv'=>'errorMsgAdd'
        ];
    }
}