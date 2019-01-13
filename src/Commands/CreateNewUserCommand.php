<?php
namespace Gvera\Commands;

use Doctrine\ORM\Mapping\Entity;
use Gvera\Events\UserRegisteredEvent;
use Gvera\Helpers\entities\GvEntityManager;
use Gvera\Helpers\events\EventDispatcher;
use Gvera\Models\User;
use Gvera\Models\UserRole;
use Gvera\Models\UserStatus;
use Gvera\Commands\CommandInterface;
use Gvera\Helpers\config\Config;
use Gvera\Exceptions\MissingArgumentsException;

/**
 * Command Class Doc Comment
 *
 * @category Class
 * @package  src/commands
 * @author    Guido Vera
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.github.com/veraguido/gv
 */
class CreateNewUserCommand implements CommandInterface
{
    private $name;
    private $password;
    private $email;
    private $entityManager;
    private $config;

    public function __construct(Config $config, GvEntityManager $entityManager)
    {
        $this->config = $config;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function execute()
    {
        if (!$this->isCommandValid()) {
            throw new MissingArgumentsException(
                'The command you are trying to run is missing mandatory arguments.'
            );
        }

        if ($this->userExists($this)) {
            throw new \Exception("There was a problem registering the user");
        }

        $status = $this->entityManager->getRepository(UserStatus::class)->findOneBy(['status' => 'active']);
        $role = $this->entityManager->getRepository(UserRole::class)->findOneBy(['name' => 'user']);


        $user = $this->createNewUser($role, $status);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        EventDispatcher::dispatchEvent(
            UserRegisteredEvent::USER_REGISTERED_EVENT,
            new UserRegisteredEvent(
                $user->getUsername(),
                $user->getEmail(),
                $this->config->getConfigItem('devmode')
            )
        );
    }

    /**
     * @return User
     */
    private function createNewUser($role, $status)
    {
        $user = new User();
        $user->setUsername($this->name);
        $user->setPassword($this->password);
        $user->setEmail($this->email);
        $user->setStatus($status);
        $user->setRole($role);

        return $user;
    }

    /**
     * check is mandatory fields are setup
     *
     * @return bool
     */
    private function isCommandValid(): bool
    {
        return
            !empty($this->email) &&
            !empty($this->name) &&
            !empty($this->password);
    }

    /**
     * check if user already exists by email or username
     *
     * @return bool
     */
    private function userExists(): bool
    {
        $byEmail = $this->entityManager->getRepository(User::class)->findByEmail($this->email);
        $byUsername = $this->entityManager->getRepository(User::class)->findByUsername($this->name);
        
        return (!empty($byEmail) || !empty($byUsername));
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }
}
