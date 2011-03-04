<?php

namespace FOQ\AlbumBundle\Sorter;

class AlbumSorter extends AbstractSorter
{
    protected function getSortFieldInfo()
    {
        return array(
            'date' => array(
                'field' => 'publishedAt',
                'order' => 'desc'
            ),
            'views' => array(
                'field' => 'impressions',
                'order' => 'desc'
            ),
        );
    }
}
