<?php
namespace Gvera\Controllers;

use Gvera\Cache\Cache;
use Gvera\Cache\RedisCache;
use Gvera\Commands\CreateNewUserCommand;
use Gvera\Commands\LoginCommand;
use Gvera\Helpers\locale\Locale;
use Gvera\Helpers\session\Session;
use Gvera\Services\UserService;

/**
 * Controller Class Doc Comment
 *
 * @category Class
 * @package  src/controllers
 * @author    Guido Vera
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.github.com/veraguido/gv
 *
 */
class Examples extends GvController
{
    public function index()
    {
        //echo phpinfo();
        //$this->httpResponse->redirect("/Cars/tiju");
        //$this->httpResponse->notFound();
    }

    public function tiju()
    {
        //print_r(unserialize(Cache::getCache()->load('gv_config'))); exit;
    }

    public function tan()
    {

        Session::set("asd", 1);
        Session::toString();
        $count = Session::get('count') ? Session::get('count') : 1;

        echo $count;

        Session::set('count', ++$count);
    }

    /**
     * @throws \Exception
     */
    public function login()
    {
        $loginCommand = new LoginCommand(
            $this->httpRequest->getParameter('username'),
            $this->httpRequest->getParameter('password')
        );
        $loginCommand->execute();
    }

    public function logout()
    {
        $us = new UserService();
        $us->logout();
    }

    public function asd()
    {
        /*$user = $this->entityManager->find('Gvera\Models\UserModel', 1);
        echo '<pre>';
        var_dump($user);
        echo '</pre>';*/
        //echo print_r(Session::get('user'));
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function qwe()
    {
        /*echo '<pre>';
        var_dump(EntityManager::getInstance()->getRepository(User::class)->find(1)->getPassword());
        echo '</pre>';*/

        if ($this->httpRequest->isPost()) {
            $registerUserCommand = new CreateNewUserCommand(
                $this->httpRequest->getParameter('username'),
                $this->httpRequest->getParameter('password'),
                $this->httpRequest->getParameter('email')
            );

            $registerUserCommand->execute();
        }
    }

    public function lorep()
    {
        echo Locale::getLocale("Hello world");
    }

    public function ipsum()
    {
        throw new \Exception('Test Exception for default controller');
    }
}
