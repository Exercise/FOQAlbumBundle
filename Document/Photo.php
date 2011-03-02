<?php

namespace FOQ\AlbumBundle\Document;
use FOQ\ContentBundle\Document\Content;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @mongodb:EmbeddedDocument
 * @mongodb:HasLifecycleCallbacks
 */
abstract class Photo
{
    /**
     * Photo number in an album
     *
     * @var int
     * @mongodb:Field(type="integer")
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
     * Set the photo file
     *
     * @param File $file
     * @return null
     */
    abstract public function setFile(File $file);

    /**
     * Get the photo web path (e.g. images/animals/turtle.jpg)
     *
     * @return string
     */
    abstract public function getWebPath();

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
        return $this->key;
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
}
