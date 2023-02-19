<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('GET')
            ->add('activity',ChoiceType::class,[
                'choices'=>['Aktiiviset'=>'active',
                            'Poistuneet'=>'deleted',
                            'Kaikki'=> 'both']
                ])
            ->add('alkaen', DateType::class, [
                'widget' => 'single_text','required'=>false])
            ->add('asti', DateType::class, [
                'widget' => 'single_text','required'=>false])
            ->add('searchStr', TextType::class,['required'=>false])
            ->add('minprice', TextType::class,['required'=>false])
            ->add('maxprice', TextType::class,['required'=>false])
            ->add('kl',ChoiceType::class,[
                'choices'=>['ANY'=> 'ANY',
                            'A'=>'A',
                            'B'=>'B',
                            'C'=>'C',
                            'D'=>'D']
                ])
            ->add('size',ChoiceType::class,[
                'choices'=>['ANY'=> 'ANY',
                            'P'=>'P',
                            'K'=>'K',
                            'I'=>'I',
                            'L'=>'L',
                            'V'=>'V']
                ])    
            ->add('orderBy',ChoiceType::class,[
                'choices'=>['Alennus %'=> 'alennus',
                            'Nimi'=> 'name',
                            'Pid'=> 'pid',
                            'OutID'=> 'outId',
                            'Pvm (last)'=> 'hakupvm',
                            'Pvm (first)'=>'firstSeen',
                            'Hinta (out)'=> 'outPrice',
                            'Hinta (nor)'=>'norPrice']
                ])
            ->add('direction',ChoiceType::class,[
                'choices'=>['DESC'=> 'DESC',
                            'ASC'=>'ASC']
                ])
            ->add('hae', SubmitType::class)
        ;
    }
}