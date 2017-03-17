<?php

namespace ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Wallpaper
 *
 * @ORM\Table(name="wallpaper")
 * @ORM\Entity(repositoryClass="ProjectBundle\Repository\WallpaperRepository")
 */
class Wallpaper
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
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var bool
     *
     * @ORM\Column(name="isDefault", type="boolean")
     */
    private $isDefault;


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
     * Set path
     *
     * @param string $path
     *
     * @return Wallpaper
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set isDefault
     *
     * @param boolean $isDefault
     *
     * @return Wallpaper
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * Get isDefault
     *
     * @return bool
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }
}
