<?php

class Application_Form_BuscaProdutos extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */

        $fk_fornecedor= new Zend_Form_Element_Select('fk_fornecedor');
        $fk_fornecedor->setAttrib('class', 'form-control');
        
        $fornecedor = new Application_Model_DbTable_Fornecedor();
        $fk_fornecedor->setLabel('Fornecedor');
        
        $listaFornecedores=$fornecedor->getFornecedores();
        $fk_fornecedor->setMultiOptions( $listaFornecedores );
        $fk_fornecedor->addMultiOption("", "Todos");
        $fk_fornecedor->setValue('');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel("Adicionar");
        $submit->setAttrib('id', 'submitbutton');
         $submit->removeDecorator('DtDdWrapper')
         ->setAttrib('class', 'btn btn-primary')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label');
         
       
    
        
        $this->addElements(array($fk_fornecedor,$submit)); 
        
        $this->setDecorators( array( array('ViewScript', array('viewScript' => 'formularioBuscarProdutos.phtml'))));
    }


}

