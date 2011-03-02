<?php

namespace FOQ\AlbumBundle\Model;
use FOS\UserBundle\Model\User;
use FOQ\AlbumBundle\Model\PhotoCollection;
use DateTime;

interface AlbumInterface
{
    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param  string
     * @return null
     */
    public function setTitle($title);

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
    public function setUser(User $user);

    /**
     * @return PhotoCollection
     */
    public function getPhotos();

    public function setRank($rank);

    public function getCreatedAt();

    public function getUpdatedAt();

    public function setUpdatedNow();

    /**
     * @return int number of impressions
     */
    public function getImpressions();

    /**
     * Increment the number of page impressions
     */
    public function incrementImpressions();
}
