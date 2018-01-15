<?php

namespace Gvera\Services;


use Gvera\Events\ForgotPasswordCreatedEvent;
use Gvera\Helpers\entities\EntityManager;
use Gvera\Helpers\events\EventDispatcher;
use Gvera\Helpers\locale\Locale;
use Gvera\Helpers\session\Session;
use Gvera\Models\ForgotPassword;
use Gvera\Models\User;

class ForgotPasswordService
{
    private $repository;
    private $entityManager;

    public function __construct()
    {
        $this->entityManager = EntityManager::getInstance();
        $this->repository = $this->entityManager->getRepository(ForgotPassword::class);
    }

    public function validateNewForgotPassword($email)
    {
        $userRepository = EntityManager::getInstance()->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $email]);
        $activeForgotPass = $this->repository->findOneBy(['user' => $user, 'alreadyUsed' => false]);

        return !isset($activeForgotPass);
    }

    /**
     * @param $email
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function generateNewForgotPassword($email) {
        $userRepository = EntityManager::getInstance()->getRepository(User::class);

        $user = $userRepository->findOneBy(['email' => $email]);
        if (!isset($user)) {
            throw new \Exception(Locale::getLocale('Email do not correspond to a valid user'));
        }

        $dateTime = new \DateTime();
        $newKey = md5($dateTime->format('Y-m-d H:i:s') . $user->getId());
        $forgotPassword = new ForgotPassword($user, $newKey);

        $this->entityManager->merge($forgotPassword);
        $this->entityManager->flush();

        EventDispatcher::dispatchEvent(
            ForgotPasswordCreatedEvent::FORGOT_PASSWORD_EVENT,
            new ForgotPasswordCreatedEvent($email)
        );
    }

    /**
     * @param $key
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function useForgotPassword($key) {
        $fp = $this->repository->findOneBy(['forgotPasswordKey' => $key]);
        if (!isset($fp)) {
            throw new \Exception(Locale::getLocale('Forgot Password was never generated'));
        }

        if($fp->getAlreadyUsed()) {
            throw new \Exception(Locale::getLocale('Forgot Password was already used'));
        }

        $fp->setAlreadyUsed(true);
        $this->entityManager->persist($fp);
        $this->entityManager->flush();
        Session::set('forgot_password', $key);
    }

    /**
     * @param $key
     * @param $newPassword
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function regeneratePassword($key, $newPassword) {
        $fp = $this->repository->findOneBy(['forgotPasswordKey' => $key]);
        $user = $fp->getUser();
        $user->setPassword($newPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

}
