<?php

namespace Application\Modules\Validator;

use Application\Modules\Validator\Validators\BasicValidator as BasicValidator;
use Application\Modules\Validator\Validators\StringValidator as StringValidator;
use Application\Modules\Validator\Validators\EmailValidator as EmailValidator;
use Application\Modules\Validator\Validators\SelectValidator as SelectValidator;
use Application\Modules\Validator\Forms\LoginForm as LoginForm;
use Application\Modules\Validator\Forms\SignupForm as SignupForm;
use Application\Modules\Validator\Forms\AddAlbumForm as AddAlbumForm;
use Application\Modules\Validator\Forms\UpdateAlbumForm as UpdateAlbumForm;
use Application\Modules\Validator\Forms\UpdateLoginForm as UpdateLoginForm;
use Application\Modules\Validator\Forms\UpdateEmailForm as UpdateEmailForm;
use Application\Modules\Validator\Forms\UpdatePasswordForm as UpdatePasswordForm;


/**
 * Class Core
 * @package Application\Modules\Validator
 */
abstract class Core
{
    /**
     * @param array $value
     * @param string $formClass
     * @return array
     * @throws \Exception
     */
    public static function isValidForm(array $value, string $formClass)
    {
        $suc = true;
        $errMsg = '';
        $i = 1;

        switch ($formClass) {
            case LoginForm::class:
                {
                    $validatorSpecification = LoginForm::getValidatorSpecification();
                    break;
                }
            case SignupForm::class:
                {
                    $validatorSpecification = SignupForm::getValidatorSpecification();
                    break;
                }
            case AddAlbumForm::class:
                {
                    $validatorSpecification = AddAlbumForm::getValidatorSpecification();
                    break;
                }
            case UpdateAlbumForm::class:
                {
                    $validatorSpecification = UpdateAlbumForm::getValidatorSpecification();
                    break;
                }
            case UpdateLoginForm::class:
                {
                    $validatorSpecification = UpdateLoginForm::getValidatorSpecification();
                    break;
                }
            case UpdateEmailForm::class:
                {
                    $validatorSpecification = UpdateEmailForm::getValidatorSpecification();
                    break;
                }
            case UpdatePasswordForm::class:
                {
                    $validatorSpecification = UpdatePasswordForm::getValidatorSpecification();
                    break;
                }
            default:
                {
                    throw new \Exception('Неверно объявлен класс валидации формы');
                }
        }

        foreach ($validatorSpecification['validationRules'] as $key => $options) {

            $options['base']['key'] = $key;
            $resValid = BasicValidator::isValid($value, $options['base'], $key);

            if (!($resValid['suc'])) {
                $errMsg .= $i.') '.self::addMsgErr($resValid['errMsg'], $i).'</br>';
                $suc = false;
                $i++;
                continue;
            }
            switch ($options['type']['name']) {
                case 'string':
                    {
                        $resValid = StringValidator::isValid($value[$key], $options['type'], $key);
                        break;
                    }
                case 'email':
                    {
                        $resValid = EmailValidator::isValid($value[$key], $options['type'], $key);
                        break;
                    }
                case 'select':
                    {
                        $resValid = SelectValidator::isValid($value[$key], $options['type'], $key);
                        break;
                    }
                default:
                    {
                        throw new \Exception('Неверно объявлен тип валидации элемента формы');
                    }
            }

            if (!($resValid['suc'])) {
                $errMsg .= $i.') '.self::addMsgErr($resValid['errMsg']).'</br>';
                $suc = false;
                $i++;
                continue;
            }
        }

        if ($suc) {
            return ['suc' => true, 'errDiv' => $validatorSpecification['idErrorDiv']];
        } else {
            return ['suc' => false, 'errMsg' => $errMsg, 'errDiv' => $validatorSpecification['idErrorDiv']];
        }
    }

    /**
     * @return array
     */
    public static function getMessageTemplate():array
    {
        return [
            'required'=>'Поле {name} должно быть заполнено',
            'like'=>'Поля {name1} и {name2} должны совпадать',
            'unlike'=>'Поля {name1} и {name2} не должны совпадать',
            'minString'=>'Поле {name} должно содержать больше {number} символов',
            'maxString'=>'Поле {name} должно содержать меньше {number} символов',
            'initInteger'=>'В поле {name} введено не целое число',
            'minInteger'=>'Цифра в поле {name} должна быть больше {number}',
            'maxInteger'=>'Цифра в поле {name} должна быть меньше {number}',
            'initEmail'=>'В поле {name} введена не электронная почта',
            'initSelect'=>'В поле {name} введены несоответсвующие данные данному полю'
        ];
    }
    /**
     * @param array $errMsg
     * @return mixed
     * @throws \Exception
     */
    private static function addMsgErr(array $errMsg)
    {
        $errorMessageTemplate=self::getMessageTemplate();
        switch($errMsg['key']) {
            case 'required': {
                $msg=$errorMessageTemplate[$errMsg['key']];
                $msg=str_replace('{name}', $errMsg['name'],$msg);
                return $msg;
            }
            case 'like': {
                $msg=$errorMessageTemplate[$errMsg['key']];
                $msg=str_replace('{name1}', $errMsg['name1'],$msg);
                $msg=str_replace('{name2}', $errMsg['name2'],$msg);
                return $msg;
            }
            case 'unlike': {
                $msg=$errorMessageTemplate[$errMsg['key']];
                $msg=str_replace('{name1}', $errMsg['name1'],$msg);
                $msg=str_replace('{name2}', $errMsg['name2'],$msg);
                return $msg;
            }
            case 'minString': {
                $msg=$errorMessageTemplate[$errMsg['key']];
                $msg=str_replace('{name}', $errMsg['name'],$msg);
                $msg=str_replace('{number}', $errMsg['number'],$msg);
                return $msg;
            }
            case 'maxString': {
                $msg=$errorMessageTemplate[$errMsg['key']];
                $msg=str_replace('{name}', $errMsg['name'],$msg);
                $msg=str_replace('{number}', $errMsg['number'],$msg);
                return $msg;
            }
            case 'initInteger': {
                $msg=$errorMessageTemplate[$errMsg['key']];
                $msg=str_replace('{name}', $errMsg['name'],$msg);
                return $msg;
            }
            case 'minInteger': {
                $msg=$errorMessageTemplate[$errMsg['key']];
                $msg=str_replace('{name}', $errMsg['name'],$msg);
                $msg=str_replace('{number}', $errMsg['number'],$msg);
                return $msg;
            }
            case 'maxInteger': {
                $msg=$errorMessageTemplate[$errMsg['key']];
                $msg=str_replace('{name}', $errMsg['name'],$msg);
                $msg=str_replace('{number}', $errMsg['number'],$msg);
                return $msg;
            }
            case 'initEmail': {
                $msg=$errorMessageTemplate[$errMsg['key']];
                $msg=str_replace('{name}', $errMsg['name'],$msg);
                return $msg;
            }
            case 'initSelect': {
                $msg=$errorMessageTemplate[$errMsg['key']];
                $msg=str_replace('{name}', $errMsg['name'],$msg);
                return $msg;
            }
            default:{
                throw new \Exception('Возвращен неверный код ошибки валидации');
            }
        }
    }
}