<?php
namespace Gvera\Commands;

use Gvera\Helpers\entities\EntityManager;
use Gvera\Models\UserStatus;

/**
 * Command Class Doc Comment
 *
 * @category Class
 * @package  src/commands
 * @author    Guido Vera
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.github.com/veraguido/gv
 *
 */
class CreateUserStatusCommand implements ICommand
{

    private $statusName;


    public function __construct($statusName)
    {
        $this->statusName = $statusName;
        $this->entityManager = EntityManager::getInstance();
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function execute()
    {
        $userStatus = new UserStatus();
        $userStatus->setStatus($this->statusName);
        $this->entityManager->persist($userStatus);
        $this->entityManager->flush();
    }
}
