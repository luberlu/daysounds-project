<?php

namespace ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Playlist
 *
 * @ORM\Table(name="playlist")
 * @ORM\Entity(repositoryClass="ProjectBundle\Repository\PlaylistRepository")
 */
class Playlist
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="position", type="string", length=255)
     */
    private $position;

    /**
     * @ORM\ManyToMany(targetEntity="Sound")
     * @ORM\JoinTable(name="playlist_sounds",
     *                 joinColumns={@ORM\JoinColumn(name="playlist_id", referencedColumnName="id")},
     *                  inverseJoinColumns={@ORM\JoinColumn(name="sound_id", referencedColumnName="id")}
     *     )
     */

    protected $sounds;

    public function _construct()
    {
        $this->sounds = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Playlist
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set position
     *
     * @param string $position
     *
     * @return Playlist
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sounds = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add sound
     *
     * @param \ProjectBundle\Entity\Sound $sound
     *
     * @return Playlist
     */
    public function addSound(\ProjectBundle\Entity\Sound $sound)
    {
        $this->sounds[] = $sound;

        return $this;
    }

    /**
     * Remove sound
     *
     * @param \ProjectBundle\Entity\Sound $sound
     */
    public function removeSound(\ProjectBundle\Entity\Sound $sound)
    {
        $this->sounds->removeElement($sound);
    }

    /**
     * Get sounds
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSounds()
    {
        return $this->sounds;
    }
}
