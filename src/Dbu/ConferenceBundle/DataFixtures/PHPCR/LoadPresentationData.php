<?php

namespace Sandbox\MainBundle\DataFixtures\PHPCR;

use Dbu\ConferenceBundle\Document\Room;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ContainerBlock;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SimpleBlock;
use Symfony\Component\DependencyInjection\ContainerAware;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\PHPCR\DocumentManager;
use Dbu\ConferenceBundle\Document\Presentation;


class LoadPresentationData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{
    public function getOrder()
    {
        return 30;
    }

    /**
     * @param DocumentManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $path = $this->container->getParameter('dbu_conference.home_path');

        $rooms = $manager->find(null, $path . '/rooms');

        $room = $rooms->getChildren()->first();
        foreach ($this->getFirstRoomData() as $data) {
            $this->createPresentation($manager, $room, $data);
        }
        $room = $rooms->getChildren()->last();
        foreach ($this->getSecondRoomData() as $data) {
            $this->createPresentation($manager, $room, $data);
        }

        $manager->flush();

        // we flush, before flushing the children of the room are not populated.
        $presentation = $rooms->getChildren()->first()->getChildren()->first();
        $additionalInfo = new ContainerBlock();
        $additionalInfo->setParentDocument($presentation);
        $additionalInfo->setName('additionalInfoBlock');
        $manager->persist($additionalInfo);

        $simple = new SimpleBlock();
        $additionalInfo->addChild($simple);
        $simple->setName('note');
        $simple->setTitle('Preparation');
        $simple->setBody('If you want to make the most out of this talk, make sure to read the introduction chapter of the <a href="http://twig.sensiolabs.org/">official twig documentation</a> before the conference.');

        $manager->flush();
    }

    /**
     * @return Presentation instance with the specified information
     */
    protected function createPresentation(DocumentManager $manager, $parent, $data)
    {
        $presentation = new Presentation();
        $presentation->setPosition($parent, $data['slug']);
        $presentation->setAddLocalePattern(true);
        $presentation->setTitle($data['title']);
        $presentation->setBody($data['body']);
        $presentation->setStart($data['start']);
        $presentation->setPublishStartDate($data['publishStartDate']);

        $manager->persist($presentation);

        return $presentation;
    }

    private function getFirstRoomData()
    {
        return array(
            array(
                'slug' => 'diving-deep-into-twig',
                'title' => 'Diving deep into Twig ',
                'body' => 'Developers are able to modify the behavior and possibilities of Twig (the PHP templating engine by Fabien Potencier) in many ways, ranging from very easy to very tough. It all starts with simply registering your extension, but from then on you can choose to create your own filters, functions and tests. I will quickly review your options, and show some best practices. After discussing these basic modifications, we\'ll take a look at creating token parsers and thinking up custom node types, which will enable you to define your own tags (the things between {% %}). I will demonstrate the inner workings of Twig: from the loader, to the lexer, to the parser, to your own token parser, to creating nodes, filtering nodes using a node visitor and finally to the compiler, which transforms all nodes to plain old PHP.',
                'start' => new \DateTime('2013-12-12 10:10:00'),
                'publishStartDate' => new \DateTime('2013-10-12'),
            ),
            array(
                'slug' => 'build-awesome-rest-apis-with-symfony2',
                'title' => 'Build Awesome REST APIs With Symfony2',
                'body' => 'Based on concrete examples, you will learn how to build a REST API using Symfony2 and many third-party libraries in an efficient manner. We will dive into each layer including routing, controllers, serialization, versioning, testing, the security layer and even the documentation (this list is not exhaustive). Overall, this talk describes the state of REST in the Symfony2 world.',
                'start' => new \DateTime('2013-12-12 11:10'),
                'publishStartDate' => new \DateTime('2013-11-15'),
            ),
            array(
                'slug' => 'increase-productivity-with-doctrine2-extensions',
                'title' => 'Increase productivity with Doctrine2 extensions',
                'body' => '...',
                'start' => new \DateTime('2013-12-12 14:20'),
                'publishStartDate' => new \DateTime('2013-04-20'),
            ),
            array(
                'slug' => 'symfony2-content-management-in-40-minutes',
                'title' => 'Symfony2 Content Management in 40 minutes',
                'body' => '...',
                'start' => new \DateTime('2013-12-12 15:20'),
                'publishStartDate' => new \DateTime('2013-05-20'),
            ),
            array(
                'slug' => 'cool-like-frontend-developer',
                'title' => 'Cool like Frontend Developer: Grunt, RequireJS, Bower and other Tools',
                'body' => '...',
                'start' => new \DateTime('2013-12-12 16:30'),
                'publishStartDate' => new \DateTime('2013-06-30'),
            ),
        );
    }

    private function getSecondRoomData()
    {
        return array(
            array(
                'slug' => 'how-to-automatize-your-infrastructure-with-chef',
                'title' => 'How to automatize your infrastructure with Chef',
                'body' => '...',
                'start' => new \DateTime('2013-12-12 10:10'),
                'publishStartDate' => new \DateTime('2013-07-10'),
            ),
            array(
                'slug' => 'simplify-your-code-with-annotations',
                'title' => 'Simplify your code with annotations',
                'body' => '...',
                'start' => new \DateTime('2013-12-12 11:10'),
                'publishStartDate' => new \DateTime('2013-08-10'),
            ),
            array(
                'slug' => 'how-kris-writes-symfony-apps',
                'title' => 'How Kris Writes Symfony Apps',
                'body' => '...',
                'start' => new \DateTime('2013-12-12 14:20'),
                'publishStartDate' => new \DateTime('2013-09-10'),
            ),
            array(
                'slug' => 'pitching-symfony-to-your-client',
                'title' => 'Pitching Symfony to your Client',
                'body' => '...',
                'start' => new \DateTime('2013-12-12 15:20'),
                'publishStartDate' => new \DateTime('2013-10-10'),
            ),
            array(
                'slug' => 'community-building-with-mentoring',
                'title' => 'Community Building with Mentoring: What makes people crazy happy to work on an open source project?',
                'body' => '...',
                'start' => new \DateTime('2013-12-12 16:30'),
                'publishStartDate' => new \DateTime('2013-11-10'),
            ),
        );
    }
}
