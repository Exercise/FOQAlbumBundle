<?php

namespace FOQ\AlbumBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\TextField;
use Symfony\Component\Form\TextareaField;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

use FOQ\AlbumBundle\Model\AlbumInterface;
use FOQ\AlbumBundle\Provider;

use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;

class AlbumForm extends Form
{
    protected $request;
    protected $provider;
    protected $objectManager;

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function setProvider(Provider $provider)
    {
        $this->provider = $provider;
    }

    public function setObjectManager($objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function configure()
    {
        $this->add(new TextField('title'));
        $this->add(new TextareaField('description'));
    }

    public function process(AlbumInterface $album = null)
    {
        if (null === $album) {
            $album = $this->provider->createAlbum();
        }
        if (!$user = $this->provider->getAuthenticatedUser()) {
            throw new InsufficientAuthenticationException();
        }
        $album->setUser($user);

        $this->setData($album);

        if ('POST' == $this->request->getMethod()) {
            $this->bind($this->request);

            if ($this->isValid()) {
                $this->objectManager->persist($album);
                return true;
            }
        }

        return false;
    }
}
