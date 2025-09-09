<?php
namespace Game;
class Item
{
    private ?int $id;
    private string $name;
    private string $type;
    private float $value;
    public function __construct(string $name, string $type, float $value, ?int $id = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
        $this->id = $id;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getType(): string
    {
        return $this->type;
    }
    public function getValue(): float
    {
        return $this->value;
    }
    public function setId(?int $id): void
    {
        $this->id = $id;
    }
    public function toString(): string
    {
        return "Name: {$this->getName()}, Type: {$this->getType()}, Value: {$this->getValue()}";
    }
    public function toDatabaseArray(): array
    {
        return [
            "name" => $this->getName(),
            "type" => $this->getType(),
            "value" => $this->getValue()
        ];
    }
    public function save(): bool
    {
        try {
            $database = DatabaseManager::getInstance();
            if ($database === null) return false;
            $itemsData = $this->toDatabaseArray();
            $insertedId = $database->insert("items", $itemsData);
            $this->setId($insertedId);
            return true;
        } catch (\PDOException $error) {
            return false;
        }
    }
    public function update(): bool
    {
        try {
            if ($this->id === null) return false;
            $database = DatabaseManager::getInstance();
            if ($database === null) return false;
            $itemsData = $this->toDatabaseArray();
            $affectedRows = $database->update("items", $itemsData, ["id" => $this->id]);
            return $affectedRows > 0;
        } catch (\PDOException $error) {
            throw new \PDOException("Update Failed: " . $error->getMessage());
        }
    }
    static public function loadFromDatabase(int $id): ?Item
    {
        try {
            $database = DatabaseManager::getInstance();
            if ($database === null) return null;
            $result = $database->select(["items" => ["*"]], ["id" => $id]);
            if (empty($result)) return null;
            $row = $result[0];
            return new Item($row["name"], $row["type"], $row["value"], $row["id"]);
        } catch (\PDOException $error) {
            throw new \PDOException("Load Failed: ",  $error->getMessage());
        }
    }
    public function delete(): bool
    {
        try {
            $database = DatabaseManager::getInstance();
            if ($database === null) {
                return false;
            }
            $affectedRows = $database->delete("items", ["id" => $this->id]);
            return $affectedRows > 0;
        } catch (\PDOException $error) {
            throw new \PDOException("Delete Failed: " . $error->getMessage());
        }
    }
}