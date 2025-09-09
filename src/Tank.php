<?php

namespace Game;

class Tank extends Character
{
    private int $shield;

    private int $originalShield;


    public function __construct(string $name, string $role, int $health, int $attack, int $defense, int $range, int $shield = 50)
    {
        parent::__construct($name, $role, $health, $attack, $defense, $range);
        $this->shield = $shield;
        $this->originalShield = $shield;
        $this->specialAttacks = ['barrierShield', 'taunt'];
    }

    public function getShield(): int
    {
        return $this->shield;
    }

    public function setShield(int $shield): void
    {
        $this->shield = $shield;
    }

    public function activateBarrierShield()
    {
        if ($this->shield < 15) {
            throw new \Exception("Not enough shield to activate barrier shield.");
        }

        $modmessage = $this->modifyTemporaryStats(0.5, 1);

        $this->shield -= 15;
        return "Barrier shield activated, Defense increased by 100% for one turn. Current shield: {$this->shield}";

    }
        public function performTaunt()
    {
        if ($this->shield < 10) {
            throw new \Exception("Not enough shield to perform taunt.");
        }

        $modMessage = $this->modifyTemporaryStats(0.6, 1.3);
        $this->shield -= 10;

        return "Performed a taunt {$this->tempAttack} power, Attack decreased by 60% and Defense increased by 30%";
    }

    public function executeSpecialAttack(string $attackName): string
    {
        switch ($attackName) {
            case 'barrierShield':
                return $this->activateBarrierShield();
            case 'taunt':
                return $this->performTaunt();
            default:
                return "Unknown special attack: {$attackName}";    
        }
    }

    public function resetAttributes(): void
    {
        $this->shield = $this->originalShield;
        $this->resetTempStats();
    }
}