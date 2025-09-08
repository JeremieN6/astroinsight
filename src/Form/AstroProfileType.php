<?php
namespace App\Form;

use App\Entity\AstroProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AstroProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('birthDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Date de naissance'
            ])
            ->add('birthTime', TimeType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Heure de naissance (locale)'
            ])
            ->add('birthPlace', TextType::class, [
                'required' => false,
                'label' => 'Lieu de naissance (texte libre)'
            ])
            ->add('latitude', NumberType::class, [
                'required' => false,
                'scale' => 6,
                'label' => 'Latitude'
            ])
            ->add('longitude', NumberType::class, [
                'required' => false,
                'scale' => 6,
                'label' => 'Longitude'
            ])
            ->add('timezone', TextType::class, [
                'required' => false,
                'label' => 'Fuseau horaire (ex: Europe/Paris)'
            ])
            ->add('accuracy', TextType::class, [
                'required' => false,
                'label' => 'Précision (exact / approx)'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AstroProfile::class,
        ]);
    }
}
