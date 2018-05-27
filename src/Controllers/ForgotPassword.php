<?php
namespace Gvera\Controllers;

use Gvera\Helpers\session\Session;

/**
 * Class ForgotPassword
 * @package Gvera\Controllers
 * @method getForgotPasswordService
 * @Inject session
 */
class ForgotPassword extends GvController
{
    public function index()
    {
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \ReflectionException
     */
    public function new()
    {
        if (!$this->httpRequest->isPost()) {
            $this->redirectToIndex();
        }

        $forgotPassService = $this->getForgotPasswordService();

        $email = $this->httpRequest->getParameter('email');

        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $email]);

        if ($forgotPassService->validateNewForgotPassword($user)) {
            $forgotPassService->generateNewForgotPassword($user);
        } else {
            $this->redirectToIndex();
        }
    }

    /**
     * @throws \ReflectionException
     */
    public function use()
    {
        $forgotPasswordService = $this->diContainer->get("forgotPasswordService");
        try {
            $forgotPasswordService->useForgotPassword($this->httpRequest->getParameter('key'));
        } catch (\Exception $e) {
            $this->redirectToIndex();
        }
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \ReflectionException
     */
    public function regenerate()
    {
        if (!($this->session->get('forgot_password')) || !$this->httpRequest->isPost()) {
            $this->session->destroy();
            $this->redirectToIndex();
        }

        $forgotPasswordService = $this->diContainer->get("forgotPasswordService");
        $forgotPasswordService->regeneratePassword(
            $this->session->get('forgot_password'),
            $this->httpRequest->getParameter('new_password')
        );
    }

    private function redirectToIndex()
    {
        $this->httpResponse->redirect('/forgotpassword');
    }
}
