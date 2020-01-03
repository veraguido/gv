<?php


namespace Services;


use Doctrine\ORM\EntityRepository;
use Gvera\Helpers\entities\GvEntityManager;
use Gvera\Helpers\session\Session;
use Gvera\Helpers\validation\ValidationService;
use Gvera\Models\BasicAuthenticationDetails;
use Gvera\Models\User;
use Gvera\Services\BasicAuthenticationService;
use PHPUnit\Framework\TestCase;

class BasicAuthenticationServiceTest extends TestCase
{
    /**
     * @test
     */
    public function testRequireAuth()
    {
        $user = new User();
        $user->setEmail("asd@aasd.com");
        $user->setUsername("admin");
        $user->setPassword(password_hash("admin", PASSWORD_BCRYPT));

        $repo = $this->createMock(EntityRepository::class);
        $repo->expects($this->any())
            ->method('findOneBy')
            ->willReturn($user);

        $gvEntityManager = $this->createMock(GvEntityManager::class);
        $gvEntityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($repo);

        $userService = new \Gvera\Services\UserService($gvEntityManager, new Session(), new ValidationService());
        $basicAuthService = new BasicAuthenticationService($gvEntityManager, $userService);

        $this->assertTrue(
            $basicAuthService->requireAuth(
                new BasicAuthenticationDetails(
                    $user->getUsername(), "admin"
                )
            )
        );

        $this->assertFalse(
            $basicAuthService->requireAuth(
                new BasicAuthenticationDetails(
                    $user->getUsername(), "invalidPass"
                )
            )
        );
    }
}