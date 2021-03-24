<?php

namespace App\Entity;

use App\ORM\Annotation\ServerAware;
use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=PlayerRepository::class)
 *
 * @ServerAware(fieldName="server_id")
 */
class Player
{
    use TimestampableEntity;

    const STATUS_ADMIN = 'a';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'i';
    const STATUS_LONG_INACTIVE = 'I';
    const STATUS_VACATION = 'v';
    const STATUS_VACATION_INACTIVE = 'vi';
    const STATUS_VACATION_LONG_INACTIVE = 'vI';
    const STATUS_BANNED = 'vb';
    const STATUS_BANNED_INACTIVE = 'vib';
    const STATUS_BANNED_LONG_INACTIVE = 'vIb';
    const STATUS_HONORABLE = 'o';

    const CLASS_NOT_SET = 0;
    const CLASS_COLLECTOR = 1;
    const CLASS_GENERAL = 2;
    const CLASS_DISCOVER = 3;

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
     * @ORM\Column(type="integer")
     */
    private $score;

    /**
     * @ORM\Column(type="integer")
     */
    private $scorePosition;

    /**
     * @ORM\Column(type="integer")
     */
    private $scoreAt24H;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateOfScoreAt24H;

    /**
     * @ORM\Column(type="integer")
     */
    private $economyScore;

    /**
     * @ORM\Column(type="integer")
     */
    private $researchScore;

    /**
     * @ORM\Column(type="integer")
     */
    private $militaryScore;

    /**
     * @ORM\Column(type="integer")
     */
    private $militaryScorePosition;

    /**
     * @ORM\Column(type="integer")
     */
    private $militaryBuiltScore;

    /**
     * @ORM\Column(type="integer")
     */
    private $militaryDestroyedScore;

    /**
     * @ORM\Column(type="integer")
     */
    private $militaryLostScore;

    /**
     * @ORM\Column(type="integer")
     */
    private $militaryHonorScore;

    /**
     * @ORM\Column(type="integer")
     */
    private $militaryShipsScore;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    public function __construct()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->planets = new ArrayCollection();
        $this->score = 0;
        $this->scorePosition = 0;
        $this->scoreAt24H = 0;
        $this->dateOfScoreAt24H = new \DateTimeImmutable();
        $this->economyScore = 0;
        $this->researchScore = 0;
        $this->militaryScore = 0;
        $this->militaryScorePosition = 0;
        $this->militaryBuiltScore = 0;
        $this->militaryDestroyedScore = 0;
        $this->militaryLostScore = 0;
        $this->militaryHonorScore = 0;
        $this->militaryShipsScore = 0;
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

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getEconomyScore(): ?int
    {
        return $this->economyScore;
    }

    public function setEconomyScore(int $economyScore): self
    {
        $this->economyScore = $economyScore;

        return $this;
    }

    public function getResearchScore(): ?int
    {
        return $this->researchScore;
    }

    public function setResearchScore(int $researchScore): self
    {
        $this->researchScore = $researchScore;

        return $this;
    }

    public function getMilitaryScore(): ?int
    {
        return $this->militaryScore;
    }

    public function setMilitaryScore(int $militaryScore): self
    {
        $this->militaryScore = $militaryScore;

        return $this;
    }

    public function getMilitaryBuiltScore(): ?int
    {
        return $this->militaryBuiltScore;
    }

    public function setMilitaryBuiltScore(int $militaryBuiltScore): self
    {
        $this->militaryBuiltScore = $militaryBuiltScore;

        return $this;
    }

    public function getMilitaryDestroyedScore(): ?int
    {
        return $this->militaryDestroyedScore;
    }

    public function setMilitaryDestroyedScore(int $militaryDestroyedScore): self
    {
        $this->militaryDestroyedScore = $militaryDestroyedScore;

        return $this;
    }

    public function getMilitaryLostScore(): ?int
    {
        return $this->militaryLostScore;
    }

    public function setMilitaryLostScore(int $militaryLostScore): self
    {
        $this->militaryLostScore = $militaryLostScore;

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->getName();
    }

    public function getMilitaryHonorScore(): ?int
    {
        return $this->militaryHonorScore;
    }

    public function setMilitaryHonorScore(int $militaryHonorScore): self
    {
        $this->militaryHonorScore = $militaryHonorScore;

        return $this;
    }

    public function getMilitaryShipsScore(): ?int
    {
        return $this->militaryShipsScore;
    }

    public function setMilitaryShipsScore(int $militaryShipsScore): self
    {
        $this->militaryShipsScore = $militaryShipsScore;

        return $this;
    }

    public function getScoreAt24H(): ?int
    {
        return $this->scoreAt24H;
    }

    public function setScoreAt24H(int $scoreAt24H): self
    {
        $this->scoreAt24H = $scoreAt24H;

        return $this;
    }

    public function getDateOfScoreAt24H(): ?\DateTimeInterface
    {
        return $this->dateOfScoreAt24H;
    }

    public function setDateOfScoreAt24H(\DateTimeInterface $dateOfScoreAt24H): self
    {
        $this->dateOfScoreAt24H = $dateOfScoreAt24H;

        return $this;
    }

    public function getScorePosition(): ?int
    {
        return $this->scorePosition;
    }

    public function setScorePosition(int $scorePosition): self
    {
        $this->scorePosition = $scorePosition;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->getStatus() === self::STATUS_ACTIVE;
    }

    public function getMilitaryScorePosition(): ?int
    {
        return $this->militaryScorePosition;
    }

    public function setMilitaryScorePosition(int $militaryScorePosition): self
    {
        $this->militaryScorePosition = $militaryScorePosition;

        return $this;
    }
}
