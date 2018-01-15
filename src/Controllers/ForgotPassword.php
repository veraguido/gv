<?php
namespace Gvera\Controllers;


use Gvera\Helpers\session\Session;
use Gvera\Services\ForgotPasswordService;

class ForgotPassword extends GvController
{
    public function index()
    {
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function new()
    {
        if (!$this->httpRequest->isPost()) {
            $this->redirectToIndex();
        }

        $forgotPassService = new ForgotPasswordService();
        $email = $this->httpRequest->getParameter('email');
        if ($forgotPassService->validateNewForgotPassword($email)) {
            $forgotPassService->generateNewForgotPassword($email);
        } else {
            $this->redirectToIndex();
        }
    }

    public function use() {

        $forgotPassService = new ForgotPasswordService();
        try {
            $forgotPassService->useForgotPassword($this->httpRequest->getParameter('key'));
        } catch (\Exception $e) {
            $this->redirectToIndex();
        }

    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function regenerate() {
        if (!(Session::get('forgot_password')) || !$this->httpRequest->isPost()) {
            Session::destroy();
            $this->redirectToIndex();
        }

        $forgotPassService = new ForgotPasswordService();
        $forgotPassService->regeneratePassword(Session::get('forgot_password'), $this->httpRequest->getParameter('new_password'));
    }

    private function redirectToIndex() {
        $this->httpResponse->redirect('/forgotpassword');
    }

}