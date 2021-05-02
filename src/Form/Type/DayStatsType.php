<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

class DayStatsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('alku', DateType::class, [
                'widget' => 'single_text',])
            ->add('loppu', DateType::class, [
                'widget' => 'single_text',])
            ->add('hae', SubmitType::class)
        ;
    }
}
