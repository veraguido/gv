<?php
namespace Gvera\Commands;

use Gvera\Helpers\entities\GvEntityManager;
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
class CreateUserStatusCommand implements CommandInterface
{

    private $statusName;
    private $entityManager;

    public function __construct($statusName, GvEntityManager $entityManager)
    {
        $this->statusName = $statusName;
        $this->entityManager = $entityManager;
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
