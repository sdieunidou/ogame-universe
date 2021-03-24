<?php

namespace App\Entity;

use App\ORM\Annotation\ServerAware;
use App\Repository\SpyRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=SpyRepository::class)
 *
 * @ServerAware(fieldName="server_id")
 */
class Spy
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
    private $apiKey;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $data = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Server")
     */
    private $server;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player")
     */
    private $player;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $playerOgameId;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $playerName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $playerClass;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Planet")
     */
    private $planet;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isMoon;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $activity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $coordinates;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $system;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $galaxy;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $totalShip;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $totalDefense;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $totalShipScore;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $totalDefenseScore;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $lootPercentage;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $spyAt;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $metal;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $crystal;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $deuterium;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $energy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getPlayerOgameId(): ?int
    {
        return $this->playerOgameId;
    }

    public function setPlayerOgameId(?int $playerOgameId): self
    {
        $this->playerOgameId = $playerOgameId;

        return $this;
    }

    public function getPlayerName(): ?string
    {
        return $this->playerName;
    }

    public function setPlayerName(?string $playerName): self
    {
        $this->playerName = $playerName;

        return $this;
    }

    public function getPlayerClass(): ?int
    {
        return $this->playerClass;
    }

    public function setPlayerClass(?int $playerClass): self
    {
        $this->playerClass = $playerClass;

        return $this;
    }

    public function getIsMoon(): ?bool
    {
        return $this->isMoon;
    }

    public function setIsMoon(?bool $isMoon): self
    {
        $this->isMoon = $isMoon;

        return $this;
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

    public function getCoordinates(): ?string
    {
        return $this->coordinates;
    }

    public function setCoordinates(?string $coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getSystem(): ?int
    {
        return $this->system;
    }

    public function setSystem(?int $system): self
    {
        $this->system = $system;

        return $this;
    }

    public function getGalaxy(): ?int
    {
        return $this->galaxy;
    }

    public function setGalaxy(?int $galaxy): self
    {
        $this->galaxy = $galaxy;

        return $this;
    }

    public function getTotalShip(): ?int
    {
        return $this->totalShip;
    }

    public function setTotalShip(?int $totalShip): self
    {
        $this->totalShip = $totalShip;

        return $this;
    }

    public function getTotalDefense(): ?int
    {
        return $this->totalDefense;
    }

    public function setTotalDefense(?int $totalDefense): self
    {
        $this->totalDefense = $totalDefense;

        return $this;
    }

    public function getTotalShipScore(): ?int
    {
        return $this->totalShipScore;
    }

    public function setTotalShipScore(?int $totalShipScore): self
    {
        $this->totalShipScore = $totalShipScore;

        return $this;
    }

    public function getTotalDefenseScore(): ?int
    {
        return $this->totalDefenseScore;
    }

    public function setTotalDefenseScore(?int $totalDefenseScore): self
    {
        $this->totalDefenseScore = $totalDefenseScore;

        return $this;
    }

    public function getLootPercentage(): ?int
    {
        return $this->lootPercentage;
    }

    public function setLootPercentage(?int $lootPercentage): self
    {
        $this->lootPercentage = $lootPercentage;

        return $this;
    }

    public function getSpyAt(): ?\DateTimeInterface
    {
        return $this->spyAt;
    }

    public function setSpyAt(?\DateTimeInterface $spyAt): self
    {
        $this->spyAt = $spyAt;

        return $this;
    }

    public function getMetal(): ?int
    {
        return $this->metal;
    }

    public function setMetal(?int $metal): self
    {
        $this->metal = $metal;

        return $this;
    }

    public function getCrystal(): ?int
    {
        return $this->crystal;
    }

    public function setCrystal(?int $crystal): self
    {
        $this->crystal = $crystal;

        return $this;
    }

    public function getDeuterium(): ?int
    {
        return $this->deuterium;
    }

    public function setDeuterium(?int $deuterium): self
    {
        $this->deuterium = $deuterium;

        return $this;
    }

    public function getEnergy(): ?int
    {
        return $this->energy;
    }

    public function setEnergy(?int $energy): self
    {
        $this->energy = $energy;

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

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

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
}
