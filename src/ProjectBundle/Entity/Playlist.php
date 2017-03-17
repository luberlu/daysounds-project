<?php

namespace ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use ProjectUserBundle\Entity\User;

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
     * @Assert\NotBlank()
     * @Assert\Length(min = 3,
     *                minMessage="The name of playlist must be at least {{ limit }} characters."
     * )
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     * @Assert\NotBlank()
     * @ORM\Column(name="position", type="integer")
     *
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


    /**
     * @ORM\ManyToOne(targetEntity="ProjectUserBundle\Entity\User")
     */
    protected $user;


    public function _construct()
    {
        $this->sounds = new ArrayCollection();
        $this->user = new ArrayCollection();
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
        $this->sounds = new ArrayCollection();
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

    /**
     * Set user
     *
     * @param \ProjectUserBundle\Entity\User $user
     *
     * @return Playlist
     */
    public function setUser(\ProjectUserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ProjectUserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
