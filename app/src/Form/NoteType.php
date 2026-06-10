<?php

namespace App\Form;

use App\Entity\Note;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentNote = $options['data'] ?? null;

        $builder
            ->add('title', TextType::class, [
                'label'       => 'Titre',
                'constraints' => [new NotBlank(message: 'Le titre est obligatoire.')],
            ])
            ->add('content', TextareaType::class, [
                'label'       => 'Contenu',
                'constraints' => [new NotBlank(message: 'Le contenu est obligatoire.')],
            ])
            ->add('isPublic', CheckboxType::class, [
                'label'    => 'Note publique',
                'required' => false,
            ])
            ->add('notePassword', TextType::class, [
                'label'    => 'Mot de passe (2 lettres + 2 chiffres, ex: AA00)',
                'required' => false,
                'attr'     => [
                    'maxlength'   => 4,
                    'placeholder' => 'AA00',
                ],
            ])
            ->add('linkedNotes', EntityType::class, [
                'class'        => Note::class,
                'choice_label' => 'title',
                'multiple'     => true,
                'required'     => false,
                'label'        => 'Lier à d\'autres notes',
                'query_builder' => function ($repo) use ($currentNote) {
                    $qb = $repo->createQueryBuilder('n')
                        ->where('n.isPublic = true')
                        ->orderBy('n.title', 'ASC');
                    if ($currentNote && $currentNote->getId()) {
                        $qb->andWhere('n.id != :self')->setParameter('self', $currentNote->getId());
                    }
                    return $qb;
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Note::class]);
    }
}
