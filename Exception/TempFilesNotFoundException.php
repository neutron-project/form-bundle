<?php
namespace Neutron\FormBundle\Exception;

class TempFilesNotFoundException extends \DomainException implements ExceptionInterface
{
    public function __construct()
    {
        parent::__construct('Temporary files are not found.');
    }
}