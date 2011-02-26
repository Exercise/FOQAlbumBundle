<?php

namespace FOQ\AlbumBundle\Document;

use FOQ\ContentBundle\Document\UserContentRepository;

class PhotoRepository extends UserContentRepository
{
    public function createQueryByAlbum(Album $album)
    {
        $id = new \MongoId($album->getId());

        $query = $this->createQuery()->field('album.$id')->equals($id);

        return $query;
    }
}
