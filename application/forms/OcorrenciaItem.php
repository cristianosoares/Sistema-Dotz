<?php

class Application_Form_OcorrenciaItem extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	
    	    	
    	$this->setName('FormularioOcorrenciaIten');
        $id = new Zend_Form_Element_Hidden('id_item_has_ocorrencia');
        $id->addFilter('Int');

        $fk_item = new Zend_Form_Element_Hidden('fk_item');
        $fk_item->addFilter('Int');
        
        $final= new Zend_Form_Element_Select('final');
        $final->setAttrib('class', 'form-control');
        $final->setLabel('Ultima Ocorrência?')
        ->setRequired(true);
        
        $final->setMultiOptions( array("0"=>"NÃO","1"=>"SIM(Finaliza pedido)") );
        
        
        $fk_ocorrencia= new Zend_Form_Element_Select('fk_ocorrencia');
        $fk_ocorrencia->setAttrib('class', 'form-control');
        
        $ocorrencia = new Application_Model_DbTable_Ocorrencia();
        $fk_ocorrencia->setLabel('Ocorrências')
        ->setRequired(true);
         
        $listaOcorrencias=$ocorrencia->getOcorrenciaCombo();
        $fk_ocorrencia->setMultiOptions( $listaOcorrencias );
       // Zend_Registry::get('logger')->log($listaOcorrencias, Zend_Log::INFO);
        
		$observacao = new Zend_Form_Element_Textarea('observacao');
        $observacao->setLabel('Descricao');
        
		$observacao->removeDecorator('DtDdWrapper')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label')
         ->setAttrib('class', 'form-control')
    	->setAttrib('rows', '5');
        
                
        $dataentrega = new Zend_Form_Element_Text('datahora',
            array(
                 'label' => 'Data da Ocorrência',
                 'required' => false,
                 'filters' => array('StringTrim', 'StripTags'),
                 'class' => 'input-small datepicker',
                 'data-date' => date("d-m-Y"),
                 'data-date-format' => 'dd-mm-yyyy',
                 'value' => date("d-m-Y")
            )
        );

        // date validator
        $dataentrega->addValidator(new Zend_Validate_Date('d-m-Y'));
            
		
		
		
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel("Adiconar");
        $submit->setAttrib('id', 'submitbutton');
         $submit->removeDecorator('DtDdWrapper')
         ->setAttrib('class', 'btn btn-primary')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label');
         
       
    
        
        $this->addElements(array($id,$fk_item,$fk_ocorrencia,$observacao,$dataentrega,$final,$submit)); 
        
        $this->setDecorators( array( array('ViewScript', array('viewScript' => 'formularioOcorrenciaItem.phtml'))));
    }


}

