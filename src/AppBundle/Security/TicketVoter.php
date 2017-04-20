<?php

namespace AppBundle\Security;

use AppBundle\Entity\Ticket;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TicketVoter extends Voter
{
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        if (($attribute !== self::EDIT) && (!$subject instanceof Ticket)) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }
        /** @var Ticket $ticket */
        $ticket = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($ticket, $user);
        }
    }

    private function canEdit(Ticket $ticket, User $user)
    {
        if (!$userOrder = $ticket->getUserOrder()) {
            return true;
        }

        return $user === $userOrder->getUser();
    }
}
