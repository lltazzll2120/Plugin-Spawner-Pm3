<?php

declare(strict_types=1);

namespace Tazz\Spawners;

use Tazz\Spawners\Commands\SpawnerCommand;
use Tazz\Spawners\Entities\EntityManager;
use Tazz\Spawners\Items\SpawnEgg;
use Tazz\Spawners\Items\SpawnerBlock;
use Tazz\Spawners\Tiles\MobSpawnerTile;
use Tazz\Spawners\Utilities\ConfigManager;
use Tazz\Spawners\Utilities\Utils;
use pocketmine\block\BlockFactory;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\plugin\PluginBase;
use pocketmine\tile\Tile;
use pocketmine\utils\TextFormat as C;
use ReflectionException;
use ReflectionProperty;

class Main extends PluginBase
{

    /** @var string */
    public const PREFIX = "";

    /**
     * @var Main
     */
    public static $instance;

    /**
     * @var array
     */
    public $exemptedEntities = [];

    public function onEnable(): void
    {
        self::$instance = $this;
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getServer()->getCommandMap()->register("BurgerSpawners", new SpawnerCommand($this));

        /** @noinspection PhpUnhandledExceptionInspection */
        Tile::registerTile(MobSpawnerTile::class, [Tile::MOB_SPAWNER, "minecraft:mob_spawner"]);
        BlockFactory::registerBlock(new SpawnerBlock(), true);
        ItemFactory::registerItem(new SpawnEgg(), true);
        Item::initCreativeItems();

        if (ConfigManager::getToggle("register-mobs")) {
            EntityManager::init();
        }

        if(is_array(ConfigManager::getArray("exempted-entities"))) {
            foreach (ConfigManager::getArray("exempted-entities") as $entityName) {
                $this->exemptEntityFromStackingByName((string)$entityName);
            }
        }
    }

    /**
     * @return array|null
     * @throws ReflectionException
     */
    public function getRegisteredEntities(): ?array
    {
        $reflectionProperty = new ReflectionProperty(Entity::class, 'knownEntities');
        $reflectionProperty->setAccessible(true);
        return $reflectionProperty->getValue();
    }

    public static function getInstance(): Main
    {
        return self::$instance;
    }

    public function getSpawner(string $name, int $amount): Item
    {
        $name = strtolower($name);
        $name = str_replace(" ", "", $name);
        $entityID = Utils::getEntityIDFromName($name);

        $nbt = new CompoundTag("", [
            new IntTag("EntityID", (int)$entityID)
        ]);

        $spawner = Item::get(Item::MOB_SPAWNER, 0, $amount, $nbt);
        $spawnerName = Utils::getEntityNameFromID((int)$entityID) . " Spawner";
        $spawner->setCustomName("§r§9" . $spawnerName);

        return $spawner;
    }

    /**
     * @param Entity $entity
     */
    public function exemptEntityFromStacking(Entity $entity): void
    {
        $this->exemptedEntities[] = $entity->getId();
    }

    /**
     * @param string $entityName
     */
    public function exemptEntityFromStackingByName(string $entityName): void
    {
        $this->exemptedEntities[] = $entityName;
    }

}
