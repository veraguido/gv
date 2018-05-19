<?php
namespace Gvera\Controllers;

use Gvera\Cache\Cache;
use Gvera\Cache\RedisCache;
use Gvera\Commands\CreateNewUserCommand;
use Gvera\Commands\LoginCommand;
use Gvera\Helpers\locale\Locale;
use Gvera\Helpers\session\Session;
use Gvera\Services\UserService;
use Gvera\Helpers\dependencyInjection\DIContainer;

/**
 * Controller Class Doc Comment
 *
 * @category Class
 * @package  src/controllers
 * @author    Guido Vera
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.github.com/veraguido/gv
 * @method getEntityManager()
 */
class Examples extends GvController
{
    public function index()
    {
        //echo phpinfo();
        //$this->httpResponse->redirect("/Cars/tiju");
        //$this->httpResponse->notFound();
    }

    /**
     * @throws \Exception
     * @method $this->diContainer->get('loginCommand');
     */
    public function login()
    {
        $loginCommand = $this->getLoginCommand();
        $loginCommand->setUsername($this->httpRequest->getParameter('username'));
        $loginCommand->setPassword($this->httpRequest->getParameter('password'));

        $loginCommand->execute();
    }

    public function logout()
    {
        $userService = new UserService();
        $userService->logout();
    }

    public function asd()
    {
        echo "trough routes.yml";
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function qwe()
    {
        if ($this->httpRequest->isPost()) {
            $registerUserCommand = new CreateNewUserCommand(
                $this->httpRequest->getParameter('username'),
                $this->httpRequest->getParameter('password'),
                $this->httpRequest->getParameter('email'),
                $this->getEntityManager()
            );

            $registerUserCommand->execute();

            $this->httpRequest->moveFileToDirectory("/tmp/", 'avatar-pic');
        }
    }

    public function lorep()
    {
        echo Locale::getLocale("Hello world");
    }
}
