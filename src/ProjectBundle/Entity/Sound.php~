<?php

namespace ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sound
 *
 * @ORM\Table(name="sound")
 * @ORM\Entity(repositoryClass="ProjectBundle\Repository\SoundRepository")
 */
class Sound
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
     * @ORM\Column(name="artiste", type="string", length=255)
     */
    private $artiste;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=255)
     * @Assert\Url(
     *    message = "The url '{{ value }}' is not a valid url",
     * )
     */
    private $link;

    /**
     * @ORM\ManyToMany(targetEntity="Genre")
     * @ORM\JoinTable(name="sound_genres",
     *                 joinColumns={@ORM\JoinColumn(name="sound_id", referencedColumnName="id")},
     *                  inverseJoinColumns={@ORM\JoinColumn(name="genre_id", referencedColumnName="id")}
     *     )
     */

    protected $genres;


    /**
     * @ORM\ManyToOne(targetEntity="Player")
     * @Assert\NotBlank(message = "Your sound url is not recognize by our system. Only soundcloud or youtube !")
     */
    protected $players;



    public function _construct()
    {
        $this->genres = new ArrayCollection();
        $this->players = new ArrayCollection();
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
     * @return Sound
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
     * Set artiste
     *
     * @param string $artiste
     *
     * @return Sound
     */
    public function setArtiste($artiste)
    {
        $this->artiste = $artiste;

        return $this;
    }

    /**
     * Get artiste
     *
     * @return string
     */
    public function getArtiste()
    {
        return $this->artiste;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return Sound
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->genres = new ArrayCollection();
    }

    /**
     * Add genre
     *
     * @param \ProjectBundle\Entity\Genre $genre
     *
     * @return Sound
     */
    public function addGenre(\ProjectBundle\Entity\Genre $genre)
    {
        $this->genres[] = $genre;

        return $this;
    }

    /**
     * Remove genre
     *
     * @param \ProjectBundle\Entity\Genre $genre
     */
    public function removeGenre(\ProjectBundle\Entity\Genre $genre)
    {
        $this->genres->removeElement($genre);
    }

    /**
     * Get genres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGenres()
    {
        return $this->genres;
    }

    /**
     * Add playlist
     *
     * @param \ProjectBundle\Entity\Playlist $playlist
     *
     * @return Sound
     */
    public function addPlaylist(\ProjectBundle\Entity\Playlist $playlist)
    {
        $this->playlists[] = $playlist;

        return $this;
    }

    /**
     * Remove playlist
     *
     * @param \ProjectBundle\Entity\Playlist $playlist
     */
    public function removePlaylist(\ProjectBundle\Entity\Playlist $playlist)
    {
        $this->playlists->removeElement($playlist);
    }

    /**
     * Get playlists
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlaylists()
    {
        return $this->playlists;
    }

    /**
     * Set players
     *
     * @param \ProjectBundle\Entity\Player $players
     *
     * @return Sound
     */
    public function setPlayers(\ProjectBundle\Entity\Player $players = null)
    {
        $this->players = $players;

        return $this;
    }

    /**
     * Get players
     *
     * @return \ProjectBundle\Entity\Player
     */
    public function getPlayers()
    {
        return $this->players;
    }
}
