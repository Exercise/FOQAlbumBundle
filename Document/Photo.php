<?php

namespace FOQ\AlbumBundle\Document;
use FOQ\ContentBundle\Document\Content;

/**
 * @mongodb:EmbeddedDocument
 * @mongodb:HasLifecycleCallbacks
 */
class Photo extends Content
{
    /**
     * Unique key. Can be used as an ID.
     * Embedded documents can't have mongo IDs, but we need a unique identifier
     *
     * @var string
     * @mongodb:Field(type="string")
     */
    protected $key = '';

    public function __construct()
    {
        // generate a strong unique key (37^12 possibilities)
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        for ($i = 0; $i < 6; $i++) {
            $this->key .= $chars[mt_rand( 0, 35 )];
        }
    }

    public function getKey()
    {
        return $this->key;
    }
}
