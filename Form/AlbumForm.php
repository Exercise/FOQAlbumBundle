<?php

namespace FOQ\AlbumBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\TextField;
use Symfony\Component\Form\TextareaField;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

use FOQ\AlbumBundle\Model\AlbumInterface;
use FOQ\AlbumBundle\Provider\AlbumProvider;

use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;

class AlbumForm extends Form
{
    protected $request;
    protected $provider;
    protected $objectManager;
    protected $securityHelper;

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function setProvider(AlbumProvider $provider)
    {
        $this->provider = $provider;
    }

    public function setObjectManager($objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function setSecurityHelper($securityHelper)
    {
        $this->securityHelper = $securityHelper;
    }

    public function configure()
    {
        $this->add(new TextField('title'));
        $this->add(new TextareaField('description'));
    }

    public function process(AlbumInterface $album = null)
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

        $this->setData($album);

        if ('POST' == $this->request->getMethod()) {
            $this->bind($this->request);

            if ($this->isValid()) {
                $this->objectManager->persist($album);
                $this->request->getSession()->setFlash('foq_album_album_create', 'success');
                return true;
            }
        }

        return false;
    }
}
