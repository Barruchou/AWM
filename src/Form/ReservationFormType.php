<?php

namespace App\Form;

use App\Entity\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ReservationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('session', EntityType::class, [
            'class' => Session::class,
            'choice_label' => function ($session) {
                return $session;
            }
        ]);
    }
}
