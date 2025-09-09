<?php

namespace Game;

class Inventory {

    private array $items = [];

    public function addItem(string $item): void {
        $this->items[] = $item;
    }

    public function removeItem(string $item): void {
        $key = array_search($item, $this->items);
        if ($key !== false) {
            unset($this->items[$key]);
            // Herindexeer de array
            $this->items = array_values($this->items);
        }
    }

    public function getItems(): array {
        return $this->items;
    }
}
