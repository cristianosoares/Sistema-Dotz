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

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel("Adiconar");
        $submit->setAttrib('id', 'submitbutton');
         $submit->removeDecorator('DtDdWrapper')
         ->setAttrib('class', 'btn btn-primary')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label');
         
       
    
        
        $this->addElements(array($start_date,$end_date,$submit)); 
        
        $this->setDecorators( array( array('ViewScript', array('viewScript' => 'formularioBuscarPedidos.phtml'))));
    }


}

