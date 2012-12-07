<?php
namespace Neutron\FormBundle\Exception;

class EmptyFileException extends \InvalidArgumentException implements ExceptionInterface
{
    public function __construct()
    {
        parent::__construct('File name or hash is empty.');
    }
}