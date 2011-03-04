<?php

namespace FOQ\AlbumBundle;

use FOS\UserBundle\Model\User;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityHelper
{
    protected $securityContext;

    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    public function getUser()
    {
        if ($token = $this->securityContext->getToken()) {
            if ($user = $token->getUser()) {
                if ($user instanceof User) {
                    return $user;
                }
            }
        }
    }
}
