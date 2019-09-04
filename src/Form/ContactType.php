<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\Department;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DomCrawler\Field\ChoiceFormField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public $departmentRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->departmentRepository = $em->getRepository(Department::class);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fname', TextType::class)
            ->add('lname', TextType::class)
            ->add('mail', EmailType::class)
            ->add('recipient', EntityType::class, [
                'class' => Department::class,
                'required' => true,
                'choice_label' => 'name',
                'placeholder' => 'Choisir un département',
                'query_builder' => function(DepartmentRepository $repo) {
                     return $repo->getListDepartment();
                },
            ])
            ->add('message', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            'translation_domain' => 'forms'
        ]);
    }

    private function getChoices()
    {
        $departments = $this->departmentRepository->findAll();
        $output = [];
        foreach($departments  as $department){
            $output[$department->getName()] = $department->getId();
        }
        return $output;
    }
}
