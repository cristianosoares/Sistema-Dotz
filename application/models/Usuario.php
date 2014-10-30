<?php
class Application_Model_Usuario implements Zend_Acl_Role_Interface
{
	private $_userName;
	private $_roleId;
	private $_fullName;
	private $_fkPerfil;
	private $id;
	private $_imagem;
	
	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = (string) $id;
	}
public function getImagem()
	{
		return $this->_imagem;
	}

	public function setImagem($_imagem)
	{
		$this->_imagem = (string) $_imagem;
	}
	public function getFKPerfil()
	{
		return $this->_fkPerfil;
	}

	public function setFKPerfil($fkPerfil)
	{
		$this->_fkPerfil = (string) $fkPerfil;
	}
	public function getUserName()
	{
		return $this->_userName;
	}

	public function setUserName($userName)
	{
		$this->_userName = (string) $userName;
	}

	public function getFullName()
	{
		return $this->_fullName;
	}

	public function setFullName($fullName)
	{
		$this->_fullName = (string) $fullName;
	}
	/**
	 *
	 */
	public function getRoleId()
	{
		return $this->_roleId;
	}

	public function setRoleId($roleId)
	{
		$this->_roleId = (string) $roleId;
	}
}