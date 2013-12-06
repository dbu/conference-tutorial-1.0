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
    private $speakersPath;

    public function __construct(UrlGeneratorInterface $routeGenerator, $speakersPath)
    {
        $this->routeGenerator = $routeGenerator;
        $this->speakersPath = $speakersPath;
    }
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('speaker_path', array($this, 'createSpeakerPath'))
        );
    }

    public function createSpeakerPath(Speaker $speaker)
    {
        return $this->routeGenerator->generate($this->speakersPath) . '#' . $speaker->getName();
    }

    public function getName()
    {
        return 'conference_extension';
    }
}
