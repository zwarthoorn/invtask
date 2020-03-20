<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'entityManager' => null,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $enityManager = $options['entityManager'];

       $countrys = $enityManager->getRepository(Country::class)->findAll();


        $builder
            ->add('firstname', TextType::class, ['label'=> 'Voornaam'])
            ->add('suffix', TextType::class, ['label'=> 'Tussenvoegsel','required'=>false])
            ->add('lastname',TextType::class, ['label' => 'Achternaam'])
            ->add('email', EmailType::class)
            ->add('country', ChoiceType::class, [
                'choices' => $countrys,
                // "name" is a property path, meaning Symfony will look for a public
                // property or a public method like "getName()" to define the input
                // string value that will be submitted by the form
                'choice_value' => 'name',
                // a callback to return the label for a given choice
                // if a placeholder is used, its empty value (null) may be passed but
                // its label is defined by its own "placeholder" option
                'choice_label' => function(?Country $country) {
                    return $country ? strtoupper($country->getName()) : '';
                },
                // returns the html attributes for each option input (may be radio/checkbox)
                'choice_attr' => function(?Country $country) {
                    return $country ? ['class' => 'country_'.strtolower($country->getName())] : [];
                },

            ])
            ->add('opslaan', SubmitType::class, [
                'attr' => ['class' => 'save'],
            ])
        ;
    }

}
