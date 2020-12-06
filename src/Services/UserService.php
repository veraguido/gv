<?php namespace Gvera\Services;

use Gvera\Commands\CreateNewUserCommand;
use Gvera\Exceptions\BadRequestException;
use Gvera\Exceptions\NotFoundException;
use Gvera\Helpers\entities\GvEntityManager;
use Gvera\Helpers\http\HttpRequest;
use Gvera\Helpers\locale\Locale;
use Gvera\Helpers\session\Session;
use Gvera\Helpers\validation\EmailValidationStrategy;
use Gvera\Helpers\validation\ValidationService;
use Gvera\Models\User;
use Gvera\Models\UserRole;
use Gvera\Models\UserRoleAction;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * Service Class Doc Comment
 *
 * @category Class
 * @package  src/services
 * @author    Guido Vera
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.github.com/veraguido/gv
 *
 */
class UserService
{
    const MODERATOR_ROLE_PRIORITY = 5;

    public GvEntityManager $entityManager;
    public Session $session;
    private ValidationService $validationService;

    public function __construct(GvEntityManager $entityManager, Session $session, ValidationService $validationService)
    {
        $this->validationService = $validationService;
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    /**
     * @param $email
     * @return bool
     * @throws \Exception
     */
    public function validateEmail($email)
    {
        return $this->validationService->validate($email, [new EmailValidationStrategy()]);
    }

    /**
     * @param $plainPassword
     * @return string
     */
    public function generatePassword($plainPassword)
    {
        return password_hash($plainPassword, PASSWORD_BCRYPT);
    }

    /**
     * @param $plainPassword
     * @param $hash
     * @return bool
     */
    public function validatePassword($plainPassword, $hash)
    {
        return password_verify($plainPassword, $hash);
    }

    /**
     * @param $username
     * @param $password
     * @throws \Exception
     */
    public function login($username, $password)
    {
        $repository = $this->entityManager->getRepository(User::class);
        $user = $repository->findOneBy(['username' => $username]);

        if (!$user
            || $user->getUsername() != $username
            || !$user->getEnabled()
            || !$this->validatePassword($password, $user->getPassword())) {
            throw new \Exception(Locale::getLocale('bad_request'));
        }

        $this->session->set(
            'user',
            [
                'id' => $user->getId(),
                'username' => $username,
                'userEmail' => $user->getEmail(),
                'role' => $user->getRole()->getRolePriority()
            ]
        );
    }

    public function logout()
    {
        $this->session->destroy();
    }

    public function isUserLoggedIn()
    {
        return $this->session->get('user') != null;
    }

    /**
     * @return int
     */
    public function getUserRole()
    {
        return $this->session->get('user') != null ? $this->session->get('user')['role'] : false;
    }

    /**
     * @param User $user
     * @param string $userRoleActionName
     * @return bool
     */
    public function userCan(?User $user, string $userRoleActionName):bool
    {
        if (null === $user) {
            return false;
        }

        $action = $this->entityManager->getRepository(UserRoleAction::class)
            ->findOneBy(['name' => $userRoleActionName]);


        if (null == $action) {
            return false;
        }

        return $user->getRole()->getUserRoleActions()->contains($action);
    }

    /**
     * @param HttpRequest $httpRequest
     * @param CreateNewUserCommand $command
     * @param UserRole $role
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createFromRequest(HttpRequest $httpRequest, CreateNewUserCommand $command, UserRole $role)
    {
        $command->setEmail($httpRequest->getParameter('email'));
        $command->setName($httpRequest->getParameter('username'));
        $hashedPassword = $this->generatePassword($httpRequest->getParameter('password'));
        $command->setPassword($hashedPassword);
        $command->setRole($role);
        $command->execute();

        $this->entityManager->flush();
    }

    /**
     * @param HttpRequest $request
     * @throws BadRequestException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateFromRequest(HttpRequest $request)
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $id = intval($request->getParameter('user_id'));
        $newPassword = $this->generatePassword($request->getParameter('password'));
        $user = $userRepository->find($id);
        $user->setPassword($newPassword);

        $this->entityManager->merge($user);
        $this->entityManager->flush();
    }

    /**
     * @param HttpRequest $request
     * @throws BadRequestException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function toggleUser(HttpRequest $request)
    {
        $userRepository = $this->entityManager->getRepository(User::class);

        $id = intval($request->getParameter('user_id'));
        $user = $userRepository->find($id);
        $user->setEnabled(!$user->getEnabled());
        $this->entityManager->merge($user);
        $this->entityManager->flush();
    }
}
