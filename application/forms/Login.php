<?php

class Application_Form_Login extends Zend_Form
{


	public function init()
	{

		

		$login = new Zend_Form_Element_Text('login');
		$login->setLabel('USERNAME')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim')
			  ->addValidator('NotEmpty');

		$senha = new Zend_Form_Element_Password('senha');
		$senha->setLabel('PASSWORD')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim')
			  ->addValidator('NotEmpty');

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Login')
			   ->setAttrib('id', 'submitbutton');

		$this->addElements(array($login, $senha, $submit));
	}
}

