<?php

namespace Dbu\ConferenceBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Image;
use Symfony\Cmf\Bundle\SimpleCmsBundle\Doctrine\Phpcr\Page;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * For speakers, the body is the bio.
 *
 * @PHPCR\Document(referenceable=true)
 */
class Speaker extends Page
{
    /**
     * @PHPCR\Referrers(referringDocument="Dbu\ConferenceBundle\Document\Presentation", referencedBy="speakers")
     */
    private $presentations;

    /**
     * @PHPCR\Child(nodeName="portrait.jpg")
     *
     * @var Image
     */
    private $portrait;

    public function __construct()
    {
        parent::__construct();

        $this->presentations= new ArrayCollection();
    }

    /**
     * @param string $fullname
     */
    public function setFullname($fullname)
    {
        $this->setTitle($fullname);
    }

    /**
     * @return string
     */
    public function getFullname()
    {
        return $this->title;
    }

    /**
     * @return Presentation[]
     */
    public function getPresentations()
    {
        return $this->presentations;
    }

    /**
     * Set the portrait image for this speaker.
     *
     * Setting null will do nothing, as this is what happens when you edit this
     * speaker in a form without uploading a replacement file.
     *
     * @param Image|UploadedFile|null $portrait
     */
    public function setPortrait($portrait = null)
    {
        if (!$portrait) {
            return;
        }

        if (!$portrait instanceof Image && !$portrait instanceof UploadedFile) {
            $type = is_object($portrait) ? get_class($portrait) : gettype($portrait);

            throw new \InvalidArgumentException(sprintf(
                'Image is not a valid type, "%s" given.',
                $type
            ));
        }

        if ($this->portrait) {
            // existing image, only update content
            // TODO: https://github.com/doctrine/phpcr-odm/pull/262
            $this->portrait->copyContentFromFile($portrait);
        } elseif ($portrait instanceof Image) {
            $this->portrait = $portrait;
        } else {
            $this->portrait = new Image();
            $this->portrait->copyContentFromFile($portrait);
        }
    }

    /**
     * @return Image
     */
    public function getPortrait()
    {
        return $this->portrait;
    }

    public function __toString()
    {
        return $this->getFullname();
    }
}
