<?php

namespace App\Entity;

use App\ORM\Annotation\ServerAware;
use App\Repository\PlanetRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlanetRepository::class)
 *
 * @ServerAware(fieldName="server_id")
 */
class Planet
{
    const TYPE_PLANET = 1;
    const TYPE_DEBRIS = 2;
    const TYPE_MOON = 3;

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
    private $ogameId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $coordinates;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @ORM\Column(type="integer")
     */
    private $system;

    /**
     * @ORM\Column(type="integer")
     */
    private $galaxy;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasMoon;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $moonOgameId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $moonSize;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $moonName;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", inversedBy="planets")
     */
    private $player;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Server", inversedBy="planets")
     */
    private $server;

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

    public function getCoordinates(): ?string
    {
        return $this->coordinates;
    }

    public function setCoordinates(string $coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    public function getHasMoon(): ?bool
    {
        return $this->hasMoon;
    }

    public function setHasMoon(bool $hasMoon): self
    {
        $this->hasMoon = $hasMoon;

        return $this;
    }

    public function getMoonOgameId(): ?int
    {
        return $this->moonOgameId;
    }

    public function setMoonOgameId(?int $moonOgameId): self
    {
        $this->moonOgameId = $moonOgameId;

        return $this;
    }

    public function getMoonSize(): ?int
    {
        return $this->moonSize;
    }

    public function setMoonSize(?int $moonSize): self
    {
        $this->moonSize = $moonSize;

        return $this;
    }

    public function getMoonName(): ?string
    {
        return $this->moonName;
    }

    public function setMoonName(?string $moonName): self
    {
        $this->moonName = $moonName;

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

    public function __toString(): string
    {
        return (string) $this->getCoordinates();
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getSystem(): ?int
    {
        return $this->system;
    }

    public function setSystem(int $system): self
    {
        $this->system = $system;

        return $this;
    }

    public function getGalaxy(): ?int
    {
        return $this->galaxy;
    }

    public function setGalaxy(int $galaxy): self
    {
        $this->galaxy = $galaxy;

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
}
