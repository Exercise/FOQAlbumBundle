<?php

namespace FOQ\AlbumBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class AlbumFormType extends AbstractType
{
	protected $dataClass;

	public function __construct($dataClass)
	{
		$this->dataClass = $dataClass;
	}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('description', 'textarea')
        ;
    }

	public function getDefaultOptions()
	{
		return array(
			'data_class' => $this->dataClass,
		);
	}


    public function getName()
    {
        return 'Album';
    }
}
