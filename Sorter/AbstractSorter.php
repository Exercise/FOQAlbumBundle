<?php

namespace FOQ\AlbumBundle\Sorter;

use Symfony\Component\HttpFoundation\Request;

abstract class AbstractSorter
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getDatabaseOrder()
    {
        $info = $this->getSortFieldInfo();

        return $info[$this->getRequestSortField()];
    }

    public function getRequestSortField()
    {
        $acceptableRequestFields = $this->getAcceptableRequestFields();
        $requestField = $this->request->query->get('sort');

        if ($requestField && in_array($requestField, $acceptableRequestFields)) {
            $field = $requestField;
        } else {
            $field = $acceptableRequestFields[0];
        }

        return $field;
    }

    public function getAcceptableRequestFields()
    {
        return array_keys($this->getSortFieldInfo());
    }

    /**
     * All possible sort fields
     * request name => database order
     *
     * e.g.
     * 'date' => array('field' => 'createdAt', 'order' => 'desc')
     *
     * @return array
     */
    abstract protected function getSortFieldInfo();
}
