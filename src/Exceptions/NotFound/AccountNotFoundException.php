<?php declare(strict_types=1);

namespace App\Exceptions\NotFound;

use Doctrine\ORM\EntityNotFoundException;

/**
 * AccountNotFoundException
 *
 * @package App\Exceptions\NotFound
 */
class AccountNotFoundException extends EntityNotFoundException
{
}
