<?php
namespace Gvera\Controllers;

use Gvera\Commands\CreateUserStatusCommand;
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
        echo 'userstatuses';
    }

    /**
     * @throws \Exception
     */
    public function create()
    {
        if (!$this->httpRequest->isPost()) {
            throw new \Exception('/statuses/create must be a post request.');
        }

        if (!UserService::isUserLoggedIn() || UserService::getUserRole() < UserService::MODERATOR_ROLE_PRIORITY) {
            throw new \Exception(Locale::getLocale('User must be logged in and have the correct rights'));
        }

        $newUserStatusCommand = new CreateUserStatusCommand($this->httpRequest->getParameter('name'));
        $newUserStatusCommand->execute();
    }

    public function delete()
    {
    }
}
