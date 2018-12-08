<?php

namespace App\Form;

use App\Form\DataTransformer\UserRoleArrayToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

/**
 * Class UserType.
 *
 * @package App\Form
 */
class UserType extends AbstractType
{
    /**
     * @var UserRoleArrayToStringTransformer
     */
    private $roleTransformer;

    /**
     * UserType constructor.
     *
     * @param UserRoleArrayToStringTransformer $roleTransformer
     */
    public function __construct(UserRoleArrayToStringTransformer $roleTransformer)
    {
        $this->roleTransformer = $roleTransformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'required' => true,
            ])
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN'
                ]
            ])
            ->addModelTransformer($this->roleTransformer)
        ;
    }
}
