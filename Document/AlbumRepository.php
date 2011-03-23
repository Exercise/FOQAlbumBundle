<?php

namespace FOQ\AlbumBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;
use FOS\UserBundle\Model\User;
use MongoId;

class AlbumRepository extends DocumentRepository
{
    public function findOneByUserAndSlug(User $user, $slug)
    {
        return $this->createQueryBuilder()
            ->field('user.$id')->equals(new MongoId($user->getId()))
            ->field('slug')->equals($slug)
            ->getQuery()
            ->getSingleResult();
    }

    public function findOneByUserAndSlugForUser(User $user, $slug, User $forUser = null)
    {
        return $this->createPublishedOrOwnQuery($forUser)
            ->field('user.$id')->equals(new MongoId($user->getId()))
            ->field('slug')->equals($slug)
            ->getQuery()
            ->getSingleResult();
    }

    public function findByUser(User $user, $limit = null)
    {
        $query = $this->createQueryByUser($user);
        if($limit) {
            $query->limit($limit);
        }
        return $query->getQuery()->execute();
    }

    public function findPublishedByUser(User $user, $limit = null)
    {
        $query = $this->createPublishedQueryByUser($user);
        if($limit) {
            $query->limit($limit);
        }
        return $query->getQuery()->execute();
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

    public function createPublishedQueryByUser(User $user)
    {
        return $this->createQueryByUser($user)->field('published')->equals(true);
    }

    public function createQueryByUser(User $user)
    {
        $query = $this->createQueryBuilder();

        $query->field('user.$id')->equals(new MongoId($user->getId()));

        return $query;
    }

    public function createNewAlbum()
    {
        $albumClass = $this->getDocumentName();

        return new $albumClass();
    }
}
