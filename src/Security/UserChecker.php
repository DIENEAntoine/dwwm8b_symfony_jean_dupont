<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if ( !$user instanceof User ) 
        {
            return;
        }
        
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if ( !$user instanceof User ) 
        {
            return;
        }

        // user account is expired, the user may be notified
        if ( ! $user->isIsVerified()) 
        {
            throw new CustomUserMessageAccountStatusException("Vous devez vérifer votre compte par email avant de vous connecter!");
        }
    }
}