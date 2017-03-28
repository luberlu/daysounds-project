<?php

namespace ProjectBundle\Model;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AddPlaylistType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options){

        $builder->add("name")
                ->add("position");

    }

}