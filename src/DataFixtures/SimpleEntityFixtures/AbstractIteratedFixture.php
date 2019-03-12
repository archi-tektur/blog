<?php declare(strict_types=1);

namespace App\DataFixtures\SimpleEntityFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class AbstractIteratedFixture
 *
 * @package App\DataFixtures\SimpleEntityFixtures
 */
abstract class AbstractIteratedFixture extends Fixture
{
    protected const ITERATION_COUNT = 20;

    /**
     * Function loaded each time fixtures are loaded
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $i = 0;
        while ($i < self::ITERATION_COUNT) {
            $this->eachIter($i);
            $i++;
        }
    }

    /**
     * Function ran each iteration
     *
     * @param int $iterator
     * @return mixed
     */
    abstract protected function eachIter(int $iterator);
}
