<?php

namespace FOQ\AlbumBundle\Document;

use FOS\UserBundle\Model\User;
use FOQ\AlbumBundle\Model\AlbumInterface;
use FOQ\AlbumBundle\Model\PhotoCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;

/**
 * @mongodb:MappedSuperclass
 */
abstract class Album implements AlbumInterface
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
     * Document id
     *
     * @var string
     * @mongodb:Id()
     */
    protected $id = null;

    /**
     * Album slug
     *
     * @var string
     * @mongodb:Field(type="string")
     * @gedmo:Slug
     */
    protected $slug = null;

    /**
     * Album title
     *
     * @var string
     * @mongodb:Field(type="string")
     * @gedmo:Sluggable
     */
    protected $title = null;

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
     * @mongodb:Field(type="boolean")
     */
    protected $published = false;

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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param  string
     * @return null
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param  string
     * @return null
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
        return $this->published;
    }

    /**
     * @param  bool
     * @return null
     */
    public function setIsPublished($published)
    {
        $this->published = $published;
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
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return PhotoCollection
     */
    public function getPhotos()
    {
        return new PhotoCollection($this->photos ?: $this->photos = new ArrayCollection());
    }

    public function setRank($rank)
    {
        $this->rank = $rank;
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
     * Increment the number of page impressions
     */
    public function incrementImpressions()
    {
        $this->impressions++;
    }
}
