<?php

namespace FOQ\AlbumBundle;

use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Model\User;
use FOQ\AlbumBundle\Document\AlbumRepository;
use FOQ\AlbumBundle\Model\AlbumInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use NotFoundException;
use Doctrine\ODM\MongoDB\Query\Builder;
use Zend\Paginator\Paginator;
use ZendPaginatorAdapter\DoctrineMongoDBAdapter;
use Zend\Paginator\Adapter\ArrayAdapter;

/**
 * High level object finder that uses the route parameters as method arguments
 */
class Provider
{
    protected $albumRepository;
    protected $userManager;
    protected $securityContext;
    protected $request;

    public function __construct(AlbumRepository $albumRepository, UserManagerInterface $userManager, SecurityContext $securityContext, Request $request = null)
    {
        $this->albumRepository = $albumRepository;
        $this->userManager     = $userManager;
        $this->securityContext = $securityContext;
        $this->request         = $request;
    }

    /**
     * Find an album by username and album slug
     *
     * @throws NotFoundException if album does not exist
     * @return AlbumInterface
     */
    public function getAlbum($username, $slug)
    {
        $album = $this->albumRepository->findOneByUserAndSlugForUser($this->getUser($username), $slug, $this->getAuthenticatedUser());

        if (empty($album)) {
            throw new NotFoundHttpException(sprintf('The album with user "%s" and slug "%s" does not exist or is not published', $username, $slug));
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
        return $this->paginate($this->albumRepository->createPublicSortedQuery($this->getAuthenticatedUser()));
    }

    /**
     * Return a paginator of albums of a user
     *
     * @return Paginator
     **/
    public function getUserAlbums($username)
    {
        $this->paginate($this->albumRepository->createPublicUserSortedQuery($this->getUser($username), $this->getAuthenticatedUser()));
    }

    /**
     * Get a paginator of photos of an album
     *
     * @return Paginator
     **/
    public function getAlbumPhotos(AlbumInterface $album)
    {
        return $this->paginate($album->getPhotos()->toArray());
    }

    /**
     * Find a photo
     *
     * @return Photo
     **/
    public function getPhoto($username, $slug, $number)
    {
        return $this->findAlbum($username, $slug)->getPhotoByNumber($number);
    }

    protected function getUser($username)
    {
        $user = $this->userManager->findUserByUsername($username);

        if (empty($user)) {
            throw new NotFoundHttpException(sprintf('The user "%s" does not exist', $username));
        }

        return $user;
    }

    protected function getAuthenticatedUser()
    {
        if ($token = $this->securityContext->getToken()) {
            if ($user = $token->getUser()) {
                if ($user instanceof User) {
                    return $user;
                }
            }
        }
    }

    protected function paginate($data)
    {
        if ($data instanceof Builder) {
            $adapter = new DoctrineMongoDBAdapter($data);
        } else {
            $adapter = new ArrayAdapter($data);
        }
        $paginator = new Paginator($adapter);

        $paginator->setCurrentPageNumber($this->request->query->get('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);

        return $paginator;
    }
}
