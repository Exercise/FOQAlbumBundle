<?php

namespace FOQ\AlbumBundle\Model;

use Symfony\Component\HttpFoundation\File\File;

interface PhotoInterface
{
    /**
     * @return string
     */
    function getId();

    /**
     * Set the photo file
     *
     * @param File $file
     * @return null
     */
    function setFile(File $file);

    /**
     * @return AlbumInterface
     */
    function getAlbum();

    /**
     * @param  AlbumInterface
     * @return null
     */
    function setAlbum($album);

    /**
     * @return string
     */
    function getTitle();

    /**
     * @param  string
     * @return null
     */
    function setTitle($title);

    function getNumber();

    function setNumber($number);

    function getCreatedAt();

    function getUpdatedAt();

    function setUpdatedNow();

    /**
     * @return int number of impressions
     */
    function getImpressions();

    /**
     * Increment the number of impressions by one
     */
    function incrementImpressions();
}
