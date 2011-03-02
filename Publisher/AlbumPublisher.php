<?php

namespace FOQ\AlbumBundle\Publisher;

use FOQ\AlbumBundle\Model\AlbumInterface;

class AlbumPublisher
{
    public function publish(AlbumInterface $album)
    {
        $album->setIsPublished(true);
    }

    public function unPublish(AlbumInterface $album)
    {
        $album->setIsPublished(false);
    }
}
