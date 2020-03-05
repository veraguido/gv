<?php

use PHPUnit\Framework\TestCase;
use Gvera\Services\ForgotPasswordService;
use Doctrine\ORM\EntityRepository;
use Gvera\Models\User;
use Gvera\Models\ForgotPassword;
use Gvera\Helpers\session\Session;
use Gvera\Helpers\entities\GvEntityManager;

class ForgotPasswordServiceTest extends TestCase
{

    private $forgotPasswordService;

    /**
     * @test
     */
    public function validateForgotPassword()
    {
        $user = new User();
        $user->setEmail("asd@aasd.com");

        $forgotPassword = new ForgotPassword($user, 'asd');
        
        $repo2 = $this->getMockedRepository($forgotPassword);

        $em = $this->getMockedEntityManager($repo2);
        $forgotPassService = new ForgotPasswordService($em);

        $this->assertFalse($forgotPassService->validateNewForgotPassword($user));
    }

    /**
     * @test
     */
    public function generateNewForgotPasswordTest()
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $forgotPassword = new ForgotPassword($user, 'asd');

        $repo = $this->getMockedRepository($forgotPassword);
        $entityManager = $this->getMockedEntityManager($repo, ['flush', 'merge']);

        $forgotPassService = new ForgotPasswordService($entityManager);
        $forgotPassService->generateNewForgotPassword($user);
    }

    /**
     * @test
     */
    public function useForgotPassword()
    {
        $user = new User();
        $user->setEmail("asd@aasd.com");

        $forgotPassword = new ForgotPassword($user, 'asd');
        
        $repository = $this->getMockedRepository($forgotPassword);

        $entityManager = $this->getMockedEntityManager($repository);

        $forgotPassService = new ForgotPasswordService($entityManager);
        $session = $this->getMockedSession();

        $forgotPassService->session = $session;

        $this->assertFalse($forgotPassword->getAlreadyUsed());
        $forgotPassService->useForgotPassword('asd');
        $this->assertTrue($forgotPassword->getAlreadyUsed());
    }

    /**
     * @test
     */
    public function regeneratePassword()
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('setPassword')
            ->willReturn(true);

        $forgotPassword = new ForgotPassword($user, 'asd');    

        $repo = $this->getMockedRepository($forgotPassword);
        $entityManager = $this->getMockedEntityManager($repo, ['flush', 'persist']);

        $forgotPassService = new ForgotPasswordService($entityManager);
        $forgotPassService->regeneratePassword('asd', 'newPass');
    }

    private function getMockedRepository($forgotPass)
    {
        $repository = $this->createMock(EntityRepository::class);
        $repository->expects($this->any())
                ->method('findOneBy')
                ->willReturn($forgotPass);

        return $repository;
    }

    private function getMockedEntityManager($repo, $additionalChecks = [])
    {
        $gvEntityManager = $this->createMock(GvEntityManager::class);
        $gvEntityManager->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(ForgotPassword::class))
            ->willReturn($repo);
        
        foreach ($additionalChecks as $check) {
            $gvEntityManager->expects($this->once())
                ->method($check);
        }

        return $gvEntityManager;
    }

    private function getMockedSession()
    {
        $session = $this->createMock(Session::class);
        $session->expects($this->any())
            ->method('set')
            ->with('forgot_password')
            ->willReturn(true);
        
        return $session;
    }
}