<?php

namespace FOQ\AlbumBundle\Controller;

use FOQ\AlbumBundle\Model\AlbumInterface;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AlbumController extends ContainerAware
{
    public function indexAction()
    {
        return $this->getTemplating()->renderResponse('FOQAlbumBundle:Album:index.html.twig', array(
            'albums' => $this->getProvider()->getAlbums()
        ));
    }

    public function listByUserAction($username)
    {
        return $this->getTemplating()->renderResponse('FOQAlbumBundle:Album:byUser.html.twig', array(
            'albums' => $this->getProvider()->getUserAlbums($username),
            'user'   => $this->container->get('fos_user.user_manager')->findUserByUsername($username)
        ));
    }

    public function showAction($username, $slug)
    {
        return $this->getTemplating()->renderResponse('FOQAlbumBundle:Album:show.html.twig', array(
            'album' => $this->getProvider()->getAlbum($username, $slug, true)
        ));
    }

    public function newAction()
    {
        $form = $this->container->get('foq_album.form.album');

        if ($form->process()) {
            $this->container->get('foq_album.object_manager')->flush();

            return new RedirectResponse($this->getAlbumUrl($form->getData()));
        }

        return $this->container->get('templating')->renderResponse('FOQAlbumBundle:Album:new.html.twig', array('form' => $form));
    }

    public function editAction($username, $slug)
    {
        $album = $this->getProvider()->getAlbum($username, $slug);
        $form  = $this->container->get('foq_album.form.album');

        if ($form->process($album)) {
            $this->container->get('foq_album.object_manager')->flush();

            return new RedirectResponse($this->getAlbumUrl($album));
        }

        return $this->container->get('templating')->renderResponse('FOQAlbumBundle:Album:new.html.twig', array('form' => $form));
    }

    public function publishAction($username, $slug)
    {
        $album = $this->getProvider()->getAlbum($username, $slug);
        $this->container->get('foq_album.publisher.album')->publish($album);
        $this->container->get('foq_album.object_manager')->flush();

        return new RedirectResponse($this->getAlbumUrl($album));
    }

    protected function getSortFieldInfo($sortBy = null, $optionalFields = null)
    {
        $sortData = array(
            'date' => array(
                'field' => 'publishedAt',
                'order' => 'desc'
            ),
            'views' => array(
                'field' => 'impressions',
                'order' => 'desc'
            ),
            'comments' => array(
                'field' => 'commentCount',
                'order' => 'desc'
            ),

        );

        if($optionalFields) {
            $sortData = array_merge($sortData, $optionalFields);
        }

        if ($sortBy && isset($sortData[$sortBy])) {
            return $sortData[$sortBy];
        } else {
            return $sortData['date'];
        }
    }

    protected function setFlash($action, $value)
    {
        $this->container->get('session')->setFlash($action, $value);
    }

    protected function getTemplating()
    {
        return $this->container->get('templating');
    }

    protected function getProvider()
    {
        return $this->container->get('foq_album.provider');
    }

    protected function getAlbumUrl(AlbumInterface $album)
    {
        return $this->container->get('foq_album.url_generator')->getAlbumUrl('foq_album_album_show', $album);
    }
}
