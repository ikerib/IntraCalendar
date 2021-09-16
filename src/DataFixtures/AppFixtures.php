<?php

namespace App\DataFixtures;

use App\Factory\CalendarFactory;
use App\Factory\ScheduleFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
       // CalendarFactory::createMany(8);
       // CalendarFactory::createMany(30);

        CalendarFactory::createMany(8, ['schedules' => ScheduleFactory::new()->many(1, 25)]);

        $manager->flush();
    }
}
