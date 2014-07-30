<?php

namespace FOQ\AlbumBundle\Provider;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ODM\MongoDB\Query\Builder;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineODMMongoDBAdapter;
use Pagerfanta\Adapter\ArrayAdapter;

abstract class AbstractProvider
{
    protected $itemCountPerPage;

    public function getItemCountPerPage()
    {
        return $this->itemCountPerPage;
    }

    public function setItemCountPerPage($itemCountPerPage)
    {
        $this->itemCountPerPage = $itemCountPerPage;
    }

    protected function incrementImpressions($object)
    {
        $hash = md5(get_class($object).$object->getId());

        if(!$this->request->getSession()->has($hash)) {
            $object->incrementImpressions();
            $this->documentManager->flush();
            $this->request->getSession()->set($hash, true);
        }
    }

    protected function getUser($username)
    {
        $user = $this->userManager->findUserByUsername($username);

        if (empty($user)) {
            throw new NotFoundHttpException(sprintf('The user "%s" does not exist', $username));
        }

        return $user;
    }

    protected function paginate($data)
    {
        if ($data instanceof Builder) {
            $adapter = new DoctrineODMMongoDBAdapter($data);
        } else {
            $adapter = new ArrayAdapter($data);
        }
        $paginator = new Pagerfanta($adapter);
        $paginator->setCurrentPage($this->request->get('page', 1));
        $paginator->setMaxPerPage($this->getItemCountPerPage());

        return $paginator;
    }
}
