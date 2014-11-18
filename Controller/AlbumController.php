<?php

namespace FOQ\AlbumBundle\Controller;

use FOQ\AlbumBundle\Model\AlbumInterface;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOQ\AlbumBundle\Validator\Exception\InvalidImageException;

class AlbumController extends ContainerAware
{
    public function indexAction()
    {
        return $this->getTemplating()->renderResponse('FOQAlbumBundle:Album:index.html.twig', array(
            'albums' => $this->getProvider()->getAlbums(),
            'sortBy' => $this->container->get('foq_album.sorter.album')->getRequestSortField()
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
            'album'  => $this->getProvider()->getAlbum($username, $slug, true),
            'sortBy' => $this->container->get('foq_album.sorter.album')->getRequestSortField()
        ));
    }

    public function newAction()
    {
		$form = $this->container->get('form.factory')->create($this->container->get('foq_album.form_type.album'));
		$handler = $this->container->get('foq_album.form_handler.album');

        if ($handler->process($form)) {
            $this->container->get('foq_album.object_manager')->flush();

            return new RedirectResponse($this->getAlbumUrl($form->getData()));
        }

        return $this->container->get('templating')->renderResponse('FOQAlbumBundle:Album:new.html.twig', array('form' => $form->createView()));
    }

    public function editAction($username, $slug)
    {
        $album = $this->getProvider()->getAlbum($username, $slug);
		$form = $this->container->get('form.factory')->create($this->container->get('foq_album.form_type.album'));
		$handler = $this->container->get('foq_album.form_handler.album');

        if ($handler->process($form, $album)) {
            $this->container->get('foq_album.object_manager')->flush();

            return new RedirectResponse($this->getAlbumUrl($album));
        }

        return $this->container->get('templating')->renderResponse('FOQAlbumBundle:Album:new.html.twig', array('form' => $form->createView()));
    }

    public function deleteAction($username, $slug)
    {
        $album = $this->getProvider()->getAlbum($username, $slug);
        $this->checkAlbumOwning($album);
        $this->container->get('foq_album.deleter.album')->delete($album);
        $this->container->get('foq_album.object_manager')->flush();

        return new RedirectResponse($this->container->get('router')->generate('foq_album_index'));
    }

    public function publishAction($username, $slug)
    {
        $album = $this->getProvider()->getAlbum($username, $slug);
        $this->checkAlbumOwning($album);
        $this->container->get('foq_album.publisher.album')->publish($album);
        $this->container->get('foq_album.object_manager')->flush();

        return new RedirectResponse($this->getAlbumUrl($album));
    }

    public function uploadAction($username, $slug)
    {
        $album = $this->getProvider()->getAlbum($username, $slug);
        $this->checkAlbumOwning($album);

        return $this->container->get('templating')->renderResponse('FOQAlbumBundle:Album:upload.html.twig', array(
            'album' => $album
        ));
    }

    public function uploadCallbackAction($username, $slug)
    {
        $album = $this->getProvider()->getAlbum($username, $slug);
        $this->checkAlbumOwning($album);
        $file = $this->container->get('request')->files->get('file');
        try {
            $this->container->get('foq_album.uploader')->upload($album, $file);
        } catch (InvalidImageException $e) {
            return new Response($e->getMessage());
        }
        $this->container->get('foq_album.object_manager')->flush();

        return new Response('ok');
    }

    protected function setFlash($action, $value)
    {
        $this->container->get('session')->getFlashBag->add($action, $value);
    }

    protected function getTemplating()
    {
        return $this->container->get('templating');
    }

    protected function getProvider()
    {
        return $this->container->get('foq_album.provider.album');
    }

    protected function getAlbumUrl(AlbumInterface $album)
    {
        return $this->container->get('foq_album.url_generator')->getAlbumUrl('foq_album_album_show', $album);
    }

    /**
     * @param AlbumInterface $album
     * @throws AccessDeniedException
     * @return void
     */
    protected function checkAlbumOwning(AlbumInterface $album)
    {
        if (!$this->container->get('foq_album.security_helper') ||
            $album->getUser()->getId() !== $this->container->get('foq_album.security_helper')->getUser()->getId()) {
            throw new AccessDeniedException();
        }
    }
}
