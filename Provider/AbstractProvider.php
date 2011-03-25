<?php

namespace FOQ\AlbumBundle\Provider;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ODM\MongoDB\Query\Builder;

use Zend\Paginator\Paginator;
use ZendPaginatorAdapter\DoctrineMongoDBAdapter;
use Zend\Paginator\Adapter\ArrayAdapter;

abstract class AbstractProvider
{
    protected $itemCountPerPage;
    protected $pageRange;
    
    public function getItemCountPerPage() {
        return $this->$itemCountPerPage;
    }
    
    public function setItemCountPerPage($itemCountPerPage) {
        $this->itemCountPerPage = $itemCountPerPage;
    }
    
    public function getPageRange() {
        return $this->pageRange;
    }
    
    public function setPageRange($pageRange) {
        $this->pageRange = $pageRange;
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
            $adapter = new DoctrineMongoDBAdapter($data);
        } else {
            $adapter = new ArrayAdapter($data);
        }
        $paginator = new Paginator($adapter);

        $paginator->setCurrentPageNumber($this->request->get('page', 1));
        $paginator->setItemCountPerPage($this->getItemCountPerPage());
        $paginator->setPageRange($this->getPageRange());

        return $paginator;
    }
}
