<?php

namespace FOQ\AlbumBundle\Uploader;

use FOQ\AlbumBundle\Adder\PhotoAdder;
use FOQ\AlbumBundle\Document\PhotoProvider;
use FOQ\AlbumBundle\Model\AlbumInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader implements UploaderInterface
{
    protected $photoAdder;
    protected $photoProvider;

    public function __construct(PhotoAdder $photoAdder, PhotoProvider $photoProvider)
    {
        $this->photoAdder    = $photoAdder;
        $this->photoProvider = $photoProvider;
    }

    public function upload(AlbumInterface $album, UploadedFile $file)
    {
        $photo = $this->photoProvider->createPhoto();
        $photo->setFile($file);
        $this->photoAdder->add($album, $photo);
    }
}
