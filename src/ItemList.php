<?php
namespace Game;

class ItemList
{
    private array $items = [];

    public function addItem(Item $item): void
    {
        $this->items[] = $item;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function loadAllFromDatabase(): ItemList
    {
        try {
            $database = DatabaseManager::getInstance();
            if ($database === null) {
                return $this;
            }
            
            $rows = $database->select(['items' => ['*']], []);
            foreach ($rows as $row) {
                $this->addItem($this->createItemFromDatabaseRow($row));
            }
            return $this;
        } catch (\PDOException $error) {
            throw new \PDOException('de select query nij item ging niet goed', $error->getMessage());
        }
    }

    private function createItemFromDatabaseRow(array $row): Item
    {
        return new Item
        (
            $row['name'],
            $row['type'],
            $row['value'],
            $row['id']
        );
    }

    public function loadByParams(array $params = []): ItemList
    {
        try {
            $database = DatabaseManager::getInstance();
            if ($database === null) {
                return $this;
            }
            $conditions = [];

            if (!empty($params['id'])) {
                $conditions['id'] = (int)$params['id'];
            }
            if (!empty($params['type'])) {
                $conditions['type'] = $params['type'];
            }
            if (isset($params['minValue']) && $params['minValue'] > 0) {
                $conditions['value >='] = (float)$params['minValue'];
            }
            if (!empty($params['name'])) {
                $conditions['name LIKE'] = $params['name'];
            }
            $rows = $database->select(['items' => ['*']], $conditions);
            foreach ($rows as $row) {
                $this->addItem($this->createItemFromDatabaseRow($row));
            }
            return $this;
        } catch (\PDOException $error) {
            throw new \PDOException($error->getMessage());
        }
    }

    public function findById(int $id): ?Item
    {
        foreach ($this->items as $item) {
            if ($item->getId() == $id) {
                return $item;
            }
        }
        try {
            $database = DatabaseManager::getInstance();
            if ($database === null)
            {
                return null;
            }
            $rows = $database->select(['items' => ['*']], ['id' => $id]);
            if (count($rows) > 0)
            {
                $item = $this->createItemFromDatabaseRow($rows[0]);
                $this->addItem($item);
                return $item;
            }

            return null;
        } catch (\PDOException $error) {
            throw new \PDOException($error->getMessage());
        }

    }
}
