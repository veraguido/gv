<?php namespace Gvera\Services;

class User
{
    public function validateUser(\Gvera\Models\User $user)
    {
        if ($user->getId()){
            return true;
        } else {
            return false;
        }
    }

    public function generatePassword($plainPassword) {
        return password_hash($plainPassword, PASSWORD_BCRYPT);
    }
}