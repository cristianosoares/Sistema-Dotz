<?php

class Application_Form_Projeto extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	$this->setName('nome');
        $id = new Zend_Form_Element_Hidden('id_projeto');
        $id->addFilter('Int');

        $nome = new Zend_Form_Element_Text('nome');
        $nome->setLabel('Nome')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
            
       $fatura = new Zend_Form_Element_Text('fatura');
        $fatura->setLabel('PROJECT TOTAL INVOICE')
           
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
        $oibi = new Zend_Form_Element_Text('oibi');
        $oibi->setLabel('Project OIBI')
          
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');       
		
		$total_custo = new Zend_Form_Element_Text('total_custo');
        $total_custo->setLabel('PROJECT TOTAL COST')
         
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');	
     
		$fk_empresa= new Zend_Form_Element_Select('fk_empresa');
       	$empresas = new Application_Model_DbTable_Empresa();
      	$fk_empresa->setLabel('Empresa')
            ->setRequired(true);
     
    	$listaEmpresas=$empresas->getEmpresasCombo();
      	$fk_empresa->setMultiOptions( $listaEmpresas );
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
       
     
    
     $addArquivo = new Zend_Form_Element_Submit('addArquivo');
     //$addArquivo->setName('new value ddd');
        $addArquivo->removeDecorator('DtDdWrapper'); 
        $addArquivo->setAttrib('id', 'addArquivo');  
        $addArquivo->setValue('Add documents');
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
         $submit->removeDecorator('DtDdWrapper'); 
        //$this->addElements(array($cancel, $submit)); 

	 
		 $this->addElements(array($id, $nome,$fk_empresa,$fatura, $oibi,$total_custo,$submit,$addArquivo));
		
			$this->addDisplayGroup(array($addArquivo, 'submit'), 'buttons', array('disableLoadDefaultDecorators' => true)); 

		$group = $this->getDisplayGroup('buttons'); 

		$group->addDecorators(array( array('FormElements'), array('HtmlTag', array('tag' => 'div', 'class' => 'buttons')), ));
     
    }


}

