<?php

namespace FOQ\AlbumBundle\Model;

use FOQ\AlbumBundle\Model\CollectionWrapper;
use FOQ\AlbumBundle\Document\Photo;

class PhotoCollection extends CollectionWrapper
{
    public function add($photo)
    {
        $photo->setNumber($this->getNextPhotoNumber());
        parent::add($photo);
    }

    public function getPhotoByNumber($number)
    {
        foreach($this->getValues() as $photo) {
            if($number == $photo->getNumber()) {
                return $photo;
            }
        }
    }

    public function getPhotoPosition(Photo $photo)
    {
        return $this->indexOf($photo) + 1;
    }

    public function isFirstPhoto(Photo $photo)
    {
        return 1 === $this->getPhotoPosition($photo);
    }

    public function isLastPhoto(Photo $photo)
    {
        return $this->count() === $this->getPhotoPosition($photo);
    }

    public function getPreviousPhoto(Photo $photo)
    {
        return $this->get($this->indexOf($photo)-1);
    }

    public function getNextPhoto(Photo $photo)
    {
        return $this->get($this->indexOf($photo)+1);
    }

    protected function getNextPhotoNumber()
    {
        return $this->getHighestPhotoNumber() + 1;
    }

    protected function getHighestPhotoNumber()
    {
        $number = 0;
        foreach ($this->getValues() as $photo) {
            if ($photo->getNumber() > $number) {
                $number = $photo->getNumber();
            }
        }

        return $number;
    }
}
