<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\Category;
use App\Entity\State;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array('label' => 'Title'))
            ->add('description', TextareaType::class, array('label' => 'Descritpion'))
            ->add('fkCategory', EntityType::class, array(
                'label' => 'Category',
                'class' => Category::class,
                'choice_label' => 'title'
            ))
            ->add('fkState', EntityType::class, array(
                'label' => 'State',
                'class' => State::class,
                'choice_label' => 'name'
            ))
            ->add('save', SubmitType::class, array('label' => 'Create'))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
