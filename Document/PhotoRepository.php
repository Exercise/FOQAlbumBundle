<?php

namespace FOQ\AlbumBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;

class PhotoRepository extends DocumentRepository
{
    public function createQueryByAlbum(Album $album)
    {
        $id = new \MongoId($album->getId());

        $query = $this->createQuery()->field('album.$id')->equals($id);

        return $query;
    }
}
