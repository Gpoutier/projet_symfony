<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SortieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'label'=>'Nom de la sortie : ',

            ])
            ->add('dateHeureDebut',DateTimeType::class,[
                'label'=>'Date et Heure de la sortie :',
                'html5'=>true,
                'widget'=>'single_text',
            ])

            ->add('dateLimiteInscription', DateType::class,[
                'label'=>'Date limite d\'inscription : ',
                'html5'=>true,
                'widget'=>'single_text',
            ])
            ->add('nbInscriptionsMax', IntegerType::class,[
                'label'=>'Nombre de places : '
            ])
            ->add('duree', IntegerType::class,[
                'label'=>'Durée (en minutes): '
            ])
            ->add('infosSortie', TextareaType::class,[
                'label'=>'Description et infos : '
            ])
            ->add('ville', EntityType::class,[
                'mapped'=> false,
                'label'=>'Ville : ',
                'class'=> Ville::class,
                'choice_label'=>'nom'
            ])
            ->add('lieu',EntityType::class,[
                'label' => 'lieu : ',
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'required' => true,
                'placeholder' => 'Choisir un lieu',
                'empty_data' => null,
            ])
            ->add('enregistrer',SubmitType::class,[
                'label'=>'Enregistrer'
            ])
            ->add('publier',SubmitType::class,[
                'label'=>'Publier'
            ])

            ->add('annuler',ResetType::class,[
                'label'=>'Annuler',
                'attr' => [
                    'class'=> 'btn btn-secondary ml-auto',
                    'onclick' => sprintf("window.location.href = '%s';", $this->router->generate('app_accueil'))
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }
}
