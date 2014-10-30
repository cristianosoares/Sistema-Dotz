<?php
class Aplicacao_Acl_Setup extends Zend_Controller_Plugin_Abstract

{
	/**
	 * @var Zend_Acl
	 */
	protected $_acl;

	public function __construct()
	{
		$this->_acl = new Zend_Acl();
		$this->_initialize();
	}

	protected function _initialize()
	{
		$this->_setupRoles();
		$this->_setupResources();
		$this->_setupPrivileges();
		$this->_saveAcl();
	}

	protected function _setupRoles()
	{
		$this->_acl->addRole( new Zend_Acl_Role('guest') );
		$this->_acl->addRole( new Zend_Acl_Role('geral'), 'guest' );
		
		$this->_acl->addRole( new Zend_Acl_Role('stakeholders'), 'guest' );
		$this->_acl->addRole( new Zend_Acl_Role('reader'), 'guest' );
		
		$this->_acl->addRole( new Zend_Acl_Role('negocio'), 'guest' );
		$this->_acl->addRole( new Zend_Acl_Role('gerente'), 'guest' );
		$this->_acl->addRole( new Zend_Acl_Role('produtor'), 'guest' );
		$this->_acl->addRole( new Zend_Acl_Role('admin'), 'negocio' );
	}

	protected function _setupResources()
	{
		$this->_acl->addResource( new Zend_Acl_Resource('login') );
		$this->_acl->addResource( new Zend_Acl_Resource('upload') );
		$this->_acl->addResource( new Zend_Acl_Resource('error') );
		$this->_acl->addResource( new Zend_Acl_Resource('index') );
		$this->_acl->addResource( new Zend_Acl_Resource('usuarios') );
	}

	protected function _setupPrivileges()
	{
		
		$this->_acl->allow( 'guest', 'index', array('view-reuniao','view-cliente','view-empresa','view-usuario','logout', 'login','index','outros-eventos','lista-reunioes','lista-projeto-risk','visualizar-risk-tracker','visualizar-projeto-risk','lista-arquivos','lista-projeto','view-project','view-project-phase','lista-arquivos-projeto','lista-empresa','lista-cliente','lista-usuario','edit-alterar-perfil','ajuda') )
				   ->allow( 'guest', 'error', array('error', 'forbidden') );
	    $this->_acl->allow( 'geral', 'index', array('index','lista-fotos-evento','logout','ranking-executivo-negocio','ranking-gerente','observacao-evento') );
		$this->_acl->allow( 'negocio', 'index', array('index', 'ranking-executivo-negocio','lista-fotos-evento','logout') );
				 
	    $this->_acl->allow( 'gerente', 'index', array('index', 'ranking-gerente','lista-fotos-evento','logout') );
	     $this->_acl->allow( 'produtor', 'index', array('index','lista-fotos-evento','logout','observacao-evento') );
	     $this->_acl->allow( 'produtor', 'upload', array('media','uploadjqAction','uploadjq','lista-videos','videos') );
				  
		$this->_acl->allow( 'admin', 'index' );
			$this->_acl->allow( 'admin', 'upload' );
	}

	protected function _saveAcl()
	{
		$registry = Zend_Registry::getInstance();
		$registry->set('acl', $this->_acl);
	}
}