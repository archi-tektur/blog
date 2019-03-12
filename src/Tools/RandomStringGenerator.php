<?php declare(strict_types=1);

namespace App\Tools;

use Exception;
use function implode;
use function mb_strlen;
use function random_int;

/**
 * Methods to generate random string.
 *
 * @package App\Tools
 */
class RandomStringGenerator
{
    private const DEFAULT_KEYSPACE = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Generate a random string, using a cryptographically secure
     * pseudorandom number generator (random_int)
     *
     * For PHP 7, random_int is a PHP core function
     * For PHP 5.x, depends on https://github.com/paragonie/random_compat
     *
     * @param int    $length   How many characters do we want?
     * @param string $keyspace A string of all possible characters
     *                         to select from
     * @return string
     * @throws Exception
     */
    public static function generate($length, $keyspace = self::DEFAULT_KEYSPACE): string
    {
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($iterator = 0; $iterator < $length; $iterator++) {
            $pieces [] = $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
}
