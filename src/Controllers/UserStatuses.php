<?php
/**
 * Created by PhpStorm.
 * User: guido
 * Date: 1/6/18
 * Time: 1:53 PM
 */

namespace Gvera\Controllers;


use Gvera\Commands\CreateUserStatusCommand;
use Gvera\Helpers\locale\Locale;
use Gvera\Services\UserService;

class UserStatuses extends GvController
{
    public function index()
    {

    }

    /**
     * @throws \Exception
     */
    public function create()
    {
        if (!$this->httpRequest->isPost()) {
            throw new \Exception('/statuses/create must be a post request.');
        }

        if (!UserService::isUserLoggedIn()) {
            throw new \Exception(Locale::getLocale('User must be logged in'));
        }

        $newUserStatusCommand = new CreateUserStatusCommand($this->httpRequest->getParameter('name'));
        $newUserStatusCommand->execute();

    }

    public function update()
    {

    }

    public function delete()
    {

    }
}