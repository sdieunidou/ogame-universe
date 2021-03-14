<?php

namespace App\Entity;

use App\Repository\ServerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=ServerRepository::class)
 */
class Server
{
    use TimestampableEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $serverId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $language;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $latestPlayerUpdate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $latestAllianceUpdate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $latestUniverseUpdate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $latestRankingUpdate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Player", mappedBy="server")
     */
    private $players;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Alliance", mappedBy="server")
     */
    private $alliances;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Planet", mappedBy="server")
     */
    private $planets;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->alliances = new ArrayCollection();
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

    public function getServerId(): ?int
    {
        return $this->serverId;
    }

    public function setServerId(int $serverId): self
    {
        $this->serverId = $serverId;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getLatestPlayerUpdate(): ?\DateTimeInterface
    {
        return $this->latestPlayerUpdate;
    }

    public function setLatestPlayerUpdate(?\DateTimeInterface $latestPlayerUpdate): self
    {
        $this->latestPlayerUpdate = $latestPlayerUpdate;

        return $this;
    }

    public function getLatestAllianceUpdate(): ?\DateTimeInterface
    {
        return $this->latestAllianceUpdate;
    }

    public function setLatestAllianceUpdate(?\DateTimeInterface $latestAllianceUpdate): self
    {
        $this->latestAllianceUpdate = $latestAllianceUpdate;

        return $this;
    }

    public function getLatestUniverseUpdate(): ?\DateTimeInterface
    {
        return $this->latestUniverseUpdate;
    }

    public function setLatestUniverseUpdate(?\DateTimeInterface $latestUniverseUpdate): self
    {
        $this->latestUniverseUpdate = $latestUniverseUpdate;

        return $this;
    }

    public function getLatestRankingUpdate(): ?\DateTimeInterface
    {
        return $this->latestRankingUpdate;
    }

    public function setLatestRankingUpdate(?\DateTimeInterface $latestRankingUpdate): self
    {
        $this->latestRankingUpdate = $latestRankingUpdate;

        return $this;
    }

    /**
     * @return Collection|Player[]
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->setServer($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getServer() === $this) {
                $player->setServer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Alliance[]
     */
    public function getAlliances(): Collection
    {
        return $this->alliances;
    }

    public function addAlliance(Alliance $alliance): self
    {
        if (!$this->alliances->contains($alliance)) {
            $this->alliances[] = $alliance;
            $alliance->setServer($this);
        }

        return $this;
    }

    public function removeAlliance(Alliance $alliance): self
    {
        if ($this->alliances->removeElement($alliance)) {
            // set the owning side to null (unless already changed)
            if ($alliance->getServer() === $this) {
                $alliance->setServer(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->getName();
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
            $planet->setServer($this);
        }

        return $this;
    }

    public function removePlanet(Planet $planet): self
    {
        if ($this->planets->removeElement($planet)) {
            // set the owning side to null (unless already changed)
            if ($planet->getServer() === $this) {
                $planet->setServer(null);
            }
        }

        return $this;
    }
}
