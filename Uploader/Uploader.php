<?php

namespace FOQ\AlbumBundle\Uploader;

use FOQ\AlbumBundle\Adder\PhotoAdder;
use FOQ\AlbumBundle\Document\PhotoRepository;
use FOQ\AlbumBundle\Model\AlbumInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader implements UploaderInterface
{
    protected $photoAdder;
    protected $photoRepository;

    public function __construct(PhotoAdder $photoAdder, PhotoRepository $photoRepository)
    {
        $this->photoAdder      = $photoAdder;
        $this->photoRepository = $photoRepository;
    }

    public function upload(AlbumInterface $album, UploadedFile $file)
    {
        $photo = $this->photoRepository->createNewPhoto();
        $photo->setFile($file);
        $this->photoAdder->add($album, $photo);
    }
}
