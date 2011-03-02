<?php

namespace FOQ\AlbumBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;
use FOS\UserBundle\Model\User;
use Zend\Paginator\Paginator;
use ZendPaginatorAdapter\DoctrineMongoDBAdapter;

class AlbumRepository extends DocumentRepository
{
    public function findOneByUserAndSlug(User $user, $slug)
    {
        return $this->findOneBy(array('user.$id' => $user->getId(), 'slug' => $slug));
    }

    public function findAll($asPaginator = false)
    {
        $query = $this->createQueryBuilder()->sort('updatedAt', 'DESC');

        if ($asPaginator) {
            return new Paginator(new DoctrineMongoDBAdapter($query));
        }

        return array_values($query->getQuery()->execute()->toArray());
    }
}
