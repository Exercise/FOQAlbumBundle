<?php

namespace FOQ\AlbumBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;
use FOS\UserBundle\Model\User;

class AlbumRepository extends DocumentRepository
{
    public function findOneByUserAndSlug(User $user, $slug)
    {
        return $this->findOneBy(array('user.$id' => $user->getId(), 'slug' => $slug));
    }
}
