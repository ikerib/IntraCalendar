<?php

namespace App\Factory;

use App\Entity\Calendar;
use App\Repository\CalendarRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Calendar>
 *
 * @method static Calendar|Proxy createOne(array $attributes = [])
 * @method static Calendar[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Calendar|Proxy find(object|array|mixed $criteria)
 * @method static Calendar|Proxy findOrCreate(array $attributes)
 * @method static Calendar|Proxy first(string $sortedField = 'id')
 * @method static Calendar|Proxy last(string $sortedField = 'id')
 * @method static Calendar|Proxy random(array $attributes = [])
 * @method static Calendar|Proxy randomOrCreate(array $attributes = [])
 * @method static Calendar[]|Proxy[] all()
 * @method static Calendar[]|Proxy[] findBy(array $attributes)
 * @method static Calendar[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Calendar[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static CalendarRepository|RepositoryProxy repository()
 * @method Calendar|Proxy create(array|callable $attributes = [])
 */
final class CalendarFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'name' => self::faker()->realText(15),
            'color' => self::faker()->hexColor(),
            'bgColor' => self::faker()->hexColor(),
            'borderColor' => self::faker()->hexColor(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Calendar $calendar) {})
        ;
    }

    protected static function getClass(): string
    {
        return Calendar::class;
    }
}
