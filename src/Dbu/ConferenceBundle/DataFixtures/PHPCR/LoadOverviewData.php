<?php

namespace Sandbox\MainBundle\DataFixtures\PHPCR;

use Dbu\ConferenceBundle\Document\RecentlyAddedBlock;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use PHPCR\Util\PathHelper;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ContainerBlock;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SimpleBlock;
use Symfony\Cmf\Bundle\MenuBundle\Doctrine\Phpcr\MenuNode;
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
        $speakersPath = $this->container->getParameter('dbu_conference.speakers_path');

        $session = $manager->getPhpcrSession();
        if ($session->nodeExists($path)) {
            $session->getNode($path)->remove();
            $manager->flush();
        } else {
            NodeHelper::createPath($session, PathHelper::getParentPath($path));
        }

        if ($session->nodeExists('/cms/content/default')) {
            foreach ($session->getNode('/cms/content/default')->getNodes() as $node) {
                $node->remove();
            }
        } else {
            NodeHelper::createPath($session, '/cms/content/default');
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
        $schedule->setDefault('type', 'schedule');
        $manager->persist($schedule);

        // as the speakersPath is configurable, we need to make sure it exists
        if (!$manager->find(null, PathHelper::getParentPath($speakersPath))) {
            NodeHelper::createPath($manager->getPhpcrSession(), PathHelper::getParentPath($speakersPath));
        }
        $schedule = new Page();
        $schedule->setId($speakersPath);
        $schedule->setLabel('Speakers');
        $schedule->setTitle('Your friendly speakers');
        $schedule->setBody('');
        $schedule->setDefault('_template', 'DbuConferenceBundle:Speaker:overview.html.twig');
        $manager->persist($schedule);

        // add menu entry for our application page
        $menu = new MenuNode();
        $menu->setPosition($page, 'subscribe');
        $menu->setLabel('Sign up');
        $menu->setRoute('dbu_conference_subscribe');
        $manager->persist($menu);

        $additionalInfo = new ContainerBlock();
        $additionalInfo->setParentDocument($manager->find(null, '/cms/content/default'));
        $additionalInfo->setName('additionalInfoBlock');
        $manager->persist($additionalInfo);

        $simple = new SimpleBlock();
        $additionalInfo->addChild($simple); // persist is cascaded
        $simple->setName('note');
        $simple->setTitle('We have cookies');
        $simple->setBody('Not sure if you want to attend the conference? Let us tell you that we have cookies so you\'d better attend!');

        $recent = new RecentlyAddedBlock();
        $additionalInfo->addChild($recent);
        $recent->setName('presentations');

        $manager->flush();
    }
}
