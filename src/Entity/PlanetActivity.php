<?php

namespace App\Entity;

use App\Repository\PlanetActivityRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=PlanetActivityRepository::class)
 */
class PlanetActivity
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $activity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $moonActivity;

    /**
     * @ORM\Column(type="integer")
     */
    private $debrisMetal;

    /**
     * @ORM\Column(type="integer")
     */
    private $debrisCrystal;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $activityAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $moonActivityAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Planet", inversedBy="activities")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $planet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivity(): ?int
    {
        return $this->activity;
    }

    public function setActivity(?int $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    public function getMoonActivity(): ?int
    {
        return $this->moonActivity;
    }

    public function setMoonActivity(?int $moonActivity): self
    {
        $this->moonActivity = $moonActivity;

        return $this;
    }

    public function getDebrisMetal(): ?int
    {
        return $this->debrisMetal;
    }

    public function setDebrisMetal(int $debrisMetal): self
    {
        $this->debrisMetal = $debrisMetal;

        return $this;
    }

    public function getDebrisCrystal(): ?int
    {
        return $this->debrisCrystal;
    }

    public function setDebrisCrystal(int $debrisCrystal): self
    {
        $this->debrisCrystal = $debrisCrystal;

        return $this;
    }

    public function getPlanet(): ?Planet
    {
        return $this->planet;
    }

    public function setPlanet(?Planet $planet): self
    {
        $this->planet = $planet;

        return $this;
    }

    public function getActivityAt(): ?\DateTimeInterface
    {
        return $this->activityAt;
    }

    public function setActivityAt(?\DateTimeInterface $activityAt): self
    {
        $this->activityAt = $activityAt;

        return $this;
    }

    public function getMoonActivityAt(): ?\DateTimeInterface
    {
        return $this->moonActivityAt;
    }

    public function setMoonActivityAt(?\DateTimeInterface $moonActivityAt): self
    {
        $this->moonActivityAt = $moonActivityAt;

        return $this;
    }
}
