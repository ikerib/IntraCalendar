<?php

namespace App\Factory;

use App\Entity\Schedule;
use App\Repository\ScheduleRepository;
use Carbon\Carbon;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Schedule>
 *
 * @method static Schedule|Proxy createOne(array $attributes = [])
 * @method static Schedule[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Schedule|Proxy find(object|array|mixed $criteria)
 * @method static Schedule|Proxy findOrCreate(array $attributes)
 * @method static Schedule|Proxy first(string $sortedField = 'id')
 * @method static Schedule|Proxy last(string $sortedField = 'id')
 * @method static Schedule|Proxy random(array $attributes = [])
 * @method static Schedule|Proxy randomOrCreate(array $attributes = [])
 * @method static Schedule[]|Proxy[] all()
 * @method static Schedule[]|Proxy[] findBy(array $attributes)
 * @method static Schedule[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Schedule[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static ScheduleRepository|RepositoryProxy repository()
 * @method Schedule|Proxy create(array|callable $attributes = [])
 */
final class ScheduleFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        $starts_at = Carbon::createFromTimestamp(self::faker()->dateTimeBetween($startDate = '+2 days', $endDate = '+1 week')->getTimeStamp()) ;
        $ends_at= Carbon::createFromFormat('Y-m-d H:i:s', $starts_at)->addHours( self::faker()->numberBetween( 1, 8 ) );

        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'title' => self::faker()->realText(40),
            'category' => 'time',
            'start' => $starts_at,
            'end' => $ends_at,
            'isAllDay' => self::faker()->boolean(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Schedule $schedule) {})
        ;
    }

    protected static function getClass(): string
    {
        return Schedule::class;
    }
}
