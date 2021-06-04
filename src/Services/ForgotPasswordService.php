<?php

namespace Gvera\Services;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Gvera\Events\ForgotPasswordCreatedEvent;
use Gvera\Helpers\entities\GvEntityManager;
use Gvera\Helpers\events\EventDispatcher;
use Gvera\Models\ForgotPassword;
use Gvera\Models\User;

/**
 * @Inject session
 */
class ForgotPasswordService
{
    private $repository;
    private GvEntityManager $entityManager;

    public function __construct(GvEntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(ForgotPassword::class);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function validateNewForgotPassword(User $user): bool
    {
        $activeForgotPass = $this->repository->findOneBy(['user' => $user, 'alreadyUsed' => false]);

        return !isset($activeForgotPass);
    }

    /**
     * @param User $user
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function generateNewForgotPassword(User $user)
    {
        $dateTime = new \DateTime();
        $newKey = md5($dateTime->format('Y-m-d H:i:s') . $user->getId());
        $forgotPassword = new ForgotPassword($user, $newKey);

        $this->entityManager->merge($forgotPassword);
        $this->entityManager->flush();

        EventDispatcher::dispatchEvent(
            ForgotPasswordCreatedEvent::FORGOT_PASSWORD_EVENT,
            new ForgotPasswordCreatedEvent($user->getEmail())
        );
    }

    /**
     * @param $key
     * @throws OptimisticLockException
     * @throws \Exception
     */
    public function useForgotPassword($key)
    {
        $forgotPassword = $this->repository->findOneBy(['forgotPasswordKey' => $key]);
        if (!isset($forgotPassword)) {
            throw new \Exception('Forgot Password was never generated');
        }

        if ($forgotPassword->getAlreadyUsed()) {
            throw new \Exception('Forgot Password was already used');
        }

        $forgotPassword->setAlreadyUsed(true);
        $this->entityManager->persist($forgotPassword);
        $this->entityManager->flush();
        $this->session->set('forgot_password', $key);
    }

    /**
     * @param $key
     * @param $newPassword
     * @throws OptimisticLockException
     */
    public function regeneratePassword($key, $newPassword)
    {
        $forgotPassword = $this->repository->findOneBy(['forgotPasswordKey' => $key]);
        $user = $forgotPassword->getUser();
        $user->setPassword($newPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
