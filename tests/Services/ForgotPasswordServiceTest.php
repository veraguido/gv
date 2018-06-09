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
        $entityManager = $this->getMockedEntityManager($repo, true);

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
        
        $repo2 = $this->getMockedRepository($forgotPassword);

        $em = $this->getMockedEntityManager($repo2);

        $forgotPassService = new ForgotPasswordService($em);
        $session = $this->getMockedSession();

        $forgotPassService->session = $session;

        $this->assertFalse($forgotPassword->getAlreadyUsed());
        $forgotPassService->useForgotPassword('asd');
        $this->assertTrue($forgotPassword->getAlreadyUsed());
    }

    private function getMockedRepository($forgotPass)
    {
        $repository = $this->createMock(EntityRepository::class);
        $repository->expects($this->any())
                ->method('findOneBy')
                ->willReturn($forgotPass);

        return $repository;
    }

    private function getMockedEntityManager($repo, $isExtended = false)
    {
        $doctrineEm = $this->createMock(Doctrine\ORM\EntityManager::class);
        $doctrineEm->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(ForgotPassword::class))
            ->willReturn($repo);
        
        if($isExtended === true) {
            $doctrineEm->expects($this->once())
            ->method('merge');

        $doctrineEm->expects($this->once())
            ->method('flush');
        }

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())
            ->method('getInstance')
            ->willReturn($doctrineEm);

        return $entityManager;
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