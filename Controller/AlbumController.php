<?php

namespace FOQ\AlbumBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AlbumController extends ContainerAware
{
    public function indexAction()
    {
        return $this->getTemplating()->renderResponse('FOQAlbumBundle:Album:list.html.twig', array(
            'albums' => $this->getProvider()->getPaginatedAlbums()
        ));
    }

    public function showAction($username, $slug)
    {
        return $this->getTemplating()->renderResponse('FOQAlbumBundle:Album:show.html.twig', array(
            'album' => $this->getProvider()->getAlbum($username, $slug)
        ));
    }

    /**
     * Show the new form
     */
    public function newAction()
    {
        return $this->getTemplating()->renderResponse('FOQAlbunBundle:Album:new.html.twig', array(
            'form' => $this->container->get('fos_user.form.user')
        ));
    }

    /**
     * Create a user and send a confirmation email
     */
    public function createAction()
    {
        $form = $this->container->get('fos_user.form.user');

        $process = $form->process(null, $this->container->getParameter('fos_user.email.confirmation.enabled'));
        if ($process) {
            $this->setFlash('foq_album_album_create', 'success');
            return new RedirectResponse($this->container->get('foq_album.url_generator')->getUrlForAlbum($form->getData()));
        }

        return $this->container->get('templating')->renderResponse('FOQUserBundle:User:new.html.twig', array('form' => $form));
    }

    public function publishAction($username, $slug)
    {
        $album = $this->findAlbum($username, $slug);

        $this->container->get('foq_album.publisher.album')->publish($album);
        $this->container->get('foq_album.object_manager')->flush();

        return new RedirectResponse($this->container->get('foq_album.url_generator')->getUrlForAlbum($form->getData()));
    }

    public function unPublishAction($username, $slug)
    {
        $album = $this->findAlbum($username, $slug);

        $this->container->get('foq_album.publisher.album')->unPublish($album);
        $this->container->get('foq_album.object_manager')->flush();

        return new RedirectResponse($this->container->get('foq_album.url_generator')->getUrlForAlbum($form->getData()));
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
}
