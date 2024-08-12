<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Task;
use App\Enum\TaskStatus as EnumTaskStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content', TextareaType::class, [
                'required' => false,
            ])
            ->add('date', null, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('status', EnumType::class, [
                'class' => EnumTaskStatus::class,
                // 'choice_label' => ????,
            ])
            ->add('member', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'fullName',
                'required' => false,
            ])
            // ->add ('project', HiddenType::class, [
            // ])
            ->add ('project_id', HiddenType::class, [
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
