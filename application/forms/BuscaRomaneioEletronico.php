<?php

class Application_Form_BuscaRomaneioEletronico extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	
    	    	
    	$this->setName('FormularioOcorrenciaIten');
       
    	$mes= new Zend_Form_Element_Select('mes');
    	$mes->setAttrib('class', 'form-control');
    	$mes->setLabel('Mês')
    	->setRequired(true);
    	
    	$mes->setMultiOptions( array('1' => '01', '2' => '02', '3' => '03', '4' => '04', '5' => '05', '6' => '06',
    			'7' => '07', '8' => '08', '9' => '09', '10' => '10', '11' => '11', '12' => '12') );
      
        
        
        $ano= new Zend_Form_Element_Select('ano');
        $ano->setAttrib('class', 'form-control');
        $ano->setLabel('Ano')
        ->setRequired(true);
        
        $nfvenda = new Zend_Form_Element_Text('nfvenda');
  
        $nfvenda->setLabel('Nota fiscal de venda')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
            $nfvenda->removeDecorator('DtDdWrapper')
        ->removeDecorator('HtmlTag')
        
         ->setAttrib('class', 'form-control')
         ->setAttrib('placeholder', 'Entre com a nota fiscal de venda');
        
        $ano->setMultiOptions( array('2014' => '2014', '2015' => '2015', '2016' => '2016') );
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel("Adiconar");
        $submit->setAttrib('id', 'submitbutton');
         $submit->removeDecorator('DtDdWrapper')
         ->setAttrib('class', 'btn btn-primary')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label');
         
       
    
        
        $this->addElements(array($ano,$mes,$nfvenda,$submit)); 
        
        $this->setDecorators( array( array('ViewScript', array('viewScript' => 'formularioBuscarRomaneioEletronico.phtml'))));
    }


}

