<?php

namespace App\Security\Voter;

use App\Entity\Sortie;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserPermissionVoter extends Voter
{
    const MODIFIER_SORTIE = 'MODIFIER_SORTIE';
    const INSCRIRE_SORTIE = 'INSCRIRE_SORTIE';
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::MODIFIER_SORTIE, self::INSCRIRE_SORTIE])
            && $subject instanceof Sortie;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        $sortie = $subject;

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        switch ($attribute) {
            case self::MODIFIER_SORTIE:
                if ($sortie->getOrganisateur() === $user){
                    return true;
                }
                break;
            case self::INSCRIRE_SORTIE:
                if ($sortie->getParticipants()->contains($user)){
                    return true;
                }
                break;
        }
        return false;
    }
}
