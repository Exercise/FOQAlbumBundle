<?php

namespace FOQ\AlbumBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use FOQ\AlbumBundle\Document\Album;
use FOS\UserBundle\Model\User;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AlbumController extends ContainerAware
{
    public function indexAction()
    {
        $albums = $this->container->get('foq_album.repository.album')->findForUser($this->getUser(), true);

        return $this->container->get('templating')->renderResponse('FOQAlbumBundle:Album:list.html.twig', compact('albums'));
    }

    public function showAction($username, $slug)
    {
        $album = $this->findAlbum($username, $slug);

        return $this->container->get('templating')->renderResponse('FOQAlbumBundle:Album:show.html.twig', compact('album'));
    }

    /**
     * Show the new form
     */
    public function newAction()
    {
        $form = $this->container->get('fos_user.form.user');

        return $this->container->get('templating')->renderResponse('FOQAlbunBundle:Album:new.html.twig', array('form' => $form));
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

    /**
     * Find an album by username and album slug
     *
     * @throws NotFoundException if album does not exist
     * @return Album
     */
    protected function findAlbum($username, $slug)
    {
        $user = $this->container->get('fos_user.user_manager')->findUserByUsername($username);

        if (empty($user)) {
            throw new NotFoundHttpException(sprintf('The user "%s" does not exist', $username));
        }

        $album = $this->container->get('foq_album.repository.album')->findOneByUserAndSlugForUser($user, $slug, $this->getUser());

        if (empty($album)) {
            throw new NotFoundHttpException(sprintf('The album with user "%s" and slug "%s" does not exist or is not published', $username, $slug));
        }

        return $album;
    }

    protected function getUser()
    {
        if ($token = $this->container->get('security.context')->getToken()) {
            if ($user = $token->getUser()) {
                if ($user instanceof User) {
                    return $user;
                }
            }
        }
    }

    protected function setFlash($action, $value)
    {
        $this->container->get('session')->setFlash($action, $value);
    }
}
