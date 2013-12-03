<?php

namespace Dbu\ConferenceBundle\Document;

use Symfony\Cmf\Bundle\SimpleCmsBundle\Doctrine\Phpcr\Page;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * Conference rooms are a special type of page
 *
 * @PHPCR\Document(referenceable=true)
 */
class Room extends Page
{
    /**
     * @var string
     * @PHPCR\String
     *
     * A short description how to find the room.
     */
    private $description;

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }


}