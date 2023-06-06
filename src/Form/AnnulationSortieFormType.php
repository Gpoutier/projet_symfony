<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AnnulationSortieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('infosSortie', TextType::class,[
                'label'=>'Motif'
            ])
            ->add('enregistrer',SubmitType::class,[
                'label'=>'Enregister'
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
