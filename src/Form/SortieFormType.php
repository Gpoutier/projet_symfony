<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\VilleRepository;
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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SortieFormType extends AbstractType
{
    private $router;
    private VilleRepository $villeRepository;

    public function __construct(UrlGeneratorInterface $router, VilleRepository $villeRepository)
    {
        $this ->villeRepository = $villeRepository;
        $this->router = $router;
    }


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

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }


    protected function addElements(FormInterface $form, Ville $ville = null){
        $form->add('ville', EntityType::class, [
            'class' => Ville::class,
            'choice_label' => "nom",
            'mapped' => false,
            'placeholder' => 'Sélectionner une Ville',
            'label' => 'ville',
        ]);

        $lieux = (null === $ville) ? [] : $ville->getLieux();

        $form->add('lieu', EntityType::class, [
            'class' => Lieu::class,
            'choice_label' => "nom",
            'choices' => $lieux,
            'placeholder' => 'Sélectionner un lieu',
            'label' => 'Lieu'
        ]);
    }

    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();

        $ville = $this->villeRepository->find($data['ville']);
        $this->addElements($form, $ville);
    }

    function onPreSetData(FormEvent $event) {
        $form = $event->getForm();

        $sortie = $event->getData();
        $lieu = $sortie->getLieu() ? $sortie->getLieu() : null;
        $ville = $lieu?->getVille();

        $this->addElements($form, $ville);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
