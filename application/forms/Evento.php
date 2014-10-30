<?php

class Application_Form_Evento extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	
    	$this->setName('nome');
        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');
        $nome = new Zend_Form_Element_Text('nome');
        $nome->setLabel('Nome')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
        
        
        
        $dt_evento = new Zend_Form_Element_Text('dt_evento');
        $dt_evento->setLabel('Data do evento')
           
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
        
            ->addValidator( new Zend_Validate_Date('dd/mm/aaaa'))
         ->addPrefixPath('Aplicacao_Validate', 'Aplicacao/Validate/', 'validate')
			->addValidator(new Aplicacao_Validate_Data());   
           
        
        $tipo_evento = new Zend_Form_Element_Select( 'tipo_evento ' );
        $tipo_evento->setLabel('Tipo Evento')
            ->addMultiOptions(
                array(
                    '0' => 'Evento home',
                    '1' => 'Outros eventos'
                )
            );
        
            
            
            $aprovado_observacao = new Zend_Form_Element_Select( 'aprovado_observacao' );
        $aprovado_observacao->setLabel('Aprovar observação')
            ->addMultiOptions(
                array(
                    '0' => 'Não',
                    '1' => 'Sim'
                )
            );
             
         $observacoes = new Zend_Form_Element_Textarea('observacoes');
        $observacoes->setLabel('Observação')
            

            ->setAttrib('cols', '55') ->setDecorators(array(array("label",array("class"=>"observacaoEvento2")),"ViewHelper"))
    ->setAttrib('rows', '20')->setAttrib('class', 'ckeditor');
       
        	
        $qt_garrafas = new Zend_Form_Element_Text('qt_garrafas');
        $qt_garrafas->setLabel('Quantidade de garrafas')
          //  ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');	
       
       $fk_usuario_negocio= new Zend_Form_Element_Select('fk_usuario_negocio');
       $usuarios = new Application_Model_DbTable_Usuario();
      $fk_usuario_negocio->setLabel('Executivo de negócios')
            ->setRequired(true);
     
     
      $listaUsuarios=$usuarios->getUsuariosNegocio();
      $fk_usuario_negocio->setMultiOptions( $listaUsuarios );
      
      
       $fk_usuario_gerente= new Zend_Form_Element_Select('fk_usuario_gerente');
       
       $listaUsuariosGerentes=$usuarios->getUsuariosGerente();
       $fk_usuario_gerente->setMultiOptions( $listaUsuariosGerentes);
       $fk_usuario_gerente->setLabel('Gerente da casa')
            ->setRequired(true);

       $produtor= new Zend_Form_Element_Select('fk_usuario_produtor');
        $listaUsuariosGerentes=$usuarios->getUsuariosProdutor();
       $produtor->setMultiOptions( $listaUsuariosGerentes);
       $produtor->setLabel('Produtor')
            ->setRequired(true);
       
       $fk_casa_noturna= new Zend_Form_Element_Select('fk_casa_noturna');
       $fk_casa_noturna->setLabel('Casa noturna')
            ->setRequired(true);
       $casaNoturnas = new Application_Model_DbTable_CasaNoturna();
       $fk_casa_noturna->setMultiOptions( $casaNoturnas->getCasasNoturnas() );
         
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel("Adiconar");
         $submit->setAttrib('class', 'botaoAdicionarComentario');
        $submit->setAttrib('id', 'submitbutton');
        $this->addElements(array($id,$nome,  $dt_evento,$qt_garrafas,$fk_usuario_negocio,$produtor,$fk_usuario_gerente,$fk_casa_noturna ,$tipo_evento,$observacoes,$submit));
    }


}

