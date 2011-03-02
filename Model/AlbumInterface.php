<?php

namespace FOQ\AlbumBundle\Model;
use Doctrine\Common\Collections\Collection;
use DateTime;

interface AlbumInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param  string
     * @return null
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param  string
     * @return null
     */
    public function setDescription($description);

    /**
     * @return bool
     */
    public function getIsPublished();

    /**
     * @param  bool
     * @return null
     */
    public function setIsPublished($isPublished);

    /**
     * @return User
     */
    public function getUser();

    /**
     * @param  User
     * @return null
     */
    public function setUser($user);

    /**
     * @return PhotoCollection
     */
    public function getPhotos();

    public function getRank();

    public function setRank($rank);

    public function getCreatedAt();

    public function getUpdatedAt();

    public function setUpdatedNow();

    /**
     * @return int number of impressions
     */
    public function getImpressions();

    /**
     * Set the number of impressions
     *
     * @param int
     **/
    public function setImpressions($nb);

    /**
     * Increment the number of page impressions
     */
    public function incrementImpressions();
}
