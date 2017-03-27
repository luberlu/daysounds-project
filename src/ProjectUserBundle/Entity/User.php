<?php

namespace ProjectUserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="ProjectUserBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 * @Vich\Uploadable
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    // ..... other fields

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Assert\Image(
     *      maxSize="2M",
     *      mimeTypes={"image/png", "image/jpeg", "image/pjpeg"},
     *      minWidth = 200,
     *      maxWidth = 1000,
     *      minHeight = 200,
     *      maxHeight = 1000,
     *      maxRatio = 1
     * )
     * @Vich\UploadableField(mapping="user_avatar", fileNameProperty="imageName")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $imageName;


    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string")
     */
    protected $slug;

    /**
     * @ORM\ManyToMany(targetEntity="ProjectUserBundle\Entity\User", inversedBy="relation_user_of")
     * @ORM\JoinTable(name="user_relations",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="follower_user_id", referencedColumnName="id")}
     * )
     */
    protected $relation_user;


    /**
     * @ORM\ManyToMany(targetEntity="ProjectUserBundle\Entity\User",  mappedBy="relation_user")
     */
    protected $relation_user_of;


    /**
     * @ORM\OneToMany(targetEntity="ProjectBundle\Entity\Playlist", cascade={"remove"}, mappedBy="user", orphanRemoval=true)
     */
    protected $playlists;


    public function _construct()
    {
        $this->relation_user = new ArrayCollection();
    }

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    public function getParent()
    {
        return 'FOSUserBundle';
    }


    public function setUsername($username)
    {
        $this->username = $username;
        $this->setSlug($this->username);

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return User
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param string $imageName
     *
     * @return User
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageName()
    {
        return $this->imageName;
    }


    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('#[^\\pL\d]+#u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        if (function_exists('iconv'))
        {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('#[^-\w]+#', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }


    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return User
     */
    public function setSlug($slug)
    {
        $this->slug = $this->slugify($slug);

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     *
     * @return User
     */
    public function setDateAdd($dateAdd)
    {
        $this->dateAdd = $dateAdd;
        return $this;
    }

    /**
     * Get dateAdd
     *
     * @return \DateTime
     */
    public function getDateAdd()
    {
        return $this->dateAdd;
    }

    /**
     * Add relationUser
     *
     * @param \ProjectUserBundle\Entity\User $relationUser
     *
     * @return User
     */
    public function addRelationUser(\ProjectUserBundle\Entity\User $relationUser)
    {
        $this->relation_user[] = $relationUser;

        return $this;
    }

    /**
     * Remove relationUser
     *
     * @param \ProjectUserBundle\Entity\User $relationUser
     */
    public function removeRelationUser(\ProjectUserBundle\Entity\User $relationUser)
    {
        $this->relation_user->removeElement($relationUser);
    }

    /**
     * Get relationUser
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRelationUser()
    {
        return $this->relation_user;
    }

    /**
     * Add playlist
     *
     * @param \ProjectBundle\Entity\Playlist $playlist
     *
     * @return User
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
     * Add relationUserOf
     *
     * @param \ProjectUserBundle\Entity\User $relationUserOf
     *
     * @return User
     */
    public function addRelationUserOf(\ProjectUserBundle\Entity\User $relationUserOf)
    {
        $this->relation_user_of[] = $relationUserOf;

        return $this;
    }

    /**
     * Remove relationUserOf
     *
     * @param \ProjectUserBundle\Entity\User $relationUserOf
     */
    public function removeRelationUserOf(\ProjectUserBundle\Entity\User $relationUserOf)
    {
        $this->relation_user_of->removeElement($relationUserOf);
    }

    /**
     * Get relationUserOf
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRelationUserOf()
    {
        return $this->relation_user_of;
    }
}
