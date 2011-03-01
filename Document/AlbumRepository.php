<?php

namespace FOQ\AlbumBundle\Document;

use FOS\UserBundle\Model\User;

class AlbumRepository extends UserContentRepository
{
    public function findOneByUserAndSlug(User $user, $slug)
    {
        return $this->findOneBy(array('user.$id' => $user->getId(), 'slug' => $slug));
    }
}
