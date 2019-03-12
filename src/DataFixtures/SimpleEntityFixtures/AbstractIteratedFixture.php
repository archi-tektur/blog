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
    protected $iterationsCount = 20;

    protected $iterator;

    /**
     * Function loaded each time fixtures are loaded
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $this->iterator = 0;
        while ($this->iterator < $this->iterationsCount) {
            $this->eachIter($this->iterator);
            $this->iterator++;
        }
    }

    /**
     * Replace each %s in text to current iterator index
     *
     * @param $text
     * @return string
     */
    public function iterReplace($text): string
    {
        return sprintf($text, $this->iterator);
    }

    /**
     * Function ran each iteration
     *
     * @param int $iterator
     * @return mixed
     */
    abstract protected function eachIter(int $iterator): void;
}
