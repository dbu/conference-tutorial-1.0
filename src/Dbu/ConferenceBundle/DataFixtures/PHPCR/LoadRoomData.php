<?php

namespace Sandbox\MainBundle\DataFixtures\PHPCR;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\PHPCR\DocumentManager;
use PHPCR\Util\NodeHelper;
use Dbu\ConferenceBundle\Document\Room;


class LoadRoomData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{
    public function getOrder()
    {
        return 20;
    }

    /**
     * @param DocumentManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $path = $this->container->getParameter('dbu_conference.home_path');

        NodeHelper::createPath($manager->getPhpcrSession(), $path . '/rooms');

        $base = $manager->find(null, $path . '/rooms');

        $this->createRoom($manager, $base, 'copernicus', 'Copernicus', 'Copernicus is the main floor for large sessions.', 'On the first floor');
        $this->createRoom($manager, $base, 'taurus', 'Taurus', 'Taurus is a room for smaller sessions.', 'On the second floor');

        $manager->flush();
    }

    /**
     * @return Room instance with the specified information
     */
    protected function createRoom(DocumentManager $manager, $parent, $name, $title, $body, $description)
    {
        $room = new Room();
        $room->setPosition($parent, $name);
        $room->setAddLocalePattern(true);
        $room->setDescription($description);
        $room->setTitle($title);
        $room->setBody($body);

        $manager->persist($room);

        return $room;
    }
}
