<?php

class Application_Form_DetalheItem extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	
    	    	
    	$this->setName('FormularioRastreamento');
        //$id = new Zend_Form_Element_Hidden('id_produto');
       // $id->addFilter('Int');

        $id_pedido_dotz = new Zend_Form_Element_Text('id_pedido_dotz');
        $id_pedido_dotz->setLabel('Código pedido dotz')
       
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty')
        ->removeDecorator('DtDdWrapper')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label')
        ->setAttrib('class', 'form-control')
        ->setAttrib('placeholder', '');
        
        $id_pedido = new Zend_Form_Element_Text('id_pedido');
        $id_pedido->setLabel('Código pedido TM1')
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty')
        ->removeDecorator('DtDdWrapper')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label')
        ->setAttrib('class', 'form-control')
        ->setAttrib('placeholder', '');
        
        
        $produtoiddotz= new Zend_Form_Element_Text('produtoiddotz');
        $produtoiddotz->setLabel('Código item dotz')
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty')
        ->removeDecorator('DtDdWrapper')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label')
        ->setAttrib('class', 'form-control')
        ->setAttrib('placeholder', '');
        
        $id_item= new Zend_Form_Element_Text('id_item');
        $id_item->setLabel('Código item TM1')
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty')
        ->removeDecorator('DtDdWrapper')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label')
        ->setAttrib('class', 'form-control')
        ->setAttrib('placeholder', '');
        
        $preco= new Zend_Form_Element_Text('preco');
        $preco->setLabel('Valor Unitário do Produto cobrado pelo fornecedor (R$)')
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty')
        ->removeDecorator('DtDdWrapper')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label')
        ->setAttrib('class', 'form-control')
        ->setAttrib('placeholder', '');
        
        $frete= new Zend_Form_Element_Text('frete');
        $frete->setLabel('Valor Unitário do Frete cobrado pelo fornecedor (R$)')
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty')
        ->removeDecorator('DtDdWrapper')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label')
        ->setAttrib('class', 'form-control')
        ->setAttrib('placeholder', '');
        
        $peso= new Zend_Form_Element_Text('peso');
        $peso->setLabel('Peso da Remessa (Gramas)')
        ->addValidator('Digits')
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty')
        ->removeDecorator('DtDdWrapper')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label')
        ->setAttrib('class', 'form-control')
        ->setAttrib('placeholder', '');
        
        $nr_rastreio= new Zend_Form_Element_Text('nr_rastreio');
        $nr_rastreio->setLabel('Número de Rastreio da Remessa = código de rastreamento dos Correios, quando for o caso.')
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty')
        ->removeDecorator('DtDdWrapper')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label')
        ->setAttrib('class', 'form-control')
        ->setAttrib('placeholder', '');
        
        $u_chave= new Zend_Form_Element_Text('u_chave');
        $u_chave->setLabel('Chave de acesso NF')
        ->setRequired(true)
        
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty')
        ->removeDecorator('DtDdWrapper')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label')
        ->setAttrib('class', 'form-control')
        ->setAttrib('placeholder', '');
        
        $numero_nf= new Zend_Form_Element_Text('numero_nf');
        $numero_nf->setLabel('Número da NF de cobrança ')
        ->setRequired(true)
        
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty')
        ->removeDecorator('DtDdWrapper')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label')
        ->setAttrib('class', 'form-control')
        ->setAttrib('placeholder', '');
        
        $numero_linha_nf= new Zend_Form_Element_Text('numero_linha_nf');
        $numero_linha_nf->setLabel('Número da linha da NF de cobrança')
        ->setRequired(true)
         ->addValidator('Digits')
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty')
        ->removeDecorator('DtDdWrapper')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label')
        ->setAttrib('class', 'form-control')
        ->setAttrib('placeholder', '');
        
      
		
		
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel("Salvar");
        $submit->setAttrib('id', 'submitbutton');
         $submit->removeDecorator('DtDdWrapper')
         ->setAttrib('class', 'btn btn-primary')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label');
         
        $voltar = new Zend_Form_Element_Submit('voltar');
        $voltar->setLabel("Voltar");
        $voltar->setAttrib('id', 'voltar');
         $voltar->removeDecorator('DtDdWrapper')
         ->setAttrib('class', 'btn btn-primary')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label');
        
    
        
        $this->addElements(array($id_pedido_dotz,$id_pedido,$produtoiddotz,$id_item,$preco,$frete,$peso,$nr_rastreio,$u_chave,$numero_nf,$numero_linha_nf,$submit,$voltar)); 
        
         $this->setDecorators( array( array('ViewScript', array('viewScript' => 'formularioRastreamento.phtml'))));
    }


}

