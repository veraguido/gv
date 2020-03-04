<?php
namespace Gvera\Controllers;

use Gvera\Helpers\http\JSONResponse;
use Gvera\Helpers\http\Response;
use Gvera\Helpers\http\TransformerResponse;
use Gvera\Helpers\locale\Locale;
use Gvera\Helpers\transformers\UserTransformer;
use Gvera\Models\User;

/**
 * @OA\Info(title="My First API", version="0.1")
 */

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
    /**
     * @OA\Get(
     *     path="/examples",
     *     @OA\Response(response="200", description="An example resource")
     * )
     */
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
        $this->httpResponse->response(new Response("trough routes.yml"));
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
        $this->httpResponse->response(Locale::getLocale("Hello world"));
    }

    public function authorization()
    {
        $user = $this->getEntityManager()->getRepository(User::class)->findOneBy(['username' => 'admin']);
        $service = $this->getUserService();
        $this->httpResponse->response(['can all' => $service->userCan($user, 'all')]);
    }

    /**
     * @throws \Gvera\Exceptions\NotImplementedMethodException
     */
    public function transformer()
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(User::class);
        $user = $repository->findOneBy(['username' => 'asda']);

        $this->httpResponse->response(new TransformerResponse(new UserTransformer($user)));
    }

    public function authTest()
    {
        $this->httpResponse->response($this->checkAuthorization());
    }

    public function basicAuth()
    {
        try {
            $this->checkApiAuthentication();
        } catch (\Throwable $e) {
            $content = ['message' => $e->getMessage()];
            $this->httpResponse->response(new JSONResponse($content, Response::HTTP_RESPONSE_UNAUTHORIZED));
        }
    }
}
