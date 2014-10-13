<?php

class Application_Form_CasaNoturna extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	
    	$this->setName('nome');
        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');
        $artist = new Zend_Form_Element_Text('nome');
        $artist->setLabel('Nome')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
            
      /*  $title = new Zend_Form_Element_Text('regiao');
        $title->setLabel('Região')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');*/
       $regiao = new Zend_Form_Element_Select( 'regiao' );
        $regiao->setLabel('Região')
            ->addMultiOptions(
                
array(
                    'ACRE' => 'ACRE',
                    'ALAGOAS' => 'ALAGOAS',
                    'AMAPÁ' => 'AMAPÁ',
                    'AMAZONAS' => 'AMAZONAS',
                    'BAHIA' => 'BAHIA',
                    'CEARÁ' => 'CEARÁ',
                    'DISTRITO FEDERAL' => 'DISTRITO FEDERAL',
                    'ESPÍRITO SANTO' => 'ESPÍRITO SANTO',
                    'RORAIMA' => 'RORAIMA',
                    'GOIÁS' => 'GOIÁS',
                    'MARANHÃO' => 'MARANHÃO',
                    'MATO GROSSO' => 'MATO GROSSO',
                    'MATO GROSSO DO SUL' => 'MATO GROSSO DO SUL',
                    'MINAS GERAIS' => 'MINAS GERAIS',
                    'PARÁ' => 'PARÁ',
                    'PARAÍBA' => 'PARAÍBA',
                    'PARANÁ' => 'PARANÁ',
                    'PERNAMBUCO' => 'PERNAMBUCO',
                    'PIAUÍ' => 'PIAUÍ',
                    'RIO DE JANEIRO' => 'RIO DE JANEIRO',
                    'RIO GRANDE DO NORTE' => 'RIO GRANDE DO NORTE',
                    'RIO GRANDE DO SUL' => 'RIO GRANDE DO SUL',
                    'RONDÔNIA' => 'RONDÔNIA',
                    'TOCANTINS' => 'TOCANTINS',
                    'SANTA CATARINA' => 'SANTA CATARINA',
                    'SÃO PAULO' => 'SÃO PAULO',
                    'SERGIPE' => 'SERGIPE'
                    
                )
            
            );
            
        $cidade = new Zend_Form_Element_Text('cidade');
        $cidade->setLabel('Cidade')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
            
          $interior_capital = new Zend_Form_Element_Text('interior_capital');
        $interior_capital->setLabel('Interior/Capital')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
            
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        
       
        $this->addElements(array($id, $artist, $regiao,$cidade,$submit));
    }


}

