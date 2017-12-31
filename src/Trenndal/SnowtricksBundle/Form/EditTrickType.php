<?php
// src/Trenndal/Snowtrickbundle/Form/EditTrickType.php

namespace Trenndal\SnowtricksBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EditTrickType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	$groups=array('-- Trick group ? --' => null,'Straight airs' => 1,'Grabs' => 2,'Spins' => 3,'Flips and Inverted Rotations' => 4,'Inverted Hand Plants' => 5,'Slides' => 6,'Stalls' => 7,'Tweaks and variations' => 8);
	$builder->add('name')->add('typeGroup', ChoiceType::class, array('choices'  => $groups))->add('description')->add('save', SubmitType::class)->add('images', CollectionType::class, array('entry_type'=>ImageType::class, 'allow_add'=>true, 'allow_delete'=>true))->add('videos', CollectionType::class, array('entry_type'=>ImageType::class, 'allow_add'=>true, 'allow_delete'=>true));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Trenndal\SnowtricksBundle\Entity\EditTrick'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'trenndal_snowtricksbundle_edittrick';
    }


}
