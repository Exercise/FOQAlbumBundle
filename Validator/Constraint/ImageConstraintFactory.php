<?php

namespace FOQ\AlbumBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraints\File;

class ImageConstraintFactory
{
    public function createImageConstraint()
    {
        return new File(array(
            'mimeTypes' => array('image/jpeg', 'image/png', 'image/gif')
        ));
    }
}
