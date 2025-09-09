<?php

require_once 'vendor/autoload.php';

use Game\Character;
use Game\CharacterList;

use Game\Battle;

use Game\Inventory;
use Game\Item;
use Game\ItemList;

use Smarty\Smarty;

use Game\DatabaseManager;
use Game\Mysql;

use Dotenv\Dotenv;

use Game\Warrior;
use Game\Mage;
use Game\Rogue;
use Game\Healer;
use Game\Tank;

session_start();

Game\Character::initializeSession();

$template = new Smarty();
$template->setTemplateDir(__DIR__ . '/templates');
$template->setCompileDir(__DIR__ . '/templates_c');

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $database = new Mysql(
        $_ENV['DB_HOST'],
        $_ENV['DB_NAME'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS']
    );
    DatabaseManager::setIntance($database);
} catch (PDOException $error) {
    $dberror = $error->getMessage();
}


$characterList = $_SESSION['characterList'] ?? new CharacterList();
$page = $_GET['page'] ?? '';

$testSword = new Item("Test Sword", "Weapon", 10);
$testArmor = new Item("Test Armor", "Armor", 20);
$testPotion = new Item("Test Potion", "Potion", 5);

switch ($page) {
    case 'createCharacter':
        $template->display('createCharacterForm.tpl');
        break;
    case 'saveCharacter':
        $name = $_POST['name'];
        $role = $_POST['role'];
        $health = (int) $_POST['health'];
        $attack = (int) $_POST['attack'];
        $defense = (int) $_POST['defense'];
        $range = (int) $_POST['range'];

        $characterList = $_SESSION['characterList'] ?? new CharacterList();
        switch ($_POST['role']) {
            case 'Warrior':
                $character = new Warrior($name, $role, $health, $attack, $defense, $range);
                $character->setRage((int) ($_POST['rage'] ?? 0));
                break;
            case 'Mage':
                $character = new Mage($name, $role, $health, $attack, $defense, $range);
                $character->setMana((int) ($_POST['mana'] ?? 150));
                break;
            case 'Rogue':
                $character = new Rogue($name, $role, $health, $attack, $defense, $range);
                $character->setEnergy((int) ($_POST['energy'] ?? 100));
                break;
            case 'Healer':
                $character = new Healer($name, $role, $health, $attack, $defense, $range);
                $character->setSpirit((int) ($_POST['spirit'] ?? 200));
                break;
            case 'Tank':
                $character = new Tank($name, $role, $health, $attack, $defense, $range);
                $character->setShield((int) ($_POST['shield'] ?? 150));
                break;
            default:
                $template->assign('error', 'Character role not recognized.');
                $template->display('error.tpl');
                return;
                
        }

        if ($_POST['role'] == 'Warrior' && isset($_POST['rage']) && $character instanceof Game\Warrior) {
            $character->setRage($_POST['rage']);
        }

        if ($_POST['role'] == 'Mage' && isset($_POST['mana']) && $character instanceof Game\Mage) {
            $character->setMana($_POST['mana']);
        }
        if ($_POST['role'] == 'Rogue' && isset($_POST['energy']) && $character instanceof Game\Rogue) {
            $character->setEnergy($_POST['energy']);
        }
        if ($_POST['role'] == 'Healer' && isset($_POST['spirit']) && $character instanceof Game\Healer) {
            $character->setSpirit($_POST['spirit']);
        }
        if ($_POST['role'] == 'Tank' && isset($_POST['shield']) && $character instanceof Game\Tank) {
            $character->setShield($_POST['shield']);
        }

        $characterList->addCharacter($character);
        $_SESSION['characterList'] = $characterList;

        header('Location: index.php?page=characterList');

        break;

    case 'listCharacters':
        $template->assign('characters', $characterList->getCharacters());
        $template->display('characterList.tpl');
        break;

    case 'viewCharacter':
        if (!empty($_GET['name'])) {
            $character = $characterList->getCharacter($_GET['name']);
            if ($character) {
                $template->assign('character', $character);
                $template->display('character.tpl');
            } else {
                $template->assign('message', 'Character not found.');
                $template->display('error.tpl');
            }
        } else {
            $template->assign('message', 'No character name provided.');
            $template->display('error.tpl');
        }
        break;
    case 'deleteCharacter':
        if (!empty($_GET['name'])) {
            $character = $characterList->getCharacter($_GET['name']);
            if ($character) {
                $characterList->removeCharacter($character);


                Game\Character::removeCharacterFromStats($character->getName(), $character->getRole());


                $template->assign('message', 'Character deleted successfully.');
            } else {
                $template->assign('message', 'Character not found.');
            }
        } else {
            $template->assign('message', 'No character name provided.');
        }
        $template->display('error.tpl');

        $_SESSION['characterList'] = $characterList;
        break;
    case 'characterStats':

    $totalCharacters = Game\Character::getTotalCharacter();

    $existingNames = Game\Character::getAllCharacterNames();

    $allTypes = ['Warrior', 'Mage', 'Rogue', 'Healer', 'Tank'];
    $typeCounts = [];
    foreach ($allTypes as $type) {
        $count = Game\Character::getCharacterTypeCount($type);
        if ($count > 0) {
            $typeCounts[$type] = $count;
        }
    }

    $template->assign('totalCharacters', $totalCharacters);
    $template->assign('characterTypeCounts', $typeCounts);
    $template->assign('characterNames', $existingNames);

    $template->display('characterStatistics.tpl');
    break;
    
    case 'battleForm':
    $template->assign('characters', $characterList->getCharacters());
    $template->display('battleForm.tpl');
    break;

    case 'startBattle':
        $characterList = $_SESSION['characterList'] ?? new CharacterList();

        $name1 = $_POST['character1'] ?? '';
        $name2 = $_POST['character2'] ?? '';

        if (empty($name1) || empty($name2)) {
            $template->assign('error', 'One or both characters not found.');
            $template->display('error.tpl');
            break;
        }

        $character1 = $characterList->getCharacter($name1);
        $character2 = $characterList->getCharacter($name2);

        if (!$character1 || !$character2) {
            $template->assign('error', 'One or both characters not found.');
            $template->display('error.tpl');
            break;
        }

        $maxRounds = isset ($_POST['maxRounds']) ? (int) $_POST['maxRounds'] : 10;
        $battle = new Battle($character1, $character2, $maxRounds);

        $_SESSION['battle'] = $battle;

        $template->assign('battle', $battle);

        $template->display('battleResult.tpl');
        break;
    
    case 'resetStats':
        Game\Character::resetAllStatistics();
        header('Location: index.php?page=characterStats');
        break;
    case 'recalculateStats':
        Game\Character::recalculateStatistics($characterList);
        header('Location: index.php?page=characterStats');
        break;

    case 'battleRound':
        if(!isset($_SESSION['battle']) || !$_SESSION['battle'] instanceof Battle)
        {
            $template->assign('error', 'No active battle found.');
            $template->display('error.tpl');
            break;
        }
        $battle = $_SESSION['battle'];

        $attack1 = $_POST['fighter1Attack'] ?? null;
        $attack2 = $_POST['fighter2Attack'] ?? null;

        $attack1 = ($attack1 === '' || $attack1 === 'normal') ? null : $attack1;
        $attack2 = ($attack2 === '' || $attack2 === 'normal') ? null : $attack2;

        $battle->setAttackForFighter($battle->getFighter2(), $attack1);
        $battle->setAttackForFighter($battle->getFighter1(), $attack2);

        $battle->executeTurn($battle->getFighter1(), $battle->getFighter2());

        $_SESSION['battle'] = $battle;

        $template->assign('battle', $battle);

        $template->display(template: 'battleResult.tpl');
        break;

    case 'resetHealth':
        if (!isset($_SESSION['battle']) || !$_SESSION['battle'] instanceof Battle) {
            $template->assign('error', 'No active battle found.');
            $template->display('error.tpl');
            break;
        }
        $battle = $_SESSION['battle'];
        $battle->endBattle();
        $_SESSION['battle'] = $battle;

        header('Location: index.php?page=battleForm');
        break;

    case 'testDatabase':
        if (DatabaseManager::getInstance()->testConnection()) {
            $template->assign('message', 'Database connection is working.');
        } else {
            $template->assign('message', 'Database connection failed.');
        }
        $template->display('testDatabase.tpl');
        break;
    case 'createItem':
        $template->display('createItemForm.tpl');
        break;
    case "saveItem":
        if (!empty($_POST['name']) && !empty($_POST['type']) && !empty($_POST['value'])) {
            $item = new Item($_POST['name'], $_POST['type'], (float)$_POST['value']);

            if ($item->save()) {
                $template->assign('item', $item);
                $template->display('itemCreated.tpl');
            } else {
                $template->assign('error', 'Failed to save item');
                $template->display('error.tpl');
            }
        } else {
            $template->assign('error', 'Failed to save item');
            $template->display('error.tpl');
        }
        break;
    case 'listItems':
        $itemList = new ItemList();
        $params = [];
        if (!empty($_POST['id'])) {
            $params['id'] = (int) $_POST['id'];
            $template->assign('selectedId', $_POST['id']);

        }
        if (!empty($_POST['type'])) {
            $params['type'] = $_POST['type'];
            $template->assign('selectedType', $_POST['type']);
        }

        if (isset($_POST['minValue']) && is_numeric($_POST['minValue'])) {
            $params['minValue'] = (float) $_POST['minValue'];
            $template->assign('selectedMinValue', $_POST['minValue']);
        }
        if (!empty($_POST['name'])) {
            $params['name'] = $_POST['name'];
            $template->assign('selectedName', $_POST['name']);
        }
        if (!empty($params)) {
            $itemList->loadByParams($params);
        } else {
            $itemList->loadAllFromDatabase();
        }
        $template->assign('items', $itemList->getItems());
        $template->assign('count', $itemList->count());
        $template->display('itemList.tpl');
        break;
    case 'updateItem':
        if (!empty($_GET['id'])) {
            $item = Item::loadFromDatabase((int) $_GET['id']);
            if ($item !== null) {
                $template->assign('item', $item);
                $template->display('updateItemForm.tpl');
            } else {
                $template->assign('error', 'Item not found.');
                $template->display('error.tpl');
            }

        } else {
            $template->assign('error', 'No item ID provided.');
            $template->display('error.tpl');
        }
        break;

    case 'saveUpdatedItem':
        if (!empty($_POST['name']) && !empty($_POST['type']) && !empty($_POST['value']) && !empty($_POST['id']))
            ;
        $item = new Item($_POST['name'], $_POST['type'], $_POST['value'], (int) $_POST['id']);
        if ($item->update()) {
            $template->assign('item', $item);
            $template->display('itemUpdated.tpl');
        } else {
            $template->assign('error', 'Failed to update item');
            $template->display('error.tpl');
        }
        break;
    case 'deleteItem':
        if (!empty($_GET['id'])) {
            $item = Item::loadFromDatabase((int) $_GET['id']);
            if ($item !== null) {
                $template->assign('item', $item);
                $template->display('deleteItemconfirm.tpl');
            } else {
                $template->assign('error', 'Item not found.');
                $template->display('error.tpl');
            }
        } else {
            $template->assign('error', 'No item ID provided.');
            $template->display('error.tpl');
        }
        break;
    case 'deleteItemConfirm':
        if (!empty($_POST['id'])) {
            $item = Item::loadFromDatabase((int) $_POST['id']);
            if ($item !== null) {
                if ($item->delete()) {
                    $template->assign('item', $item);
                    $template->assign('message', 'Item deleted successfully.');
                    $template->display('itemDeleted.tpl');
                } else {
                    $template->assign('error', 'Failed to delete item.');
                    $template->display('error.tpl');
                }
            } else {
                $template->assign('error', 'Item not found.');
                $template->display('error.tpl');
            }
        } else {
            $template->assign('error', 'No item ID provided.');
            $template->display('error.tpl');
        }

        break;

    default:
        $template->display('home.tpl');
        break;
}

Game\Character::saveSession();
$_SESSION['characterList'] = $characterList;