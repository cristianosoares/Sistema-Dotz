<?php

class Application_Form_BuscaPedidos extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	
    	    	
    	$this->setName('FormularioBuscaPedido');
        
        
        // Element: start_date
        $start_date = new Zend_Form_Element_Text('start_date',
            array(
                 'label' => 'Data de Inicio',
                 'required' => false,
                 'filters' => array('StringTrim', 'StripTags'),
                 'class' => 'input-small datepicker',
                 'data-date' => date("d-m-Y"),
                 'data-date-format' => 'dd-mm-yyyy',
                 'value' => date("d-m-Y")
            )
        );

        // date validator
        $start_date->addValidator(new Zend_Validate_Date('d-m-Y'));

        $this->addElement($start_date);
        unset($start_date);

        // Element: end_date
        $end_date = new Zend_Form_Element_Text('end_date',
            array(
                 'label' => 'Data Final',
                 'required' => false,
                 'filters' => array('StringTrim', 'StripTags'),
                 'class' => 'input-small datepicker',
                 'data-date' => date("d-m-Y"),
                 'data-date-format' => 'dd-mm-yyyy',
                 'value' => date("d-m-Y")
            )
        );
        $this->addElement($end_date);
        unset($end_date);
        
        $fk_fornecedor= new Zend_Form_Element_Select('fk_fornecedor');
        $fk_fornecedor->setAttrib('class', 'form-control');
        
        $fornecedor = new Application_Model_DbTable_Fornecedor();
        $fk_fornecedor->setLabel('Fornecedor');
        
        $listaFornecedores=$fornecedor->getFornecedores();
        $fk_fornecedor->setMultiOptions( $listaFornecedores );
        $fk_fornecedor->addMultiOption("", "Selecione");
        $fk_fornecedor->setValue('');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel("Adicionar");
        $submit->setAttrib('id', 'submitbutton');
         $submit->removeDecorator('DtDdWrapper')
         ->setAttrib('class', 'btn btn-primary')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label');
         
        $exportarPedidos = new Zend_Form_Element_Submit('exportarPedidos');
        $exportarPedidos->setLabel("Exportar Pedidos");
        $exportarPedidos->setAttrib('id', 'submitbutton');
        $exportarPedidos->removeDecorator('DtDdWrapper')
        ->setAttrib('class', 'btn btn-primary')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label'); 
       
    
        
        $this->addElements(array($start_date,$end_date,$fk_fornecedor,$submit,$exportarPedidos)); 
        
        $this->setDecorators( array( array('ViewScript', array('viewScript' => 'formularioBuscarPedidos.phtml'))));
    }


}

