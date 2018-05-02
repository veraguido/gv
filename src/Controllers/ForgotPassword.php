<?php
namespace Gvera\Controllers;

use Gvera\Helpers\session\Session;

/**
 * Class ForgotPassword
 * @package Gvera\Controllers
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

        $forgotPassService = $this->diContainer->get("forgotPasswordService");
        $email = $this->httpRequest->getParameter('email');
        if ($forgotPassService->validateNewForgotPassword($email)) {
            $forgotPassService->generateNewForgotPassword($email);
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
        if (!(Session::get('forgot_password')) || !$this->httpRequest->isPost()) {
            Session::destroy();
            $this->redirectToIndex();
        }

        $forgotPasswordService = $this->diContainer->get("forgotPasswordService");
        $forgotPasswordService->regeneratePassword(
            Session::get('forgot_password'),
            $this->httpRequest->getParameter('new_password')
        );
    }

    private function redirectToIndex()
    {
        $this->httpResponse->redirect('/forgotpassword');
    }
}
