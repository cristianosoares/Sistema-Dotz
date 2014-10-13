<?php

class Application_Form_AprovarEvento extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	
    	
        
       $qt_garrafas = new Zend_Form_Element_Text('qt_garrafas');
        $qt_garrafas->setLabel('Quantidade de garrafas')
          //  ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');	
           
        
       $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');

        $aprovado_observacao = new Zend_Form_Element_Select( 'aprovado_observacao' );
        $aprovado_observacao->setLabel('Aprovar observação')
            ->addMultiOptions(
                array(
                    '0' => 'Não',
                    '1' => 'Sim'
                )
            );
         $pergunta1 = new Zend_Form_Element_Radio('pergunta1');
        $pergunta1->setLabel('1-Qual o grau de predominância de ambiente azul?')
            ->setSeparator('')
           
            -> addDecorators(array( 
                array('HtmlTag', array('tag' => 'div','class' => 'envolveRadio')), 
            ))
            //'attribs'    => array('class' => 'maClasse')
            ->addMultiOptions(
                array(
                    '0' => '0',
                    '1' => '1',
                 '2' => '2',
                  '3' => '3',
                   '4' => '4',
                    '5' => '5'
                )
            );
        $pergunta2 = new Zend_Form_Element_Radio('pergunta2');
        $pergunta2->setLabel('2-Qual a lotação da casa ( Porcentagem em relação a lotação total)')
            ->addMultiOptions(
                array(
                    '20' => '20',
                    '40' => '40',
                 '60' => '60',
                  '80' => '80',
                   '100' => '100'
                )
            )->setSeparator('')
           
            -> addDecorators(array( 
                array('HtmlTag', array('tag' => 'div','class' => 'envolveRadio')), 
            ));
        $pergunta3 = new Zend_Form_Element_Radio('pergunta3');
        $pergunta3->setLabel('3-Qual o grau de satisfação da brigada e do gerente quanto ao plano de incentivo?')
            ->addMultiOptions(
                array(
                    '0' => '0',
                    '1' => '1',
                 '2' => '2',
                  '3' => '3',
                   '4' => '4',
                    '5' => '5'
                )
            )->setSeparator('')
           
            -> addDecorators(array( 
                array('HtmlTag', array('tag' => 'div','class' => 'envolveRadio')), 
            ))
            ;
        $pergunta4 = new Zend_Form_Element_Radio('pergunta4');
        $pergunta4->setLabel('4-Qual o grau de engajamento e comprometimento da equipe de brigada?')
            ->addMultiOptions(
                array(
                    '0' => '0',
                    '1' => '1',
                 '2' => '2',
                  '3' => '3',
                   '4' => '4',
                    '5' => '5'
                )
            )->setSeparator('')
           
            -> addDecorators(array( 
                array('HtmlTag', array('tag' => 'div','class' => 'envolveRadio')), 
            ))
            ;
        $pergunta5 = new Zend_Form_Element_Radio('pergunta5');
        $pergunta5->setLabel('5-Qual o grau de engajamento e comprometimento do gerente da casa?')
            ->addMultiOptions(
                array(
                    '0' => '0',
                    '1' => '1',
                 '2' => '2',
                  '3' => '3',
                   '4' => '4',
                    '5' => '5'
                )
            )->setSeparator('')
           
            -> addDecorators(array( 
                array('HtmlTag', array('tag' => 'div','class' => 'envolveRadio')), 
            ))
            ;
        $pergunta6 = new Zend_Form_Element_Radio('pergunta6');
        $pergunta6->setLabel('6-Qual o grau de engajamento e comprometimento das promotoras?')
            ->addMultiOptions(
                array(
                    '0' => '0',
                    '1' => '1',
                 '2' => '2',
                  '3' => '3',
                   '4' => '4',
                    '5' => '5'
                )
            )->setSeparator('')
           
            -> addDecorators(array( 
                array('HtmlTag', array('tag' => 'div','class' => 'envolveRadio')), 
            ))
            ;
        $pergunta7 = new Zend_Form_Element_Radio('pergunta7');
        $pergunta7->setLabel('7-Qual o grau de aceitação do ritual (Strobo Punch)?')
            ->addMultiOptions(
                array(
                    '0' => '0',
                    '1' => '1',
                 '2' => '2',
                  '3' => '3',
                   '4' => '4',
                    '5' => '5'
                )
            )->setSeparator('')
           
            -> addDecorators(array( 
                array('HtmlTag', array('tag' => 'div','class' => 'envolveRadio')), 
            ))
            ;
        $pergunta8 = new Zend_Form_Element_Radio('pergunta8');
        $pergunta8->setLabel('8-Qual o grau de aceitação dos glowsticks?')
            ->addMultiOptions(
                array(
                    '0' => '0',
                    '1' => '1',
                 '2' => '2',
                  '3' => '3',
                   '4' => '4',
                    '5' => '5'
                )
            )->setSeparator('')
           
            -> addDecorators(array( 
                array('HtmlTag', array('tag' => 'div','class' => 'envolveRadio')), 
            ))
            ;
            
        $pergunta9 = new Zend_Form_Element_Radio('pergunta9');
        $pergunta9->setLabel('9-Qual o grau de aceitação dos frames?')
            ->addMultiOptions(
                array(
                    '0' => '0',
                    '1' => '1',
                 '2' => '2',
                  '3' => '3',
                   '4' => '4',
                    '5' => '5'
                )
            )->setSeparator('')
           
            -> addDecorators(array( 
                array('HtmlTag', array('tag' => 'div','class' => 'envolveRadio')), 
            ))
            ;  
        $observacoes = new Zend_Form_Element_Textarea('observacoes');
        $observacoes->setLabel('Observação')
           

            ->setAttrib('cols', '55') ->setDecorators(array(array("label",array("class"=>"observacaoEvento")),"ViewHelper"))
    ->setAttrib('rows', '20')->setAttrib('class', 'ckeditor');
            
       
         
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('class', 'botaoAdicionarComentario2');
        $submit->setAttrib('id', 'submitbutton');
        
        $this->addElements(array($id,$qt_garrafas,$observacoes,$aprovado_observacao,$pergunta1,$pergunta2,$pergunta3,$pergunta4,$pergunta5,$pergunta6,$pergunta7,$pergunta8,$pergunta9,$submit));

    foreach($this->getElements() as $element) {
            $element->removeDecorator('DtDdWrapper');           
            $element->clearDecorators();
            $element->removeDecorator('Label');
            $element->setDecorators(array(
                'ViewHelper', 
                'label', 
                'Errors', 
                    array(
                        'HtmlTag', 
                        array(
                            'tag' => '<div>',
                            'class' => 'envolveRadio'
                        )
                    ) 
                )
            );
              
       }      

    // defina o arquivo que vai carregar o HTML decorador  
        $this->setDecorators( array( array('ViewScript', array('viewScript' => 'formularioAprovarEvento.phtml'))));
        
    
    }


}

