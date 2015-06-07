<?php
/**
 * Created by PhpStorm.
 * User: suman
 * Date: 4/5/2015
 * Time: 1:40 PM
 */
namespace BookManagerApp\ManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title');
        $builder->add('description')->add('pages');
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class'=>'BookManagerApp\ManagerBundle\Entity\Book'));
    }
    public function getName()
    {
        return 'bookManagerApp_managerbundle_book';
    }
}