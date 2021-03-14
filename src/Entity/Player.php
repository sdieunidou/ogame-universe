<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=PlayerRepository::class)
 */
class Player
{
    use TimestampableEntity;

    const STATUS_ADMIN = 'a';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'i';
    const STATUS_VACATION = 'v';
    const STATUS_BANNED = 'vI';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Server", inversedBy="players")
     */
    private $server;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Alliance", inversedBy="players")
     */
    private $alliance;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Planet", mappedBy="player")
     */
    private $planets;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $ogameId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    public function __construct()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->planets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOgameId(): ?int
    {
        return $this->ogameId;
    }

    public function setOgameId(int $ogameId): self
    {
        $this->ogameId = $ogameId;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getServer(): ?Server
    {
        return $this->server;
    }

    public function setServer(?Server $server): self
    {
        $this->server = $server;

        return $this;
    }

    public function getAlliance(): ?Alliance
    {
        return $this->alliance;
    }

    public function setAlliance(?Alliance $alliance): self
    {
        $this->alliance = $alliance;

        return $this;
    }

    /**
     * @return Collection|Planet[]
     */
    public function getPlanets(): Collection
    {
        return $this->planets;
    }

    public function addPlanet(Planet $planet): self
    {
        if (!$this->planets->contains($planet)) {
            $this->planets[] = $planet;
            $planet->setPlayer($this);
        }

        return $this;
    }

    public function removePlanet(Planet $planet): self
    {
        if ($this->planets->removeElement($planet)) {
            // set the owning side to null (unless already changed)
            if ($planet->getPlayer() === $this) {
                $planet->setPlayer(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->getName();
    }
}
