<?php

namespace FOQ\AlbumBundle\Uploader;

use FOQ\AlbumBundle\Adder\PhotoAdder;
use FOQ\AlbumBundle\Document\PhotoRepository;
use FOQ\AlbumBundle\Model\AlbumInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use FOQ\AlbumBundle\Validator\Exception\InvalidImageException;
use FOQ\AlbumBundle\Validator\Constraint\ImageValidator;
use FOQ\AlbumBundle\Validator\Constraint\ImageConstraintFactory;

class Uploader implements UploaderInterface
{
    protected $photoAdder;
    protected $photoRepository;
    protected $imageValidator;
    protected $imageConstraintFactory;

    public function __construct(PhotoAdder $photoAdder, PhotoRepository $photoRepository, ImageValidator $imageValidator, ImageConstraintFactory $imageConstraintFactory)
    {
        $this->photoAdder             = $photoAdder;
        $this->photoRepository        = $photoRepository;
        $this->imageValidator         = $imageValidator;
        $this->imageConstraintFactory = $imageConstraintFactory;
    }

    /**
     * Uploads an image and add a photo to the album
     *
     * @param AlbumInterface $album
     * @param UploadedFile $file
     * @return null
     * @throws InvalidImageException
     */
    public function upload(AlbumInterface $album, UploadedFile $file)
    {
        $this->validate($file);
        $photo = $this->photoRepository->createNewPhoto();
        $photo->setFile($file);
        $this->photoAdder->add($album, $photo);
    }

    /**
     * Validates the uploaded file
     *
     * @param UploadedFile $file
     * @return null
     * @throws InvalidImageException
     */
    protected function validate(UploadedFile $file)
    {
        $constraint = $this->imageConstraintFactory->createImageConstraint();
        $isValid = $this->imageValidator->isValid($file, $constraint);
        if (!$isValid) {
            throw new InvalidImageException(strtr($this->imageValidator->getMessageTemplate(), $this->imageValidator->getMessageParameters()));
        }
    }
}
