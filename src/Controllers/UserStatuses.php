<?php
namespace Gvera\Controllers;

use Exception;
use Gvera\Commands\CreateUserStatusCommand;
use Gvera\Exceptions\InvalidHttpMethodException;
use Gvera\Helpers\http\HttpRequest;
use Gvera\Helpers\http\Response;
use Gvera\Helpers\locale\Locale;
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
class UserStatuses extends GvController
{
    public function index()
    {
        $this->httpResponse->response(new Response('userstatuses'));
    }

    /**
     * @throws Exception
     */
    public function create()
    {
        $userService = $this->getUserService();
        if (!$this->httpRequest->isPost()) {
            throw new InvalidHttpMethodException(
                'Http method used mismatch with expected',
                ['used' => $_SERVER['REQUEST_METHOD'], 'expected' => HttpRequest::POST]
            );
        }

        if (!$userService->isUserLoggedIn() ||
            $userService->getSessionUserRole() < $userService::MODERATOR_ROLE_PRIORITY
        ) {
            throw new Exception(Locale::getLocale('User must be logged in and have the correct rights'));
        }

        $newUserStatusCommand = new CreateUserStatusCommand(
            $this->httpRequest->getParameter('name'),
            $this->diContainer->get("entityManager")
        );
        $newUserStatusCommand->execute();
    }
}
