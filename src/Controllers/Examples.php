<?php
namespace Gvera\Controllers;

use Gvera\Exceptions\NotFoundException;
use Gvera\Exceptions\NotImplementedMethodException;
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
     * @throws NotFoundException
     * @throws \ReflectionException
     */
    public function login()
    {
        $this->httpRequest->validate();
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

        $file = $this->httpRequest->getFileByPropertyName("avatar-pic");

        $this->httpRequest->moveFileToDirectory("/tmp/", $file);
    }

    public function lorep()
    {
        $this->httpResponse->response(Locale::getLocale("Hello world"));
    }

    public function authorization()
    {
        $user = $this->getEntityManager()->getRepository(User::class)->findOneBy(['username' => 'mod']);
        $service = $this->getUserService();
        $this->httpResponse->response(new JSONResponse(['can all' => $service->userCan($user, 'all')]));
    }

    /**
     * @throws NotImplementedMethodException
     */
    public function transformer()
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(User::class);
        $user = $repository->findOneBy(['username' => 'asda']);

        $this->httpResponse->response(new TransformerResponse(new UserTransformer($user)));
    }

    public function basicAuth()
    {
        try {
            $this->mustPassBasicAuthentication();
        } catch (\Throwable $e) {
            $content = ['message' => $e->getMessage()];
            $this->httpResponse->response(new JSONResponse($content, Response::HTTP_RESPONSE_UNAUTHORIZED));
        }
    }

    public function jwt()
    {
        try {
            $this->mustPassTokenAuthentication();
        } catch (\Exception $e) {
            $content = ['message' => $e->getMessage()];
            $this->httpResponse->response(new JSONResponse($content, Response::HTTP_RESPONSE_UNAUTHORIZED));
        }
    }
}
