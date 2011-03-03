<?php

namespace FOQ\AlbumBundle\Document;
use FOQ\AlbumBundle\Model\PhotoInterface;
use FOQ\AlbumBundle\Model\PhotoCollection;
use DateTime;

/**
 * @mongodb:MappedSuperclass
 */
abstract class Photo implements PhotoInterface
{
    /**
     * Document id
     *
     * @var string
     * @mongodb:Id()
     */
    protected $id = null;

    /**
     * Album where the photo is
     *
     * @var AlbumInterface
     * You must overwrite this mapping to set the target document to your user class
     * // mongodb:ReferenceOne(targetDocument="Album")
     */
    protected $album = null;

    /**
     * Photo number in an album
     *
     * @var int
     * @mongodb:Field(type="int")
     */
    protected $number = '';

    /**
     * Photo title
     *
     * @var string
     * @mongodb:Field(type="string")
     */
    protected $title = null;

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
     * @return AlbumInterface
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * @param  AlbumInterface
     * @return null
     */
    public function setAlbum($album)
    {
        $this->album = $album;
        $album->getPhotos()->add($this);
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

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($number)
    {
        $this->number = $number;
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
