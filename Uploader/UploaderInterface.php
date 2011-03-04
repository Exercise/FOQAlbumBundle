<?php

namespace FOQ\AlbumBundle\Uploader;

use FOQ\AlbumBundle\Model\AlbumInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploaderInterface
{
    function upload(AlbumInterface $album, UploadedFile $file);
}
