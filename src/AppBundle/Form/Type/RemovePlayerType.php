<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RemovePlayerType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('leave game', 'submit', array('attr' => array('class' => 'btn btn-warning')))
		;
	}

	public function getName()
	{
		return 'removePlayer';
	}
}