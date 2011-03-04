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
    function getId();

    /**
     * @return string
     */
    function getSlug();

    /**
     * @param  string
     * @return null
     */
    function setSlug($slug);

    /**
     * @return string
     */
    function getTitle();

    /**
     * @param  string
     * @return null
     */
    function setTitle($title);

    /**
     * @return string
     */
    function getDescription();

    /**
     * @param  string
     * @return null
     */
    function setDescription($description);

    /**
     * @return bool
     */
    function getIsPublished();

    /**
     * @param  bool
     * @return null
     */
    function setIsPublished($isPublished);

    /**
     * @return DateTime
     */
    function getPublishedAt();

    /**
     * @param  DateTime
     * @return null
     */
    function setPublishedAt(DateTime $publishedAt);

    /**
     * @return User
     */
    function getUser();

    /**
     * @param  User
     * @return null
     */
    function setUser(User $user);

    /**
     * @return PhotoCollection
     */
    function getPhotos();

    /**
     * Gets number of photos
     *
     * @return int
     **/
    function getCount();

    /**
     * Update number of photos
     *
     * @return null
     **/
    function updateCount();

    function setRank($rank);

    function getCreatedAt();

    function getUpdatedAt();

    function setUpdatedNow();

    /**
     * @return int number of impressions
     */
    function getImpressions();

    /**
     * Increment the number of page impressions
     */
    function incrementImpressions();
}
