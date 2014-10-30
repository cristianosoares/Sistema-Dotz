
<?php

class Application_Form_Produto2 extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	
    	$this->setName('FormularioProduto');
        $id = new Zend_Form_Element_Hidden('id_produto');
        $id->addFilter('Int');
        $nome = new Zend_Form_Element_Text('nome');
        $nome->setLabel('Nome')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
            
        $palavraChave= new Zend_Form_Element_Text('palavrachave');
        $palavraChave->setLabel('Palavra-chave')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
        
		$descricao = new Zend_Form_Element_Textarea('descricao');
        $descricao->setLabel('Descricao')
            ->setRequired(true)

            ->setAttrib('cols', '55') ->setDecorators(array(array("label",array("class"=>"descricao")),"ViewHelper"))
    ->setAttrib('rows', '20')->setAttrib('class', 'ckeditor');
            
     
         
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel("Adiconar");
        $submit->setAttrib('id', 'submitbutton');
        
        $vincularReferencia = new Zend_Form_Element_Submit('vincularReferencia');
        $vincularReferencia->setLabel("Vincular Refêrencia");
        $vincularReferencia->setAttrib('id', 'vincularReferencia');
        
        $this->addElements(array($id,$nome,$descricao,$palavraChave,$submit,$vincularReferencia)); 
        
         $this->setDecorators( array( array('ViewScript', array('viewScript' => 'formularioProduto.phtml'))));
    }


}

