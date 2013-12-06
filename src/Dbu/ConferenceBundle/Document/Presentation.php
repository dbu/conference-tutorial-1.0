<?php

namespace Dbu\ConferenceBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Cmf\Bundle\SimpleCmsBundle\Doctrine\Phpcr\Page;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * A presentation is a special type of page. Its parent is a room.
 *
 * @PHPCR\Document(referenceable=true)
 */
class Presentation extends Page
{
    /**
     * @var \DateTime
     * @PHPCR\Date
     *
     * Start time of the presentation
     */
    private $start;

    /**
     * @var Speaker[]|Collection
     * @PHPCR\ReferenceMany(targetDocument="Dbu\ConferenceBundle\Document\Speaker")
     */
    private $speakers;

    public function __construct()
    {
        parent::__construct();

        $this->start = new \DateTime();
        $this->speakers = new ArrayCollection();
    }

    /**
     * @param \DateTime $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param Speaker[] $speakers
     */
    public function setSpeakers($speakers)
    {
        $this->speakers = $speakers;
    }

    /**
     * @return Speaker[]
     */
    public function getSpeakers()
    {
        return $this->speakers;
    }

    public function addSpeaker(Speaker $speaker)
    {
        $this->speakers->add($speaker);
    }

    public function removeSpeaker(Speaker $speaker)
    {
        $this->speakers->removeElement($speaker);
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}
