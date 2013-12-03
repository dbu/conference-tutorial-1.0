<?php

namespace Sandbox\MainBundle\DataFixtures\PHPCR;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use PHPCR\Util\PathHelper;
use Symfony\Cmf\Bundle\SimpleCmsBundle\Doctrine\Phpcr\Page;
use Symfony\Component\DependencyInjection\ContainerAware;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\PHPCR\DocumentManager;
use PHPCR\Util\NodeHelper;
use Dbu\ConferenceBundle\Document\Room;


class LoadOverviewData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{
    public function getOrder()
    {
        return 10;
    }

    /**
     * @param DocumentManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $path = $this->container->getParameter('dbu_conference.home_path');

        $session = $manager->getPhpcrSession();
        if ($session->nodeExists($path)) {
            $session->getNode($path)->remove();
            $manager->flush();
        } else {
            NodeHelper::createPath($manager->getPhpcrSession(), PathHelper::getParentPath($path));
        }

        $page = new Page();
        $page->setId($path);
        $page->setTitle('Our lovely conference');
        $page->setBody('Welcome to the conference website.');
        $manager->persist($page);

        $schedule = new Page();
        $schedule->setPosition($page, 'schedule');
        $schedule->setLabel('Schedule');
        $schedule->setTitle('Conference Schedule');
        $schedule->setBody('');
        $manager->persist($schedule);

        $schedule = new Page();
        $schedule->setPosition($page, 'speakers');
        $schedule->setLabel('Speakers');
        $schedule->setTitle('Your friendly speakers');
        $schedule->setBody('');
        $manager->persist($schedule);

        $manager->flush();
    }
}
