<?php

namespace FOQ\AlbumBundle\Document;
use FOQ\ContentBundle\Document\UserContent;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;

/**
 * @mongodb:Document(
 *   collection="album",
 *   repositoryClass="FOQ\AlbumBundle\Document\AlbumRepository"
 * )
 */
abstract class Album extends UserContent
{
    /**
     * User who owns this album
     * You must overwrite this mapping to set the target document to your user class
     *
     * @var User
     */
    protected $user = null;

    /**
     * Photos in the album
     *
     * @var Collection
     * You must overwrite this mapping to set the target document to your user class
     */
    protected $photos = null;

    /**
     * End of abstract mappings
     */

    /**
     * Album name
     *
     * @var string
     * @mongodb:Field(type="string")
     */
    protected $name = null;

    /**
     * Album description
     *
     * @var string
     * @mongodb:Field(type="string")
     */
    protected $description = null;

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

    /**
     * Whether or not the album is visible
     *
     * @var bool
     * @mongodb:Field(type="bool")
     */
    protected $isPublished = false;

    /**
     * Number of times the album has been displayed
     *
     * @var int
     * @mongodb:Field(type="int")
     */
    protected $impressions = 0;

    /**
     * @mongodb:Field(type="date")
     */
    protected $createdAt;

    /**
     * @mongodb:Field(type="date")
     */
    protected $updatedAt;

    public function __construct()
    {
        $this->photos    = new ArrayCollection();
        $this->createdAt = new DateTime();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  string
     * @return null
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param  string
     * @return null
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return bool
     */
    public function getIsPublished()
    {
        return $this->isPublished;
    }

    /**
     * @param  bool
     * @return null
     */
    public function setIsPublished($isPublished)
    {
        $this->isPublished = $isPublished;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param  User
     * @return null
     */
    public function setUser($user)
    {
        $this->user = $user;
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

    public function addPhoto(Photo $photo)
    {
        $photo->setNumber($this->getNextPhotoNumber());
        $this->getPhotos()->add($photo);
    }

    public function getPhotoByNumber($number)
    {
        foreach($this->getPhotos() as $photo) {
            if($number == $photo->getNumber()) {
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

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedNow()
    {
        $this->updatedAt = new DateTime();
    }

    /**
     * @return int number of impressions
     */
    public function getImpressions()
    {
        return $this->impressions;
    }

    /**
     * Set the number of impressions
     *
     * @param int
     **/
    public function setImpressions($nb)
    {
        $this->impressions = $nb;
    }

    /**
     * Increment the number of page impressions
     */
    public function incrementImpressions()
    {
        $this->impressions++;
    }

    protected function getNextPhotoNumber()
    {
        $number = 1;
        foreach ($this->getPhotos() as $photo) {
            if ($photo->getNumber() > $number) {
                $number = $photo->getNumber();
            }
        }

        return $number;
    }
}
