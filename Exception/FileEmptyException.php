<?php
namespace Neutron\FormBundle\Exception;

class FileEmptyException extends \InvalidArgumentException implements ExceptionInterface
{
    public function __construct()
    {
        parent::__construct('File name is empty.');
    }
}