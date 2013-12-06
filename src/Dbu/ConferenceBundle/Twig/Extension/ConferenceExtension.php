<?php

namespace Dbu\ConferenceBundle\Twig\Extension;

use Dbu\ConferenceBundle\Document\Speaker;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ConferenceExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $routeGenerator;

    public function __construct(UrlGeneratorInterface $routeGenerator)
    {
        $this->routeGenerator = $routeGenerator;
    }
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('speaker_path', array($this, 'createSpeakerPath'))
        );
    }

    public function createSpeakerPath(Speaker $speaker)
    {
        return $this->routeGenerator->generate($speaker->getParent()) . '#' . $speaker->getName();
    }

    public function getName()
    {
        return 'conference_extension';
    }
}
