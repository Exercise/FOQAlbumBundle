<?php

namespace FOQ\AlbumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('FOQAlbumBundle:Default:index.html.twig');
    }
}
