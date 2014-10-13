<?php

class Application_Model_DbTable_Perfil extends Zend_Db_Table_Abstract
{

    protected $_name = 'perfil';
	protected $_primary = 'id_perfil';
	protected $_dependentTables = array("Application_Model_DbTable_Usuario");

	public function getPerfil ()
    {
       $perfil = new Application_Model_DbTable_Perfil();
       return $perfil->getAdapter()->fetchPairs( $perfil->select()->from( 'perfil', array('id_perfil', 'nome') )->order('nome'));
    }
}

