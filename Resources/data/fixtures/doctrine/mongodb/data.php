<?php

use FOQ\AlbumBundle\Document\Album;
use FOQ\AlbumBundle\Document\Photo;
use Symfony\Component\HttpFoundation\File\File;

$album1 = new Album();
$album1->setTitle('Album one');
$album1->setUser($dm->getRepository('UserBundle:User')->findOneByUsername('fred'));

$album2 = new Album();
$album2->setTitle('Album two');
$album2->setUser($dm->getRepository('UserBundle:User')->findOneByUsername('fred'));
$album2->publish();

$album3 = new Album();
$album3->setTitle('Album three');
$album3->setUser($dm->getRepository('UserBundle:User')->findOneByUsername('clyde'));
$album3->publish();

$album4 = new Album();
$album4->setTitle('Album four');
$album4->setUser($dm->getRepository('UserBundle:User')->findOneByUsername('bonnie'));
$album4->publish();

foreach(array($album1, $album2, $album3) as $album) {
    $multiplicator = 'Album two' === $album->getTitle() ? 7 : 1;
    for($it = 1; $it <= $multiplicator; $it++) {
        foreach(array('photo1', 'photo2', 'photo3') as $photoName) {
            $photo = new Photo();
            $photo->setTitle($photoName.' '.$multiplicator);
            $photo->setMainImage(new File(__DIR__.'/../../images/'.$photoName.'.jpg'));

            $album->addPhoto($photo);
        }
    }
}

unset($album);
