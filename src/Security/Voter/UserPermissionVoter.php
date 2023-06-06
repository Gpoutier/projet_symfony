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
    const DESINSCRIRE_SORTIE = 'DESINSCRIRE_SORTIE';
    const CREER_SORTIE = 'CREER_SORTIE';
    const ANNULER_SORTIE = 'ANNULER_SORTIE';


    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::MODIFIER_SORTIE, self::INSCRIRE_SORTIE, self::DESINSCRIRE_SORTIE, self::CREER_SORTIE, self::ANNULER_SORTIE])
            && $subject instanceof Sortie;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        /** @var  Sortie $sortie */
        $sortie = $subject;
        $dateActuelle = new \DateTime("now");
        $dateDateLimiteInscriptionFormatee = $sortie->getDateLimiteInscription()->format('Y-m-d H:i:s');
        $dateHeureDebutFormatee = $sortie->getDateHeureDebut()->format('Y-m-d H:i:s');



        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        switch ($attribute) {
            case self::CREER_SORTIE:
                if ($user instanceof UserInterface){
                    return true;
                }
                break;
            case self::MODIFIER_SORTIE:
                if ($sortie->getOrganisateur() === $user){
                    return true;
                }
                break;
            case self::INSCRIRE_SORTIE:
                if (!$sortie->getParticipants()->contains($user) && $sortie->getEtat()->getLibelle() == "Ouverte" && $dateDateLimiteInscriptionFormatee < $dateActuelle){
                    return true;
                }
                break;
            case self::DESINSCRIRE_SORTIE:
                if ($sortie->getParticipants()->contains($user) && $dateHeureDebutFormatee < $dateActuelle){
                    return true;
                }
                break;
            case self::ANNULER_SORTIE:
                if ($sortie->getOrganisateur() === $user && $sortie->getEtat()->getLibelle() == "Ouverte"){
                    return true;
                }
                break;
        }
        return false;
    }
}
