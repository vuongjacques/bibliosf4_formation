<?php

namespace App\Form;

use App\Entity\Abonne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', null, [
                "constraints" => [
                    new Length([
                        "min" => 3,
                        "minMessage" => "Le pseudo doit comporter au moins 3 caractères"
                    ])
                ],
                "label" => "Pseudo*"
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => "Mot de passe*",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit comporter {{ limit }} caractères minimum',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add("nom", null, [
                "constraints" => [
                    new Length([
                        "max" => 30,
                        "maxMessage" => "Le nom ne doit pas comporter plus de {{ limit }} caractères",
                        "min" => 2,
                        "minMessage" => "Le nom doit comporter {{ limit }} caractères minimum"
                    ])
                ],
                "required" => false
            ])
            ->add("prenom", null, [
                "label" => "Prénom",
                "constraints" => [
                    new Length([
                        "max" => 30,
                        "maxMessage" => "Le prénom ne doit pas comporter plus de {{ limit }} caractères",
                        "min" => 1,
                        "minMessage" => "Le prénom doit comporter {{ limit }} caractères minimum"
                    ])
                ],
                "required" => false
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Veuillez accepter nos C.G.U.',
                    ]),
                ],
                "label" => "J'accepte les C.G.U."
            ])
            ->add("enregistrer", SubmitType::class, [
                "label" => "S'inscrire",
                "attr" => [
                    "class" => "btn-success"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Abonne::class,
        ]);
    }
}
