<?php

class Application_Form_VisualizarDestinatario extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	
    	    	
    	$this->setName('FormularioDestinatario');
        $id = new Zend_Form_Element_Hidden('fk_pedido');
        $id->addFilter('Int');
        
       
        
        
        $documento = new Zend_Form_Element_Text('documento');
        $documento->setLabel('Documento(CPF/CNPJ)')
       
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
            $documento->removeDecorator('DtDdWrapper')
        ->removeDecorator('HtmlTag')
        
         ->setAttrib('class', 'form-control') ->setAttrib("disable", array(1));
         
         $tipopessoa= new Zend_Form_Element_Select('tipopessoa');
         $tipopessoa->setAttrib('class', 'form-control')->setAttrib("disable", array(1));
         $tipopessoa->setLabel('Tipo de Pessoa')->setRequired(true);
         $tipopessoa->setMultiOptions( array("F"=>"Física","J"=>"Júridica") );
            

         $nome = new Zend_Form_Element_Text('nome');
         $nome->setLabel('Nome')
         ->setRequired(true)->setAttrib("disable", array(1))
         ->addFilter('StripTags')->setAttrib("disable", array(1))
         ->addFilter('StringTrim')
         ->addValidator('NotEmpty');
         $nome->removeDecorator('DtDdWrapper')
         ->removeDecorator('HtmlTag')
         ->setAttrib('class', 'form-control');
         
         $email = new Zend_Form_Element_Text('email');
         $email->setLabel('E-mail')
         ->setRequired(true)->setAttrib("disable", array(1))
         ->addFilter('StripTags')
         ->addFilter('StringTrim')
         ->addValidator('NotEmpty');
         $email->removeDecorator('DtDdWrapper')
         ->removeDecorator('HtmlTag')
         ->setAttrib('class', 'form-control');
         
         
         $rua = new Zend_Form_Element_Text('rua');
         $rua->setLabel('Rua')
         ->setRequired(true)->setAttrib("disable", array(1))
         ->addFilter('StripTags')
         ->addFilter('StringTrim')
         ->addValidator('NotEmpty');
         $rua->removeDecorator('DtDdWrapper')
         ->removeDecorator('HtmlTag')
         ->setAttrib('class', 'form-control');
         
         $numero = new Zend_Form_Element_Text('numero');
         $numero->setLabel('Número')
         ->setRequired(true)->setAttrib("disable", array(1))
         ->addFilter('StripTags')
         ->addFilter('StringTrim')
         ->addValidator('NotEmpty');
         $numero->removeDecorator('DtDdWrapper')
         ->removeDecorator('HtmlTag')
         ->setAttrib('class', 'form-control');
         
         
         $compl = new Zend_Form_Element_Text('compl');
         $compl->setLabel('Complemento')
         ->setRequired(true)->setAttrib("disable", array(1))
         ->addFilter('StripTags')
         ->addFilter('StringTrim')
         ->addValidator('NotEmpty');
         $compl->removeDecorator('DtDdWrapper')
         ->removeDecorator('HtmlTag')
         ->setAttrib('class', 'form-control');
         
         $bairro = new Zend_Form_Element_Text('bairro');
         $bairro->setLabel('Bairro')
         ->setRequired(true)->setAttrib("disable", array(1))
         ->addFilter('StripTags')
         ->addFilter('StringTrim')
         ->addValidator('NotEmpty');
         $bairro->removeDecorator('DtDdWrapper')
         ->removeDecorator('HtmlTag')
         ->setAttrib('class', 'form-control');
         
         $cidade = new Zend_Form_Element_Text('cidade');
         $cidade->setLabel('Cidade')
         ->setRequired(true)->setAttrib("disable", array(1))
         ->addFilter('StripTags')
         ->addFilter('StringTrim')
         ->addValidator('NotEmpty');
         $cidade->removeDecorator('DtDdWrapper')
         ->removeDecorator('HtmlTag')
         ->setAttrib('class', 'form-control');
         
         $uf = new Zend_Form_Element_Text('uf');
         $uf->setLabel('UF')
         ->setRequired(true)->setAttrib("disable", array(1))
         ->addFilter('StripTags')
         ->addFilter('StringTrim')
         ->addValidator('NotEmpty');
         $uf->removeDecorator('DtDdWrapper')
         ->removeDecorator('HtmlTag')
         ->setAttrib('class', 'form-control');
         
         $cep = new Zend_Form_Element_Text('cep');
         $cep->setLabel('CEP')
         ->setRequired(true)->setAttrib("disable", array(1))
         ->addFilter('StripTags')
         ->addFilter('StringTrim')
         ->addValidator('NotEmpty');
         $cep->removeDecorator('DtDdWrapper')
         ->removeDecorator('HtmlTag')
         ->setAttrib('class', 'form-control');
         
         $ddd = new Zend_Form_Element_Text('ddd');
         $ddd->setLabel('DDD')
         ->setRequired(true)->setAttrib("disable", array(1))
         ->addFilter('StripTags')
         ->addFilter('StringTrim')
         ->addValidator('NotEmpty');
         $ddd->removeDecorator('DtDdWrapper')
         ->removeDecorator('HtmlTag')
         ->setAttrib('class', 'form-control');
         
         $telefone = new Zend_Form_Element_Text('telefone');
         $telefone->setLabel('Telefone')
         ->setRequired(true)->setAttrib("disable", array(1))
         ->addFilter('StripTags')
         ->addFilter('StringTrim')
         ->addValidator('NotEmpty');
         $telefone->removeDecorator('DtDdWrapper')
         ->removeDecorator('HtmlTag')
         ->setAttrib('class', 'form-control');
         
         $pontoreferencia = new Zend_Form_Element_Text('pontoreferencia');
         $pontoreferencia->setLabel('Ponto refêrencia')
         ->setRequired(true)->setAttrib("disable", array(1))
         ->addFilter('StripTags')
         ->addFilter('StringTrim')
         ->addValidator('NotEmpty');
         $pontoreferencia->removeDecorator('DtDdWrapper')
         ->removeDecorator('HtmlTag')
         ->setAttrib('class', 'form-control');
         
         $codigoident = new Zend_Form_Element_Text('codigoident');
         $codigoident->setLabel('Código de identificação do usuário')
         ->setRequired(true)->setAttrib("disable", array(1))
         ->addFilter('StripTags')
         ->addFilter('StringTrim')
         ->addValidator('NotEmpty');
         $codigoident->removeDecorator('DtDdWrapper')
         ->removeDecorator('HtmlTag')
         ->setAttrib('class', 'form-control');
         
         
    
        
        $this->addElements(array($id,$documento,$tipopessoa,$nome,$email,$rua,$numero,$compl,$bairro,$cidade,$uf,$cep,$ddd,$telefone,$pontoreferencia,$codigoident)); 
        
         $this->setDecorators( array( array('ViewScript', array('viewScript' => 'formularioVisualizarDestinatario.phtml'))));
    }


}

