<?php

class Application_Form_ReuniaoVinculaUsuario extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	
    	$this->setName('nome');
        $id = new Zend_Form_Element_Hidden('id_reuniao');
        $id->addFilter('Int');

        $nome = new Zend_Form_Element_Text('nome');
        $nome->setLabel('Nome')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
            
        $date = new Zend_Form_Element_Text('date');
        $date->setLabel('Date')
           ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
        
            ->addValidator( new Zend_Validate_Date('dd/mm/aaaa'));
       //  ->addPrefixPath('Aplicacao_Validate', 'Aplicacao/Validate/', 'validate')
			//->addValidator(new Aplicacao_Validate_Data()); 
			
        $comentarios = new Zend_Form_Element_Textarea('comentarios');
        $comentarios->setLabel('Observação')
          ->setRequired(true)  

            ->setAttrib('cols', '55') 
    ->setAttrib('rows', '20');
        
        $fk_usuario= new Zend_Form_Element_MultiCheckbox('fk_usuario');
       	$usuarios = new Application_Model_DbTable_Usuario();
      	$fk_usuario->setLabel('Participantes')
            ->setRequired(true);
     
    	$listaUsuarios=$usuarios->getUsuarioCombo();
      	$fk_usuario->setMultiOptions( $listaUsuarios );
        Zend_Registry::get('logger')->log("Lista Usuarios", Zend_Log::INFO);
        Zend_Registry::get('logger')->log($listaUsuarios, Zend_Log::INFO);
        
        
        $usuarioHasReuniao = new Application_Model_DbTable_UsuarioHasReuniao();
        //Zend_Registry::get('logger')->log($usuarioHasReuniao->getParticipanteReuniaoCombo(1), Zend_Log::INFO);
		//$element->setValue(array('bar', 'bat'));
        
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        $this->addElements(array($id, $nome,$date, $comentarios,$fk_usuario,$submit));
     
    }


}

