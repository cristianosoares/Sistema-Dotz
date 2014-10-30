<?php

class Application_Form_Cliente extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	
    	$this->setName('nome');
        $id = new Zend_Form_Element_Hidden('id_cliente');
        $id->addFilter('Int');
        $nome = new Zend_Form_Element_Text('nome');
        $nome->setLabel('Nome')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
        
		$fk_empresa= new Zend_Form_Element_Select('fk_empresa');
       	$empresas = new Application_Model_DbTable_Empresa();
      	$fk_empresa->setLabel('Empresa')
            ->setRequired(true);
     
    	$listaEmpresas=$empresas->getEmpresasCombo();
      	$fk_empresa->setMultiOptions( $listaEmpresas );
      	Zend_Registry::get('logger')->log($listaEmpresas, Zend_Log::INFO);
     
         
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel("Adiconar");
        $submit->setAttrib('id', 'submitbutton');
        $this->addElements(array($id,$nome,$fk_empresa,$submit));
    }


}

