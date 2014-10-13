<?php

class Application_Form_Produto extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	$this->setAttrib('enctype', 'multipart/form-data');
    	    	
    	$this->setName('FormularioProduto');
        $id = new Zend_Form_Element_Hidden('id_produto');
        $id->addFilter('Int');
        
        $nomeImagem = new Zend_Form_Element_Hidden('nomeImagem');
        
        
        $nome = new Zend_Form_Element_Text('nome');
        $nome->setLabel('Nome')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
            $nome->removeDecorator('DtDdWrapper')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label')
         ->setAttrib('class', 'form-control')
         ->setAttrib('placeholder', 'Enter nome');
         
            
        $palavraChave= new Zend_Form_Element_Text('palavrachave');
        $palavraChave->setLabel('Palavra-chave')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
        
		$descricao = new Zend_Form_Element_Textarea('descricao');
        $descricao->setLabel('Descricao')
            ->setRequired(true);
		$descricao->removeDecorator('DtDdWrapper')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label')
         ->setAttrib('class', 'form-control')
    	->setAttrib('rows', '5');
            
		
		$adicionarCaract = new Zend_Form_Element_Button('adicionarCaract');
		$adicionarCaract->setLabel("Adicionar caracteristica");
		$adicionarCaract->setAttrib('id', 'adicionarCaract');
		$adicionarCaract->removeDecorator('DtDdWrapper')
		->setAttrib('class', 'btn btn-primary')
		->removeDecorator('HtmlTag')
		->removeDecorator('Label');
		
		
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel("Adiconar");
        $submit->setAttrib('id', 'submitbutton');
         $submit->removeDecorator('DtDdWrapper')
         ->setAttrib('class', 'btn btn-primary')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label');
         
        $vincularReferencia = new Zend_Form_Element_Submit('vincularReferencia');
        $vincularReferencia->setLabel("Vincular outra refêrencia");
        $vincularReferencia->setAttrib('id', 'vincularReferencia');
         $vincularReferencia->removeDecorator('DtDdWrapper')
         ->setAttrib('class', 'btn btn-primary')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label');
        
        $precode = new Zend_Form_Element_Text('precode');
        $precode->setLabel('Preço anterior do produto')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
        $precopor= new Zend_Form_Element_Text('precopor');
        $precopor->setLabel('Preço atual do produto')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
        
        $fretemedio= new Zend_Form_Element_Text('fretemedio');
        $fretemedio->setLabel('Frete médio do produto')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('Regex',false, array('pattern' =>'/^\$?[0-9]+(,[0-9]{3})*(.[0-9]{2})?$/','messages'=>array(
                               'regexNotMatch'=>'Formato do campo ##.## com duas casa decimais'
                           )))
            
            ->addValidator('NotEmpty');
            
           
        $nomecaract= new Zend_Form_Element_Text('nomecaract');
      $nomecaract
           
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty')->removeDecorator('DtDdWrapper')
        ->removeDecorator('HtmlTag') ->setAttrib('class', 'form-control')
        ->removeDecorator('Label');
        
        $valorcaract= new Zend_Form_Element_Text('valorcaract');
      $valorcaract
           
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty')->removeDecorator('DtDdWrapper')
        ->removeDecorator('HtmlTag') ->setAttrib('class', 'form-control')
        ->removeDecorator('Label');
        
        $nomeCaracteristica= new Zend_Form_Element_Text('nomeCaracteristica');
        $nomeCaracteristica->setLabel('')->setName('nomeCaracteristica[]');
        
        
        $valorCaracteristica= new Zend_Form_Element_Text('valorCaracteristica');
        $valorCaracteristica->setLabel('')->setName('valorCaracteristica[]');
        
        $codigoean= new Zend_Form_Element_Text('codigoean');
        $codigoean->setLabel('Código EAN do Produto (Padrão EAN)')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
       	
        $saldo= new Zend_Form_Element_Text('saldo');
        $saldo->setLabel('Quantidade de produtos disponível em estoque')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('Digits')
            ->addValidator('NotEmpty');
        
            
        $disponivel= new Zend_Form_Element_Checkbox('disponivel');
        $disponivel->setLabel('Produto disponível?');
        $disponivel->removeDecorator('DtDdWrapper')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label');
        
        $ativo= new Zend_Form_Element_Checkbox('ativo');
        $ativo->setLabel('Produto ativo?');
        $ativo->removeDecorator('DtDdWrapper')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label');
        
          $fileUpload = new Zend_Form_Element_File('fileUpload');
		  $fileUpload->setLabel('Imagem produto')
		   ->setRequired(true)
		->addValidator('Extension', false, array('jpg', 'png', 'gif'))
	->addValidator('Size', false, 4024000);
    
        
        $this->addElements(array($id,$nomeImagem,$nome,$descricao,$palavraChave,$precode,$precopor,$fretemedio,$ativo,$disponivel,$codigoean,$saldo,$fileUpload,$nomeCaracteristica,$valorCaracteristica,$submit,$vincularReferencia,$adicionarCaract,$nomecaract,$valorcaract)); 
        
         $this->setDecorators( array( array('ViewScript', array('viewScript' => 'formularioProduto.phtml'))));
    }


}

