<?php

namespace Application\Models;

use Application\Core\Model as Model;

/**
 * Class UserModel
 * @package Application\Models
 */
class UserModel extends Model
{
    /**
     * @var
     */
    private $id;
    /**
     * @var
     */
    private $login;
    /**
     * @var
     */
    private $email;
    /**
     * @var
     */
    private $password;

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login)
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }


    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return array
     */
    public function verifyUserPassword(string $password): array
    {
        if (!password_verify($this->getPassword(), $password)) {
            return ['error' => 'Неверно введен login или password. Попробуйте набрать еще раз.'];
        }
        return ['error' => ''];
    }

    /**
     * @param string $password
     * @return string
     */
    public function createPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
