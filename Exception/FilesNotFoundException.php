<?php
namespace Neutron\FormBundle\Exception;

class FilesNotFoundException extends \DomainException implements ExceptionInterface
{
    public function __construct()
    {
        parent::__construct('Files are not found.');
    }
}