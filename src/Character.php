<?php

namespace Game;

abstract class Character 
{
    private string $name;
    private string $role;

    private int $health;
    protected int $attack;
    protected int $defense;
    private int $range;

    private array $inventory;

    protected int $tempAttack = 0;

    protected int $tempDefense = 0;

    protected array $specialAttacks = [];
    
    public static $totalCharacter = 0;
    
    public static $characterTypes = [];

    public static $existingNames = [];

    public function __construct($name, $role, $health, $attack, $defense, $range)
    {
        $this->name = $name;
        $this->role = $role;
        $this->health = $health;
        $this->attack = $attack;
        $this->defense = $defense;
        $this->range = $range;
        $this->inventory = [];
        
        self::$totalCharacter++;

        self::$characterTypes[] = $this->role;


        self::$existingNames[] = $this->name;
    }

    public function getInventory()
    {
        return $this->inventory;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getHealth()
    {
        return $this->health;
    }

    public function getAttack()
    {
        return $this->attack + $this->tempAttack;
    }

    public function getDefense()
    {
        return $this->defense + $this->tempDefense;
    }

    public function getRange()
    {
        return $this->range;
    }

    public function getSpecialAttacks()
    {
        return $this->specialAttacks;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function setHealth($health)
    {
        $this->health = $health;
    }

    public function setAttack($attack)
    {
        $this->attack = $attack;
    }

    public function setDefense($defense)
    {
        $this->defense = $defense;
    }

    public function setRange($range)
    {
        $this->range = $range;
    }

    public function resetTempStats()
    {
        $this->tempAttack = 0;
        $this->tempDefense = 0;
    }

    protected function modifyTemporaryStats(int $attackMod, int $defenseMod)
    {
        $this->tempAttack += $attackMod;
        $this->tempDefense += $defenseMod;

        return "Temporary stats modified: Attack +{$attackMod}, Defense +{$defenseMod}";
    }

    public function getSummary()
    {
        return "Name: {$this->name}, Role: {$this->role}, Health: {$this->health}, Attack: {$this->getAttack()}, Defense: {$this->getDefense()}, Range: {$this->range}";
    }

    public abstract function executeSpecialAttack(string $attackName): string;
    

    

    public abstract function resetAttributes(): void;  
    
    
    private static function loadFromSession(): void
    {
        if (isset($_SESSION['characterList']) && is_array($_SESSION['characterStats'])) {

            $stats = $_SESSION['characterStats'];
            self::$totalCharacter = $stats['totalCharacter'] ?? 0;
            self::$characterTypes = $stats['characterTypeCounts'] ?? [];
            self::$existingNames = $stats['characterNames'] ?? [];
        }
        
    }

    private static function saveToSession(): void
    {
        $_SESSION['characterStats'] = [
            'totalCharacter' => self::$totalCharacter,
            'characterTypeCounts' => self::$characterTypes,
            'characterNames' => self::$existingNames
        ];
    }

    public static function getTotalCharacter(): int
    {
        self::loadFromSession();
        return self::$totalCharacter;
    }

    public static function getAllCharacterTypes(): array
    {
        self::loadFromSession();
        return self::$characterTypes;
    }

    public static function getAllCharacterNames(): array
    {
        self::loadFromSession();
        return self::$existingNames;
    }

    public static function getCharacterTypeCount(string $type): int
    {
        self::loadFromSession();
        return count(array_filter(self::$characterTypes, fn($t) => $t === $type));
    }

    public static function resetAllStatistics (): void
    {
        self::$totalCharacter = 0;
        self::$characterTypes = [];
        self::$existingNames = [];
        self::saveToSession();
    }

    public static function recalculateStatistics(CharacterList $characterList ): void
    {
        self::resetAllStatistics();

        foreach ($characterList->getCharacters() as $character) {
            self::$totalCharacter++;
            self::$characterTypes[] = $character->getRole();
            self::$existingNames[] = $character->getName();
        }

        self::saveToSession();

    }   

    public static function removeCharacterFromStats(string $name, string $role): void
    {

        $nameKey = array_search($name, self::$existingNames);
        $roleKey = array_search($role, self::$characterTypes);
        if($nameKey !== false && $roleKey !== false) {
            unset(self::$existingNames[$nameKey]);
            unset(self::$characterTypes[$roleKey]);
            self::$existingNames = array_values(self::$existingNames);
            self::$characterTypes = array_values(self::$characterTypes);
            self::$totalCharacter--;
            self::saveToSession();
        } 
    }

    public static function initializeSession(): void
    {
        self::loadFromSession();
    }

    public static function saveSession(): void
    {
        self::saveToSession();
    }  
}