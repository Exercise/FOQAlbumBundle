<?php

namespace FOQ\AlbumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOQ\AlbumBundle\Document\Album;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AlbumController extends Controller
{
    public function indexAction()
    {
        $albums = $this->get('foq_album.repository.album')->findRecents();

        return $this->get('templating')->renderResponse('FOSAlbumBundle:Album:list.html.twig', compact('albums'));
    }

    public function newAction()
    {
        if(!$this->isAllowed(new Album(), 'create')) {
            return $this->goToLogin();
        }
        $album = new Album();
        $form = $this->get('foq_album.form.album');
        $form->setData($album);

        // Prepare the crumb menu
        $this->addContentBreadCrumb();
        $this->get('menu.crumb')->addChild('New album', $this->get('router')->generate('album_new'));

        return $this->render('AlbumBundle:Frontend:new.twig', array('form' => $form));
    }

    public function createAction()
    {
        if(!$this->isAllowed(new Album(), 'create')) {
            return $this->goToLogin();
        }
        $album = new Album();
        $form = $this->get('foq_album.form.album');
        $form->setData($album);

        $form->bind($this->get('request')->request->get($form->getName()));
        if(!$form->isValid()) {
            return $this->renderJson($form->fetchAllErrors(), 400);
        }
    }

    public function publishAction($id)
    {
        $album = $this->getRepository()->find($id);
        if(!$album) throw new NotFoundHttpException();

        if($album->isPublished()) {
            return $this->renderJson(array('error' => 'Album is already published.'), 403);
        }

        if(!$this->isAllowed($album, 'publish')) {
            throw new Exception\HttpForbiddenException();
        }

        $album->publish();
        $this->getOdm()->flush();

        return $this->renderJson();
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
}
