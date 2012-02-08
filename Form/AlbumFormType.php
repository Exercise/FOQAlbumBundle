<?php

namespace FOQ\AlbumBundle\Form;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;

class AlbumFormType extends AbstractType
{
	protected $dataClass;

	public function __construct($dataClass)
	{
		$this->dataClass = $dataClass;
	}

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('description', 'textarea')
        ;
    }

	public function getDefaultOptions(array $options)
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