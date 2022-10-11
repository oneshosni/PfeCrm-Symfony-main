<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\DemandeInfo;
use App\Entity\Produit;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Description')
            ->add('Type')
            ->add('produit',EntityType::class,[
                "multiple"=>false,
                "class"=>Produit::class,
                "query_builder"=>function(ProduitRepository $categorieRepository){
                    return $categorieRepository->createQueryBuilder('c')
                        ->orderBy('c.Name', 'ASC');
                },
                "required"=>true,
                "attr"=>["data-live-search"=>"true","data-size"=>"3"]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DemandeInfo::class,
        ]);
    }
}
