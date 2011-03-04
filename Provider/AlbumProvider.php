<?php

namespace FOQ\AlbumBundle\Provider;

use FOS\UserBundle\Model\UserManagerInterface;
use FOQ\AlbumBundle\Document\AlbumRepository;
use FOQ\AlbumBundle\Model\AlbumInterface;
use FOQ\AlbumBundle\Sorter\AlbumSorter;
use FOQ\AlbumBundle\SecurityHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * High level object finder that uses the route parameters as method arguments
 */
class AlbumProvider extends AbstractProvider
{
    protected $albumRepository;
    protected $userManager;
    protected $securityHelper;
    protected $documentManager;
    protected $albumSorter;
    protected $request;

    public function __construct(AlbumRepository $albumRepository, UserManagerInterface $userManager, SecurityHelper $securityHelper, DocumentManager $documentManager, AlbumSorter $albumSorter, Request $request = null)
    {
        $this->albumRepository = $albumRepository;
        $this->userManager     = $userManager;
        $this->securityHelper  = $securityHelper;
        $this->documentManager = $documentManager;
        $this->albumSorter     = $albumSorter;
        $this->request         = $request;
    }

    /**
     * Find an album by username and album slug
     *
     * @throws NotFoundException if album does not exist
     * @return AlbumInterface
     */
    public function getAlbum($username, $slug, $incrementImpressions = false)
    {
        $album = $this->albumRepository->findOneByUserAndSlugForUser($this->getUser($username), $slug, $this->securityHelper->getUser());

        if (empty($album)) {
            throw new NotFoundHttpException(sprintf('The album with user "%s" and slug "%s" does not exist or is not published', $username, $slug));
        }

        if ($incrementImpressions) {
            $this->incrementImpressions($album);
        }

        return $album;
    }

    /**
     * Return a paginator of albums
     *
     * @return Paginator
     **/
    public function getAlbums()
    {
        return $this->paginate($this->albumRepository->createPublicSortedQuery($this->securityHelper->getUser(), $this->albumSorter->getDatabaseOrder()));
    }

    /**
     * Return a paginator of albums of a user
     *
     * @return Paginator
     **/
    public function getUserAlbums($username)
    {
        return $this->paginate($this->albumRepository->createPublicUserSortedQuery($this->getUser($username), $this->securityHelper->getUser(), $this->albumSorter->getDatabaseOrder()));
    }

    /**
     * Returns a new album instance
     *
     * @return AlbumInterface
     **/
    public function createAlbum()
    {
        $albumClass = $this->albumRepository->getDocumentName();

        return new $albumClass();
    }
}
