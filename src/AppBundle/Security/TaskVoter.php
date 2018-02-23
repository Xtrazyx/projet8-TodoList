<?php
/**
 * Created by PhpStorm.
 * User: Xtrazyx
 * Date: 21/02/2018
 * Time: 15:44
 */

namespace AppBundle\Security;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TaskVoter extends Voter
{
    const DELETE = 'delete';
    const EDIT = 'edit';

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     * @codeCoverageIgnore
     */
    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::DELETE, self::EDIT))) {
            return false;
        }

        // only vote on Task objects inside this voter
        if (!$subject instanceof Task) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false; // @codeCoverageIgnore
        }

        /**
         * @var Task $task
         */
        $task = $subject;

        switch ($attribute) {
            case self::DELETE:
                return $this->canDelete($task, $user);
            case self::EDIT:
                return $this->canEdit($task, $user);
        }

        return false; // @codeCoverageIgnore
    }

    private function canDelete(Task $task, User $user)
    {
        // if they can edit, they can delete
        if ($this->canEdit($task, $user)) {
            return true;
        }

        return false;
    }

    private function canEdit(Task $task, User $user)
    {
        if($task->getUser() === $user){
            return $user === $task->getUser();
        }

        return false;
    }
}
