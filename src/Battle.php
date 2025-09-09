<?php

namespace Game;

use Game\Character;
use PHPStan\Type\VoidType;

class Battle {
    
    private array $battleLog = [];

    private int $maxRounds = 10;

    private int $roundNumber = 1;


    private int $fighter1OriginalHealth = 100;
    private int $fighter2OriginalHealth = 100;

    private Character $fighter1;
    private Character $fighter2;

    private array $selectedAttacks = ['fighter1' => null, 'fighter2' => null];


    public function __construct(
        Character $fighter1,
        Character $fighter2,
        int $maxRounds = 10)
    {
        $this->fighter1 = $fighter1;
        $this->fighter2 = $fighter2;
        $this->maxRounds = $maxRounds;
        $this->battleLog[] = "Het gevecht is gestart";
        $this->fighter1OriginalHealth = $fighter1->getHealth();
        $this->fighter2OriginalHealth = $fighter2->getHealth();
    }


    public function setAttackForFighter(Character $fighter, ?string $attackName): void
    {
        if ($fighter === $this->fighter1)
        {
            $this->selectedAttacks['fighter1'] = $attackName;
        } elseif ($fighter === $this->fighter2) {
            $this->selectedAttacks['fighter2'] = $attackName;
        }
    }

    public function getBattleLog(): array {
        return $this->battleLog;
    }
    
    public function getMaxRounds(): int {
        return $this->maxRounds;
    }

    public function getRoundNumber(): int {
        return $this->roundNumber;
    }

    public function getFighter1() {
        return $this->fighter1;
    }
    public function getFighter2() {
        return $this->fighter2;
    }

    public function getFighter1OriginalHealth(): int {
        return $this->fighter1OriginalHealth;
    }

    public function getFighter2OriginalHealth(): int {
        return $this->fighter2OriginalHealth;
    }

    public function changeMaxRounds(int $rounds): void {
        $this->maxRounds = $rounds;
    }


    private function executeAttack(Character $attacker, Character $defender, ?string $specialAttack = null)
    {
        if($specialAttack)
        {
            $result = $attacker->executeSpecialAttack($specialAttack);
            $this->battleLog[] = "Special attack performed by {$attacker->getName()}: {$specialAttack}";
            $damage = $this->calculateDamage($attacker, $defender); // Optionally adjust if special attack changes damage
        } else {
            $damage = $this->calculateDamage($attacker, $defender);
        }

        if(method_exists($defender, 'takeDamage')){
            $defender->takeDamage($damage);
        } else {
            $defender->setHealth($defender->getHealth() - $damage);
        }

        $this->battleLog[] = "Ronde {$this->roundNumber}: <strong>{$attacker->getName()}</strong> valt aan en doet <strong>{$damage}</strong> schade aan <strong>{$defender->getName()}</strong>.";
        $this->battleLog[] = "{$defender->getName()} heeft nu <strong>{$defender->getHealth()}</strong> health.";

        if ($defender->getHealth() <= 0) 
        {
            $this->battleLog[] = "<strong>🏆 {$attacker->getName()} heeft gewonnen!</strong>";
            return $this->battleLog;
        }
    }

    public function executeTurn($attacker, $defender): string
    {
        $attack1 = $this->selectedAttacks['fighter1'];
        $this->executeAttack($this->fighter1, $this->fighter2, $attack1);

        if ($this->fighter2->getHealth() > 0) {
            $attack2 = $this->selectedAttacks['fighter2'];
            $this->executeAttack($this->fighter2, $this->fighter1, $attack2);
        }

        $this->selectedAttacks = ['fighter1' => null, 'fighter2' => null];

        $this->fighter1->resetAttributes();
        $this->fighter2->resetAttributes();

        $this->roundNumber++;

        return "Beurt uitgevoerd. Bekijk het gevechtslog voor details.";
    }

    public function endBattle(): void
    {
        $this->fighter1->setHealth($this->fighter1OriginalHealth);
        $this->fighter2->setHealth($this->fighter2OriginalHealth);

        if(method_exists($this->fighter1, 'resetAttributes')) {
            $this->fighter1->resetAttributes();
        }
        if(method_exists($this->fighter2, 'resetAttributes')) {
            $this->fighter2->resetAttributes();
        }
        $this->battleLog[] = "Het gevecht is beëindigd en health is gereset.";
    }

    public function startFight(Character $character1, Character $character2): string {
        $this->battleLog [] = "Het gevecht tussen <strong> {$character1->getName()}</strong> en <strong>{$character2->getName()}</strong> is begonnen!<br><br>";

        while ($character1->getHealth() > 0 && $character2->getHealth() > 0 && $this->roundNumber <= $this->maxRounds) {
            $damage1 = $this->calculateDamage($character1, $character2);
            $character2->setHealth($character2->getHealth() - $damage1);
            $this->logAttack($character1, $character2, $damage1);

            if ($character2->getHealth() <= 0) {
                $this->battleLog [] = "<strong>🏆 {$character1->getName()} heeft gewonnen!</strong><br>";
                return $this->battleLog;
            }

            $damage2 = $this->calculateDamage($character2, $character1);
            $character1->setHealth($character1->getHealth() - $damage2);
            $this->logAttack($character2, $character1, $damage2);

            if ($character1->getHealth() <= 0) {
                $this->battleLog [] = "🏆 <strong>{$character2->getName()} heeft gewonnen!</strong><br>";
                return $this->battleLog;
            }

            $this->roundNumber++;
            $this->battleLog []=  "<br>";
        }

        if ($this->roundNumber > $this->maxRounds) {
            $this->battleLog [] = "<strong>⏳ Het maximum aantal rondes is bereikt. Het gevecht is geëindigd zonder winnaar.</strong><br>";
        }

        return $this->battleLog;
    }

    private function calculateDamage(Character $attacker, Character $defender): int {
        $randomFactor = mt_rand(70, 100) / 100;
        $damage = $attacker->getAttack() * $randomFactor - $defender->getDefense();
        return max(0, (int) round($damage));
    }

  
    private function logAttack(Character $attacker, Character $defender, int $damage): void {
        $this->battleLog [] = "Ronde {$this->roundNumber}: <strong>{$attacker->getName()}</strong> valt aan en doet <strong>{$damage}</strong> schade aan <strong>{$defender->getName()}</strong>.<br>";
        $this->battleLog [] = "{$defender->getName()} heeft nu <strong>{$defender->getHealth()}</strong> health.\n\n";
    }
}
