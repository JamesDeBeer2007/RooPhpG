<?php

namespace Game;

class Rogue extends Character
{
    private int $energy ;

    private int $originalEnergy;

        public function __construct(string $name, string $role, int $health, int $attack, int $defense = 4, int $range = 2, int $energy = 100)
    {
        parent::__construct($name, $role, $health, $attack, $defense, $range);
        $this->energy = $energy;
        $this->originalEnergy = $energy;
        $this->specialAttacks = ['SneakAttack', 'poisonDagger'];
    }

    public function getEnergy(): int
    {
        return $this->energy;
    }

    public function setEnergy(int $energy): void
    {
        $this->energy = $energy;
    }

        public function getSummary()
    {
        $parentSummary = parent::getSummary();
        $energyInfo = "<br>Additionally, this Rogue has {$this->energy} energy points.";
        return $parentSummary . "". $energyInfo; 
    }
    public function performSneakAttack(): ?string
    {
        if ($this->energy < 20) {
            throw new \Exception("Not enough energy to perform sneak attack.");
        }
        $modMessage = $this->modifyTemporaryStats(2, -0.4);

        $this->energy -= 20;
        
        return "Performed sneak attack with {$this->tempAttack} attack, Defense reduced by 40%";
    }
        public function usePoisonDagger()
    {
        if ($this->energy < 30) {
            throw new \Exception("Not enough energy to perform whirlwind attack.");
        }

        $modMessage = $this->modifyTemporaryStats(0.8, 0.7);
        $this->energy-= 30;

        return "Used Poison Dagger with {$this->tempAttack} power, Attack decreased by 20% and Defense decreased by 30%";
    }

    public function executeSpecialAttack(string $attackName): string
    {
        switch ($attackName) {
            case 'rageAttack':
                return $this->performSneakAttack();
            case 'whirlwind':
                return $this->usePoisonDagger();
            default:
                return "Unknown special attack: {$attackName}";    
        }
    }

    public function resetAttributes(): void
    {
        $this->energy = $this->originalEnergy;
        $this->resetTempStats();
    }
}