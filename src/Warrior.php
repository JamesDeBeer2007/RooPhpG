<?php
namespace Game;

class Warrior extends Character
{
    private $rage;
    private $originalRage;

    public function __construct(string $name, string $role, int $health, int $attack, int $defense, int $range, int $rage = 0)
    {
        parent::__construct($name, $role, $health, $attack, $defense, $range);
        $this->rage = $rage;
        $this->originalRage = $rage;
        $this->specialAttacks = ['rageAttack', 'whirlwind'];
    }

    public function getRage()
    {
        return $this->rage;
    }

    public function setRage($rage)
    {
        $this->rage = $rage;
    }

    public function getSummary()
    {
        $parentSummary = parent::getSummary();
        $rageInfo = "<br>Additionally, this Warrior has {$this->rage} rage points.";
        return $parentSummary . "". $rageInfo; 
    }

    public function performRageAttack()
    {
        if ($this->rage < 25) {
            throw new \Exception("Not enough rage to perform rage attack.");
        }

        $modMessage = $this->modifyTemporaryStats(0.75, -0.3);

        $this->rage -= 25;

        return "Performed rage attack with {$this->tempAttack} power, Defense reduced by 30%";
    }

    public function performWhirlWind()
    {
        if ($this->rage < 35) {
            throw new \Exception("Not enough rage to perform whirlwind attack.");
        }

        $modMessage = $this->modifyTemporaryStats(0.6, 0.5);
        $this->rage -= 35;

        return "Performed whirlwind attack with {$this->tempAttack} power, Attack decreased by 40% and Defense decreased by 50%";
    }

    public function executeSpecialAttack(string $attackName): string
    {
        switch ($attackName) {
            case 'rageAttack':
                return $this->performRageAttack();
            case 'whirlwind':
                return $this->performWhirlWind();
            default:
                return "Unknown special attack: {$attackName}";    
        }
    }

    public function resetAttributes(): void
    {
        $this->rage = $this->originalRage;
        $this->resetTempStats();
    }
}