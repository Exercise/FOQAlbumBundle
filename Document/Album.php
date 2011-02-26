<?php

namespace FOQ\AlbumBundle\Document;
use FOQ\ContentBundle\Document\UserContent;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @mongodb:Document(
 *   collection="album",
 *   repositoryClass="FOQ\AlbumBundle\Document\AlbumRepository"
 * )
 * @mongodb:HasLifecycleCallbacks
 */
class Album extends UserContent
{
    /**
     * Photos in the album
     *
     * @var Collection
     * @mongodb:EmbedMany(targetDocument="FOQ\AlbumBundle\Document\Photo")
     */
    protected $photos = null;

    /**
     * Number of photos in the album
     *
     * @var int
     * @mongodb:Field(type="int")
     */
    protected $count = 0;

    /**
     * Position of the album (less is first)
     *
     * @var int
     * @mongodb:Field(type="int")
     */
    protected $rank = 0;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    /**
     * Return the image of the first photo if any
     *
     * @return Image
     **/
    public function getFirstPhotoImage()
    {
        if($photo = $this->getFirstPhoto()) {
            return $photo->getImage();
        }
    }

    /**
     * Get the first photo
     *
     * @return Photo
     **/
    public function getFirstPhoto()
    {
        return $this->getPhotos()->first();
    }

    /**
     * @return Collection
     */
    public function getPhotos()
    {
        return $this->photos ?: $this->photos = new ArrayCollection();
    }

    /**
     * @param  Collection
     * @return null
     */
    public function setPhotos($photos)
    {
        $this->photos = $photos;
    }

    public function addPhoto(Photo $photo)
    {
        $this->photos[] = $photo;
    }

    public function getPhotoByKey($key)
    {
        foreach($this->getPhotos() as $photo) {
            if($key === $photo->getKey()) {
                return $photo;
            }
        }
    }

    public function getPhotoPosition(Photo $photo)
    {
        return $this->getPhotos()->indexOf($photo) + 1;
    }

    public function isFirstPhoto(Photo $photo)
    {
        return 1 === $this->getPhotoPosition($photo);
    }

    public function isLastPhoto(Photo $photo)
    {
        return $this->getNbPhotos() === $this->getPhotoPosition($photo);
    }

    public function getPreviousPhoto(Photo $photo)
    {
        return $this->getPhotos()->get($this->getPhotos()->indexOf($photo)-1);
    }

    public function getNextPhoto(Photo $photo)
    {
        return $this->getPhotos()->get($this->getPhotos()->indexOf($photo)+1);
    }

    public function getNbPhotos()
    {
        return $this->getPhotos()->count();
    }

    public function getRank()
    {
        return $this->rank;
    }
}
