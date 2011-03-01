<?php

namespace FOQ\AlbumBundle\Publisher;

use FOQ\AlbumBundle\Document\Album;

class AlbumPublisher
{
    public function publish(Album $album)
    {
        $album->setIsPublished(true);
    }

    public function unPublish(Album $album)
    {
        $album->setIsPublished(false);
    }
}
