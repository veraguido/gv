<?php
namespace Gvera\Controllers;

use Gvera\Cache\Cache;
use Gvera\Cache\RedisCache;
use Gvera\Commands\CreateNewUserCommand;
use Gvera\Commands\LoginCommand;
use Gvera\Helpers\locale\Locale;
use Gvera\Helpers\session\Session;
use Gvera\Helpers\transformers\UserTransformer;
use Gvera\Models\User;
use Gvera\Services\UserService;
use Gvera\Helpers\dependencyInjection\DIContainer;
use Gvera\Helpers\annotations\HttpMethodAnnotation;

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
        $this->viewParams = ['csrf' => $this->generateCSRFToken()];
    }

    /**
     * @throws \Exception
     * @method $this->diContainer->get('loginCommand');
     *
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
        $userService = $this->getUserService();
        $userService->logout();
    }

    public function asd()
    {
        $this->httpResponse->response("trough routes.yml");
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     * @httpMethod("POST")
     */
    public function qwe()
    {
        $this->validateCSRFToken($this->httpRequest->getParameter('csrf'));
        $registerUserCommand = $this->getCreateNewUserCommand();
        $registerUserCommand
            ->setName($this->httpRequest->getParameter('username'))
            ->setEmail($this->httpRequest->getParameter('email'))
            ->setPassword($this->httpRequest->getParameter('password'));
                
        $registerUserCommand->execute();

        $this->httpRequest->moveFileToDirectory("/tmp/", 'avatar-pic');
    }

    public function lorep()
    {
        $this->httpResponse->reponse(Locale::getLocale("Hello world"));
    }

    /**
     * Before executing this method, be sure to have a user with username 'asda' in your database
     * @httpMethod("GET")
     */
    public function transformer()
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(User::class);
        $user = $repository->findOneBy(['username' => 'asda']);

        $this->httpResponse->asJson();
        $this->httpResponse->response(new UserTransformer($user));
    }

    public function authTest()
    {
        $this->httpResponse->response($this->checkAuthorization());
    }
}
