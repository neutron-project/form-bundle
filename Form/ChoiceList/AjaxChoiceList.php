<?php
/*
 * This file is part of NeutronFormBundle
 *
 * (c) Nikolay Georgiev <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\FormBundle\Form\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

/**
 * Implementation of choice list. Not-existing choices are accepted now.
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class AjaxChoiceList extends SimpleChoiceList
{

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList::getValuesForChoices()
     */
    public function getValuesForChoices(array $values)
    {
        return $values;
    }

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList::getChoicesForValues()
     */
    public function getChoicesForValues(array $values)
    {
        return $values;
    }

}