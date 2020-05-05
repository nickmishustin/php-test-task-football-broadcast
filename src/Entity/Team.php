<?php

namespace App\Entity;

class Team
{
    private const POS_FORWARD  = 'Нападающий';
    private const POS_HALFBACK  = 'Полузащитник';
    private const POS_BACK  = 'Защитник';
    private const POS_GOALKEEPER  = 'Вратарь';

    private string $name;
    private string $country;
    private string $logo;
    /**
     * @var Player[]
     */
    private array $players;
    private string $coach;
    private int $goals;

    private array $positionsTime  = [
        self::POS_FORWARD => 0,
        self::POS_HALFBACK => 0,
        self::POS_BACK  => 0,
        self::POS_GOALKEEPER => 0
    ];

    private array $positionsNames  = [
        'Н' => self::POS_FORWARD,
        'П' => self::POS_HALFBACK,
        'З' => self::POS_BACK,
        'В' => self::POS_GOALKEEPER
    ];

    public function __construct(string $name, string $country, string $logo, array $players, string $coach)
    {
        $this->assertCorrectPlayers($players);

        $this->name = $name;
        $this->country = $country;
        $this->logo = $logo;
        $this->players = $players;
        $this->coach = $coach;
        $this->goals = 0;
    }

    public function getPositionsTime(): array
    {
     
        $players = $this->getPlayers();

        foreach ($players as $player){
            $this->positionsTime[
                $this->positionsNames[$player->getPosition()]
            ] += $player->getPlayTime();
        }

        return $this->positionsTime;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getLogo(): string
    {
        return $this->logo;
    }

    /**
     * @return Player[]
     */
    public function getPlayersOnField(): array
    {
        return array_filter($this->players, function (Player $player) {
            return $player->isPlay();
        });
    }

    public function getPlayers(): array
    {
        return $this->players;
    }

    public function getPlayer(int $number): Player
    {
        foreach ($this->players as $player) {
            if ($player->getNumber() === $number) {
                return $player;
            }
        }

        throw new \Exception(
            sprintf(
                'Player with number "%d" not play in team "%s".',
                $number,
                $this->name
            )
        );
    }

    public function getCoach(): string
    {
        return $this->coach;
    }

    public function addGoal(): void
    {
        $this->goals += 1;
    }

    public function getGoals(): int
    {
        return $this->goals;
    }


    private function assertCorrectPlayers(array $players)
    {
        foreach ($players as $player) {
            if (!($player instanceof Player)) {
                throw new \Exception(
                    sprintf(
                        'Player should be instance of "%s". "%s" given.',
                        Player::class,
                        get_class($player)
                    )
                );
            }
        }
    }
}