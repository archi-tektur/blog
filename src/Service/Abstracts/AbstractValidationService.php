<?php declare(strict_types=1);

namespace App\Service\Abstracts;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class AbstractValidationService
 *
 * @package App\Controller\REST
 */
abstract class AbstractValidationService
{
    /**
     * @var EntityManager
     */
    protected $entityManager;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * AbstractValidationService constructor.
     *
     * @param ValidatorInterface     $validator
     * @param EntityManagerInterface $entity
     */
    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entity)
    {
        $this->validator = $validator;
        $this->entityManager = $entity;
    }

    /**
     * @param $obj
     * @throws ValidatorException
     * @return void
     */
    protected function validate($obj): void
    {
        $errors = $this->validator->validate($obj);
        if (count($errors) > 0) {
            throw new ValidatorException($errors->get(0)->getMessage());
        }
    }
}
