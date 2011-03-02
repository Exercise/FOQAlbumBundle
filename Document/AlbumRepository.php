<?php

namespace FOQ\AlbumBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;
use FOS\UserBundle\Model\User;
use Zend\Paginator\Paginator;
use ZendPaginatorAdapter\DoctrineMongoDBAdapter;
use MongoId;

class AlbumRepository extends DocumentRepository
{
    public function findOneByUserAndSlugForUser(User $user, $slug, User $forUser = null)
    {
        $query = $this->createPublishedOrOwnQuery($forUser)
            ->field('user.$id')->equals(new MongoId($user->getId()))
            ->field('slug')->equals($slug);

        return $query->getQuery()->getSingleResult();
    }

    public function findOnePublishedByUserAndSlug(User $user, $slug)
    {
        return $this->findOneBy(array('user.$id' => new MongoId($user->getId()), 'slug' => $slug, 'isPublished' => true));
    }

    public function findForUser(User $user = null, $asPaginator = false)
    {
        $query = $this->createPublishedOrOwnQuery($user)
            ->sort('updatedAt', 'DESC');

        if ($asPaginator) {
            return new Paginator(new DoctrineMongoDBAdapter($query));
        }

        return array_values($query->getQuery()->execute()->toArray());
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
