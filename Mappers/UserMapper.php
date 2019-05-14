<?php

namespace Application\Mappers;

use Application\Core\Mapper as Mapper;


/**
 * Class UserMapper
 * @package Application\Mappers
 */
class UserMapper extends Mapper
{
    /**
     * @param string $userLogin
     * @return array
     */
    public function availableLogin(string $userLogin): array
    {
        $stmt = $this->db->prepare('select count(*) from user where login = :login');
        $stmt->bindParam(':login', $userLogin, \PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->fetch();
        if ($count[0] != 0) {
            return ['error' => "Login $userLogin уже занят"];
        }
        return ['error' => ''];
    }

    /**
     * @param string $userEmail
     * @return array
     */
    public function availableEmail(string $userEmail): array
    {
        $stmt = $this->db->prepare('select count(*) from user where email = :email');
        $stmt->bindParam(':email', $userEmail, \PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->fetch();
        if ($count[0] != 0) {
            return ['error' => "Email $userEmail уже занят"];
        }
        return ['error' => ''];
    }


    /**
     * @param string $userLogin
     * @return mixed
     */
    public function selectUserWhereLogin(string $userLogin)
    {
        $stmt = $this->db->prepare('select * from user where login = :login');
        $stmt->bindParam(':login', $userLogin, \PDO::PARAM_STR);
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_CLASS, "Application\Models\UserModel");
        return $stmt->fetch();
    }

    /**
     * @param int $userId
     * @return \Application\Models\UserModel
     */
    public function selectUserWhereId(int $userId): \Application\Models\UserModel
    {
        $stmt = $this->db->prepare('select * from user where id = :id');
        $stmt->bindParam(':id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_CLASS, "Application\Models\UserModel");
        return $stmt->fetch();
    }

    /**
     * @param \Application\Models\UserModel $user
     * @return int
     */
    public function insertUser(\Application\Models\UserModel $user): int
    {
        $userLogin = $user->getLogin();
        $userEmail = $user->getEmail();
        $userPassword = $user->getPassword();
        $stmt = $this->db->prepare('INSERT INTO user (`login`, `email`, `password`) VALUES (:login, :email, :password)');
        $stmt->bindParam(':login', $userLogin, \PDO::PARAM_STR);
        $stmt->bindParam(':email', $userEmail, \PDO::PARAM_STR);
        $stmt->bindParam(':password', $userPassword, \PDO::PARAM_STR);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    /**
     * @param string $var
     * @param int $idUser
     * @return array
     */
    public function updateUserLogin(string $var, int $idUser): array
    {
        $this->db->beginTransaction();
        $sql = 'UPDATE `user` SET login=? where id=?';
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute([$var, $idUser])) {
            $this->db->commit();
            return ['error' => ''];
        } else {
            $this->db->rollback();
            return ['error' => 'Не удалось изменить логин в БД'];
        }
    }

    /**
     * @param string $var
     * @param int $idUser
     * @return array
     */
    public function updateUserEmail(string $var, int $idUser): array
    {
        $this->db->beginTransaction();
        $sql = 'UPDATE `user` SET email=? where id=?';
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute([$var, $idUser])) {
            $this->db->commit();
            return ['error' => ''];
        } else {
            $this->db->rollback();
            return ['error' => 'Не удалось изменить email в БД'];
        }
    }

    /**
     * @param string $var
     * @param int $idUser
     * @return array
     */
    public function updateUserPassword(string $var, int $idUser): array
    {
        $this->db->beginTransaction();
        $sql = 'UPDATE `user` SET password=? where id=?';
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute([$var, $idUser])) {
            $this->db->commit();
            return ['error' => ''];
        } else {
            $this->db->rollback();
            return ['error' => 'Не удалось изменить пароль в БД'];
        }
    }

    /**
     * @param string $password
     * @param int $idUser
     * @return array
     */
    public function checkPassword(string $password, int $idUser): array
    {
        $user = $this->selectUserWhereId($idUser);
        if (!password_verify($password, $user->getPassword())) {
            return ['error' => 'Неверно введен password'];
        }
        return ['error' => ''];
    }
}
