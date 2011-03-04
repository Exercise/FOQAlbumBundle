<?php

namespace FOQ\AlbumBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;
use FOS\UserBundle\Model\User;
use MongoId;

class AlbumRepository extends DocumentRepository
{
    public function findOneByUserAndSlugForUser(User $user, $slug, User $forUser = null)
    {
        return $this->createPublishedOrOwnQuery($forUser)
            ->field('user.$id')->equals(new MongoId($user->getId()))
            ->field('slug')->equals($slug)
            ->getQuery()
            ->getSingleResult();
    }

    public function createPublicSortedQuery(User $forUser = null, array $sortOrder = array('date', 'desc'))
    {
        return $this->createPublishedOrOwnQuery($forUser)
            ->sort($sortOrder['field'], $sortOrder['order']);
    }

    public function createPublicUserSortedQuery(User $user, User $forUser = null, array $sortOrder = array('date', 'desc'))
    {
        return $this->createPublishedOrOwnQuery($forUser)
            ->field('user.$id')->equals(new MongoId($user->getId()))
            ->sort($sortOrder['field'], $sortOrder['order']);
    }

    public function createPublishedOrOwnQuery(User $user = null)
    {
        $query = $this->createQueryBuilder();

        if ($user) {
            $query
                ->addOr($query->expr()->field('published')->equals(true))
                ->addOr($query->expr()->field('user.$id')->equals(new MongoId($user->getId())));
        } else {
            $query->field('published')->equals(true);
        }

        return $query;
    }
}
