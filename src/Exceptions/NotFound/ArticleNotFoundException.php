<?php

namespace App\Exceptions\NotFound;

use Doctrine\ORM\EntityNotFoundException;

class ArticleNotFoundException extends EntityNotFoundException
{
}
