<?php

namespace Gvera\Services;

use Gvera\Helpers\entities\GvEntityManager;
use Gvera\Models\BasicAuthenticationDetails;
use Gvera\Models\User;

class BasicAuthenticationService
{

    private $authDetails;

    /**
     * @var GvEntityManager
     */
    private $entityManager;
    /**
     * @var UserService
     */
    private $userService;

    /**
     * BasicAuthenticationService constructor.
     * @param GvEntityManager $entityManager
     * @param UserService $userService
     */
    public function __construct(GvEntityManager $entityManager, UserService $userService)
    {
        $this->entityManager = $entityManager;
        $this->userService = $userService;
    }

    public function requireAuth(BasicAuthenticationDetails $details): bool
    {
        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['username' => $details->getUsername()]);

        if (null === $user) {
            return false;
        }

        return $this->userService->validatePassword($details->getPassword(), $user->getPassword());
    }
}
