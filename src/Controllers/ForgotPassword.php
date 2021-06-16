<?php
namespace Gvera\Controllers;

use Gvera\Helpers\http\Response;
use Gvera\Models\User;
use ReflectionException;

/**
 * Class ForgotPassword
 * @package Gvera\Controllers
 * @method getForgotPasswordService
 */
class ForgotPassword extends GvController
{
    public function index()
    {
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws ReflectionException
     */
    public function new()
    {
        if (!$this->httpRequest->isPost()) {
            $this->redirectToIndex();
        }

        $forgotPassService = $this->getForgotPasswordService();
        $entityManager = $this->getEntityManager();

        $email = $this->httpRequest->getParameter('email');

        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $email]);

        if (null === $user) {
            $this->httpResponse->response(new Response('something went wrong'));
            return;
        }

        if (!$forgotPassService->validateNewForgotPassword($user)) {
            $this->redirectToIndex();
            return;
        }

        $newKey = $forgotPassService->generateNewForgotPassword($user);
        $this->httpResponse->response(new Response('Key generated successfully: ' . $newKey));
    }

    /**
     * @throws ReflectionException
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
     * @throws ReflectionException
     */
    public function regenerate()
    {
        $session = $this->getSession();
        if (!($session->get('forgot_password')) || !$this->httpRequest->isPost()) {
            $session->destroy();
            $this->redirectToIndex();
        }

        $forgotPasswordService = $this->diContainer->get("forgotPasswordService");
        $forgotPasswordService->regeneratePassword(
            $session->get('forgot_password'),
            $this->httpRequest->getParameter('new_password')
        );
    }

    private function redirectToIndex()
    {
        $this->httpResponse->redirect('/forgotpassword');
    }
}
