<?php

namespace FOQ\AlbumBundle\Publisher;

use FOQ\AlbumBundle\Model\AlbumInterface;
use DateTime;

class AlbumPublisher
{
    public function publish(AlbumInterface $album)
    {
        $album->setIsPublished(true);

        if (!$album->getPublishedAt()) {
            $album->setPublishedAt(new Datetime());
        }
    }

    public function unPublish(AlbumInterface $album)
    {
        $album->setIsPublished(false);
    }
}
