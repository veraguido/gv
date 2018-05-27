<?php

use PHPUnit\Framework\TestCase;
use Gvera\Helpers\entities\EntityManager;
use Gvera\Services\ForgotPasswordService;
use Doctrine\ORM\EntityRepository;
use Gvera\Models\User;
use Gvera\Models\ForgotPassword;
use Gvera\Helpers\session\Session;

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
        
        $repo2 = $this->createMock(EntityRepository::class);
        $repo2->expects($this->any())
                ->method('findOneBy')
                ->willReturn($forgotPassword);

        $doctrineEm = $this->createMock(Doctrine\ORM\EntityManager::class);

        $doctrineEm->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(ForgotPassword::class))
            ->willReturn($repo2);

        $em = $this->createMock(EntityManager::class);
        $em->expects($this->any())
            ->method('getInstance')
            ->willReturn($doctrineEm);

        $forgotPassService = new ForgotPasswordService($em);

        $this->assertFalse($forgotPassService->validateNewForgotPassword($user));
    }

    /**
     * @test
     */
    public function useForgotPassword()
    {
        $user = new User();
        $user->setEmail("asd@aasd.com");

        $forgotPassword = new ForgotPassword($user, 'asd');
        
        $repo2 = $this->createMock(EntityRepository::class);
        $repo2->expects($this->any())
                ->method('findOneBy')
                ->willReturn($forgotPassword);

        $doctrineEm = $this->createMock(Doctrine\ORM\EntityManager::class);

        $doctrineEm->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(ForgotPassword::class))
            ->willReturn($repo2);

        $em = $this->createMock(EntityManager::class);
        $em->expects($this->any())
            ->method('getInstance')
            ->willReturn($doctrineEm);

        $forgotPassService = new ForgotPasswordService($em);
        $session = $this->createMock(Session::class);
        $session->expects($this->any())
            ->method('set')
            ->with('forgot_password')
            ->willReturn(true);

        $forgotPassService->session = $session;

        $this->assertFalse($forgotPassword->getAlreadyUsed());
        $forgotPassService->useForgotPassword('asd');
        $this->assertTrue($forgotPassword->getAlreadyUsed());
    }
}