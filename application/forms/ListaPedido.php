<?php

class Application_Form_ListaPedido extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	
    	    	
    	$this->setName('FormularioListaPedido');
        $id = new Zend_Form_Element_Hidden('id_pedido');
        $id->addFilter('Int');
        
        
		
		
        $nota_fiscal = new Zend_Form_Element_Submit('nota_fiscal');
        $nota_fiscal->setLabel("Conciliação de Nota Fiscal");
        $nota_fiscal->setAttrib('id', 'submitbutton');
         $nota_fiscal->removeDecorator('DtDdWrapper')
         ->setAttrib('class', 'btn btn-primary')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label');
         
        $cupon_ingresso = new Zend_Form_Element_Submit('cupon_ingresso');
        $cupon_ingresso->setLabel("Cupons e Ingressos");
        $cupon_ingresso->setAttrib('id', 'vincularReferencia');
         $cupon_ingresso->removeDecorator('DtDdWrapper')
         ->setAttrib('class', 'btn btn-primary')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label');
        
        
    
        
        $this->addElements(array($id,$nota_fiscal,$cupon_ingresso)); 
        
         $this->setDecorators( array( array('ViewScript', array('viewScript' => 'formularioListaPedido.phtml'))));
    }


}

