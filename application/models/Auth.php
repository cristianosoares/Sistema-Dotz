<?php
class Application_Model_Auth
{
	public static function login($login, $senha)
	{
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		//Inicia o adaptador Zend_Auth para banco de dados
		$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
		$authAdapter->setTableName('usuario')
					->setIdentityColumn('login')
					->setCredentialColumn('senha')
					->setCredentialTreatment('SHA1(?)');
		Zend_Registry::get('logger')->log("authh", Zend_Log::INFO);
		//Define os dados para processar o login
		$authAdapter->setIdentity($login)
					->setCredential($senha);
		//Faz inner join dos dados do perfil no SELECT do Auth_Adapter
		$select = $authAdapter->getDbSelect();
		$select->join( array('p' => 'perfil'), 'p.id_perfil = usuario.fk_perfil', array('nome_perfil' => 'nome') )
		->joinLeft( array('a' => 'arquivo'), 'a.id_arquivo = usuario.fk_arquivo', array('nome_imagem' => 'nome') );
		//Efetua o login
		$auth = Zend_Auth::getInstance();
		
		//Zend_Registry::get('logger')->log($select, Zend_Log::INFO);
		$result = $auth->authenticate($authAdapter);
		//Zend_Registry::get('logger')->log($result, Zend_Log::INFO);
			Zend_Registry::get('logger')->log("antes if login isValid", Zend_Log::INFO);
		//Verifica se o login foi efetuado com sucesso
		if ( $result->isValid() ) {
			//Recupera o objeto do usuário, sem a senha
			$info = $authAdapter->getResultRowObject(null, 'senha');

			$usuario = new Application_Model_Usuario();
			//$usuario->setFullName( $info->nome_completo );
			$usuario->setUserName( $info->login );
			$usuario->setFKPerfil( $info->fk_perfil );
			$usuario->setId ($info->id_usuario );
			$usuario->setRoleId( $info->nome_perfil );
			$usuario->setImagem($info->nome_imagem);
			//Zend_Registry::get('logger')->log("papel", Zend_Log::INFO);
//Zend_Registry::get('logger')->log($usuario, Zend_Log::INFO);

			$storage = $auth->getStorage();
			$storage->write($usuario);

			return true;
		}
		throw new Exception('User or password incorrect');
	}
}