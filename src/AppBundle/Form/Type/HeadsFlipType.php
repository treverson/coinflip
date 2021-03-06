<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class HeadsFlipType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('flipType', 'hidden')
    	->add('heads', 'submit', array('attr' => array('class' => 'btn btn-primary')))
		;
	}

	public function getName()
	{
		return 'headsFlip';
	}
}