<?php namespace Gvera\Services;

use Gvera\Helpers\entities\EntityManager;
use Gvera\Helpers\session\Session;
use Gvera\Helpers\validation\EmailValidationStrategy;
use Gvera\Helpers\validation\ValidationService;
use Gvera\Models\User;

class UserService
{
    public function validateEmail($email)
    {
        return ValidationService::validate($email, [new EmailValidationStrategy()]);
    }

    public function generatePassword($plainPassword) {
        return password_hash($plainPassword, PASSWORD_BCRYPT);
    }

    public function validatePassword($plainPassword, $hash) {
        return password_verify($plainPassword, $hash);
    }

    public function login($userName, $password)
    {
        $em = EntityManager::getInstance()->getRepository(User::class);
        $user = $em->findOneBy(['username' => $userName]);

        var_dump($this->validatePassword($password, $user->getPassword()));
        if ($user && $user->getUsername() == $userName && $this->validatePassword($password, $user->getPassword())) {
            Session::set('user', ['username' => $userName, 'userEmail' => $user->getEmail()]);
        }
    }

    public static function isUserLoggedIn() {
        return Session::get('user') != null;
    }
}