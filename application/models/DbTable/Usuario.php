<?php

class Application_Model_DbTable_Usuario extends Zend_Db_Table_Abstract
{

    protected $_name = 'usuario';
	protected $_primary = 'id_usuario';
	
	protected $_referenceMap = array(
 		"perfil" => array(
			"columns" => array("fk_perfil"),
			"refTableClass" => "Application_Model_DbTable_Perfil",
			"refColumns" => array("id_perfil"),
		)

	);
	public function getUsuarioCombo ()
    {
       $listaUsuario = new Application_Model_DbTable_Usuario();
       return $listaUsuario->getAdapter()->fetchPairs( $listaUsuario->select()->from( 'usuario', array('id_usuario', 'nome') )->order('nome'));
    }
	public function getUsuario($id)
    {
        $id = (int) $id;
        $row = $this->fetchRow('id_usuario = ' . $id);
        if (! $row) {
            throw new Exception("Não foi possivel encontrar a linha $id");
        }
        return $row->toArray();
    }
    public function getUsuariosComPerfil()
    {
    	
    	 $select =$this->_db->select()
             ->from(array('u' => 'usuario'))
             ->joinInner(array('p' => 'perfil'),('u.fk_perfil =p.id_perfil'),array('nome as nomePerfil'))
             ->where('u.id_usuario <>1');
  	   $result = $this->getAdapter()->fetchAll($select);
       return $result;
    }
    public function getUsuarioImagem($id)
    {
    	
    	 $select =$this->_db->select()
             ->from(array('u' => 'usuario'))
             ->joinLeft(array('a' => 'arquivo'),('u.fk_arquivo =a.id_arquivo'),array('nome as nomeArquivo'))->order('a.id_arquivo desc')
             ->where('u.id_usuario ='. $id);
  	   $result = $this->getAdapter()->fetchRow($select);
  	  
       return $result["nomeArquivo"];
    }
    public function getUsuarios()
    {
       $usuario = new Application_Model_DbTable_Usuario();
       return $usuario->getAdapter()->fetchPairs( $usuario->select()->from( 'usuario', array('id_usuario', 'nome') )->where('id_usuario <>1')->order('nome'));
    }
    public function getUsuariosNegocio()
    {
       $usuario = new Application_Model_DbTable_Usuario();
       return $usuario->getAdapter()->fetchPairs( $usuario->select()->from( 'usuario', array('id_usuario', 'nome') )->where('fk_perfil =4')->order('nome'));
    }
    public function getUsuariosGerente()
    {
       $usuario = new Application_Model_DbTable_Usuario();
       return $usuario->getAdapter()->fetchPairs( $usuario->select()->from( 'usuario', array('id_usuario', 'nome') )->where('fk_perfil =5')->order('nome'));
    }
	public function getUsuariosProdutor()
    {
       $usuario = new Application_Model_DbTable_Usuario();
       return $usuario->getAdapter()->fetchPairs( $usuario->select()->from( 'usuario', array('id_usuario', 'nome') )->where('fk_perfil =2')->order('nome'));
    }
    public function addUsuario($nome, $senha, $email, $fk_perfil, $login,$fk_arquivo)
    {
    	$senha=sha1($senha);//criptografia da senha
        $data = array('nome' => $nome, 'senha' => $senha, 'email' => $email, 'fk_perfil' => $fk_perfil,'login'=>$login,'fk_arquivo'=>$fk_arquivo);
        $this->insert($data);
    }
     public function addUsuarioSemFoto($nome, $senha, $email, $fk_perfil, $login)
    {
    	$senha=sha1($senha);//criptografia da senha
        $data = array('nome' => $nome, 'senha' => $senha, 'email' => $email, 'fk_perfil' => $fk_perfil,'login'=>$login);
        $this->insert($data);
    }
    public function updateUsuario ($id,$nome,$senha,$email,$fk_perfil,$fk_arquivo)
    {
    	$senha=sha1($senha);//criptografia da senha
    	if($id=="1"){
    		$fk_perfil=1;
    	}
        $data = array('id_usuario'=>$id,'nome' => $nome, 'senha' => $senha, 'email' => $email, 'fk_perfil' => $fk_perfil,'fk_arquivo'=>$fk_arquivo);
         
       $this->update($data, 'id_usuario = ' . (int) $id);
    }
	public function updateUsuarioSemArquivo ($id,$nome,$senha,$email,$fk_perfil)
    {
    	$senha=sha1($senha);//criptografia da senha
    	if($id=="1"){
    		$fk_perfil=1;
    	}
        $data = array('id_usuario'=>$id,'nome' => $nome, 'senha' => $senha, 'email' => $email, 'fk_perfil' => $fk_perfil);
         
       $this->update($data, 'id_usuario = ' . (int) $id);
    }	
	public function updateAlterarPerfil ($id,$nome,$senha,$email,$fk_arquivo)
    {
    	$senha=sha1($senha);//criptografia da senha
    	if($id=="1"){
    		$fk_perfil=1;
    	}
        $data = array('id_usuario'=>$id,'nome' => $nome, 'senha' => $senha, 'email' => $email,'fk_arquivo'=>$fk_arquivo);
         
       $this->update($data, 'id_usuario = ' . (int) $id);
    }
    public function updateAlterarPerfilSemFoto ($id,$nome,$senha,$email)
    {
    	$senha=sha1($senha);//criptografia da senha
    	if($id=="1"){
    		$fk_perfil=1;
    	}
        $data = array('id_usuario'=>$id,'nome' => $nome, 'senha' => $senha, 'email' => $email);
         
       $this->update($data, 'id_usuario = ' . (int) $id);
    }
    public function deleteUsuario ($id)
    {
        $this->delete('id_usuario =' . (int) $id);
    }


}

