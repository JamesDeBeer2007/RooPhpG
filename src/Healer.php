<?php

namespace Game;

class Healer extends Character{

private int $spirit;

private int $originalSpirit;

    public function __construct(string $name, string $role, int $health, int $attack, int $defense = 6, int $range = 3, int $spirit = 200)
    {
        parent::__construct($name, $role, $health, $attack, $defense, $range);
        $this->spirit = $spirit;
        $this->originalSpirit = $spirit;
        $this->specialAttacks = ['healingPrayer', 'divineShield'];
    }

public function getSpirit(): int
{
    return $this->spirit;
}

public function setSpirit(int $spirit): void
{
    $this->spirit = $spirit;
}

    public function getSummary()
    {
        $parentSummary = parent::getSummary();
        $spiritInfo = "<br>Additionally, this healer has {$this->spirit} spirit points.";
        return $parentSummary . "". $spiritInfo; 
    }

public function performHealingPrayer(): ?string
{
    if ($this->spirit < 30) {
        throw new \Exception("Not enough spirit to perform healing prayer.");
    }
    
    $newHealth = $this->getHealth() + 20;
        if ($newHealth > 100) {
            $newHealth = 100;
        }
        $this->setHealth($newHealth);
    
    $modMessage = $this->modifyTemporaryStats(0, 2.0);
    
    $this->spirit -= 30;
    
    return "Performed healing prayer with {$this->tempAttack} power, Defense increased by 200%";
}
    public function castDivineShield()
    {
        if ($this->spirit < 60) {
            throw new \Exception("Not enough spirit to cast the divine shield.");
        }

        $modMessage = $this->modifyTemporaryStats(0.3, 1.5);
        $this->spirit -= 60;

        return "Casted a divine shield with {$this->tempAttack} power, Attack decreased by 70% and Defense increased by 50%";
    }

    public function executeSpecialAttack(string $attackName): string
    {
        switch ($attackName) {
            case 'healingPrayer':
                return $this->performHealingPrayer();
            case 'divineShield':
                return $this->castDivineShield();
            default:
                return "Unknown special attack: {$attackName}";    
        }
    }

    public function resetAttributes(): void
    {
        $this->spirit = $this->originalSpirit;
        $this->resetTempStats();
    }
}