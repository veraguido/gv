<?php namespace Gvera\Services;

use Gvera\Helpers\entities\EntityManager;
use Gvera\Helpers\locale\Locale;
use Gvera\Helpers\session\Session;
use Gvera\Helpers\validation\EmailValidationStrategy;
use Gvera\Helpers\validation\ValidationService;
use Gvera\Models\User;

/**
 * Service Class Doc Comment
 *
 * @category Class
 * @package  src/services
 * @author    Guido Vera
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.github.com/veraguido/gv
 * @Inject entityManager
 * @Inject session
 *
 */
class UserService
{
    const MODERATOR_ROLE_PRIORITY = 5;

    public $entityManager;
    public $session;

    public function validateEmail($email)
    {
        return ValidationService::validate($email, [new EmailValidationStrategy()]);
    }

    /**
     * @return string
     */
    public function generatePassword($plainPassword)
    {
        return password_hash($plainPassword, PASSWORD_BCRYPT);
    }

    /**
     * @return boolean
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
        $repository = $this->entityManager->getInstance()->getRepository(User::class);
        $user = $repository->findOneBy(['username' => $username]);

        if ($user && $user->getUsername() == $username && $this->validatePassword($password, $user->getPassword())) {
            $this->session->set(
                'user',
                [
                    'username' => $username,
                    'userEmail' => $user->getEmail(),
                    'role' => $user->getRole()->getRolePriority()
                ]
            );
        } else {
            throw new \Exception('user or password are incorrect');
        }
    }

    public function logout()
    {
        $this->session->unset('user');
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
}
