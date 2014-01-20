<?php

namespace Sandbox\MainBundle\DataFixtures\PHPCR;

use Dbu\ConferenceBundle\Document\Room;
use Dbu\ConferenceBundle\Document\Speaker;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\PHPCR\DocumentManager;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Image;
use Dbu\ConferenceBundle\Document\Presentation;

class LoadSpeakerData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{
    public function getOrder()
    {
        return 40;
    }

    /**
     * @param DocumentManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $path = $this->container->getParameter('dbu_conference.home_path');

        $speakers = $manager->find(null, $path . '/speakers');
        $rooms = $manager->find(null, $path . '/rooms');

        $room = $rooms->getChildren()->first();
        foreach ($this->getFirstRoomSpeakerData() as $data) {
            $this->createSpeaker($manager, $speakers, $room, $data);
        }
        $room = $rooms->getChildren()->last();
        foreach ($this->getSecondRoomSpeakerData() as $data) {
            $this->createSpeaker($manager, $speakers, $room, $data);
        }

        $manager->flush();
    }

    /**
     * @return Presentation instance with the specified information
     */
    protected function createSpeaker(DocumentManager $manager, $parent, Room $room, $data)
    {
        $speaker = new Speaker();
        $speaker->setPosition($parent, $data['slug']);
        $speaker->setFullname($data['fullname']);
        $speaker->setBody($data['body']);
        if (isset($data['portrait'])) {
            $image = new Image();
            $image->setFileContentFromFilesystem(
                __DIR__ .
                DIRECTORY_SEPARATOR .
                'images' .
                DIRECTORY_SEPARATOR .
                $data['portrait']
            );
            $speaker->setPortrait($image);
        }
        $speaker->setPublishStartDate($data['publishStartDate']);

        /** @var $presentation Presentation */
        $presentation = $room->getChildren()->get($data['presentation']);
        $presentation->addSpeaker($speaker);

        $manager->persist($speaker);

        return $speaker;
    }

    private function getFirstRoomSpeakerData()
    {
        return array(
            array(
                'slug' => 'matthias-noback',
                'presentation' => 'diving-deep-into-twig',
                'fullname' => 'Matthias Noback',
                'body' => 'In 2002 I started as a freelance web designer and went from HTML to JavaScript, to PHP. Since 2004 I run my own company - Plato Webdesign. After several years of working solo, I became a web developer for Driebit (Amsterdam) and made myself familiar with symfony 1.0, up until 1.4. With the arrival of Symfony2, it has become clear that the world of PHP should be taken more seriously every day.',
                'portrait' => 'matthias-noback.jpg',
                'publishStartDate' => new \DateTime('2013-12-10'),
            ),
            array(
                'slug' => 'william-durand',
                'presentation' => 'build-awesome-rest-apis-with-symfony2',
                'fullname' => 'William Durand',
                'body' => 'Student by day, full stack developer by night. Open-Source evangelist all the time.',
                'portrait' => 'william-durand.jpg',
                'publishStartDate' => new \DateTime('2013-12-09'),
            ),
            array(
                'slug' => 'lukas-kahwe-smith',
                'presentation' => 'build-awesome-rest-apis-with-symfony2',
                'fullname' => 'Lukas Kahwe Smith',
                'body' => 'I have been involved in PEAR, PHP internals, Doctrine and as of late mostly with Symfony2, especially the CMF initiative.',
                'portrait' => 'lukas-kahwe-smith.jpg',
                'publishStartDate' => new \DateTime('2013-12-08'),
            ),
            array(
                'slug' => 'gediminas-morkevicius',
                'presentation' => 'increase-productivity-with-doctrine2-extensions',
                'fullname' => 'Gediminas Morkevicius',
                'body' => '...',
                'publishStartDate' => new \DateTime('2013-12-07'),
            ),
            array(
                'slug' => 'david-buchmann',
                'presentation' => 'symfony2-content-management-in-40-minutes',
                'fullname' => 'David Buchmann',
                'body' => '...',
                'publishStartDate' => new \DateTime('2013-12-06'),
            ),
            array(
                'slug' => 'ryan-weaver',
                'presentation' => 'cool-like-frontend-developer',
                'fullname' => 'Ryan Weaver',
                'body' => '...',
                'publishStartDate' => new \DateTime('2013-12-05'),
            ),
        );
    }

    private function getSecondRoomSpeakerData()
    {
        return array(
            array(
                'slug' => 'gregoire-pineau',
                'presentation' => 'how-to-automatize-your-infrastructure-with-chef',
                'fullname' => 'Grégoire Pineau',
                'body' => '...',
                'publishStartDate' => new \DateTime('2013-12-05'),
            ),
            array(
                'slug' => 'piotr-pasich',
                'presentation' => 'simplify-your-code-with-annotations',
                'fullname' => 'Piotr Pasich',
                'body' => '...',
                'publishStartDate' => new \DateTime('2013-12-04'),
            ),
            array(
                'slug' => 'kris-wallsmith',
                'presentation' => 'how-kris-writes-symfony-apps',
                'fullname' => 'Kris Wallsmith',
                'body' => '...',
                'publishStartDate' => new \DateTime('2013-12-03'),
            ),
            array(
                'slug' => 'john-la',
                'presentation' => 'pitching-symfony-to-your-client',
                'fullname' => 'John La',
                'body' => '...',
                'publishStartDate' => new \DateTime('2013-12-02'),
            ),
            array(
                'slug' => 'cathy-theys',
                'presentation' => 'community-building-with-mentoring',
                'fullname' => 'Cathy Theys',
                'body' => '...',
                'publishStartDate' => new \DateTime('2013-12-01'),
            ),
        );
    }
}
