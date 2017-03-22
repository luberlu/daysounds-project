<?php

namespace ProjectBundle\Model;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AddSoundType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options){

        $builder->add("name")
            ->add("artiste")
            ->add("link")
            ->add('players', EntityType::class, array(
                'class' => 'ProjectBundle:Player',
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true
            ))
            ->add('genres', EntityType::class, array(
                'class' => 'ProjectBundle:Genre',
                'choice_label' => 'name',
                 'multiple' => true,
                 'expanded' => true
            ));
    }
}