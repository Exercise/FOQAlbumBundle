<?php

namespace FOQ\AlbumBundle\Form;

use Symfony\Component\HttpFoundation\Request;

use FOQ\AlbumBundle\Model\AlbumInterface;
use FOQ\AlbumBundle\Provider\AlbumProvider;
use FOQ\AlbumBundle\SecurityHelper;

use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
use Symfony\Component\Form\FormInterface;

class AlbumFormHandler
{
    protected $request;
    protected $provider;
    protected $objectManager;
    protected $securityHelper;

    public function __construct(Request $request, AlbumProvider $provider, $objectManager, SecurityHelper $securityHelper)
    {
        $this->request = $request;
        $this->provider = $provider;
        $this->objectManager = $objectManager;
        $this->securityHelper = $securityHelper;
    }

    public function process(FormInterface $form, AlbumInterface $album = null)
    {
        if (!$user = $this->securityHelper->getUser()) {
            throw new InsufficientAuthenticationException('You need to log in to create an album');
        }
        if (null === $album) {
            $album = $this->provider->createAlbum();
            $album->setUser($user);
        } else if (!$user->is($album->getUser())) {
            throw new InsufficientAuthenticationException('You do not own this album');
        }

        $form->setData($album);

        if ('POST' === $this->request->getMethod()) {
            $form->handleRequest($this->request);

            if ($form->isValid()) {
                $this->objectManager->persist($album);
                $this->request->getSession()->getFlashBag()->add('foq_album_album_create', 'success');
                return true;
            }
        }

        return false;
    }
}
