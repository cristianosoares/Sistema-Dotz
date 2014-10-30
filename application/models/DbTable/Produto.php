<?php

class Application_Model_DbTable_Produto extends Zend_Db_Table_Abstract
{

    protected $_name = 'produto';
	protected $_primary = 'id_produto';
	
	protected $_dependentTables = array("Application_Model_DbTable_Referencia");

	public function getProduto ($id)
    {
        $id = (int) $id;
        $row = $this->fetchRow('id_produto = ' . $id);
        if (! $row) {
            throw new Exception("Não foi possivel encontrar a linha $id");
        }
        return $row->toArray();
    }
	public function getProdutosReferencia(){	 
    	     $select =$this->_db->select()
             ->from(array('r' => 'referencia'))
             ->joinInner(array('p' => 'produto'),('p.id_produto =r.fk_produto'))
             ->joinInner(array('a' => 'arquivo'),('a.id_arquivo =r.fk_arquivo'),array('nome as nomeArquivo'));
			/*->joinInner(array('a' => 'arquivo'),('a.id_arquivo =pha.fk_arquivo'),array('nome as nomeArquivo'))
			->joinInner(array('p' => 'projeto'),('p.id_projeto =pha.fk_projeto'),array('nome as nomeProjeto'))
            ->where('  fk_projeto = ' . $fk_projeto);*/
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;                           
	}
	public function getProdutosNovos(){
		
		$select =$this->_db->select()
		->from(array('r' => 'referencia'))
		->joinInner(array('p' => 'produto'),('p.id_produto =r.fk_produto'))
		->joinInner(array('a' => 'arquivo'),('a.id_arquivo =r.fk_arquivo'),array('nome as nomeArquivo'))
		->where('  r.inseridoDotz =0 ' )
		->order('  p.id_produto desc ' );
		
	
		$result = $this->getAdapter()->fetchAll($select);
		return $result;
	}
	public function getProdutosNovosIdreferencia($id_produto){
		
		$select =$this->_db->select()
		->from(array('r' => 'referencia'))
		->joinInner(array('p' => 'produto'),('p.id_produto =r.fk_produto'))
		->joinInner(array('a' => 'arquivo'),('a.id_arquivo =r.fk_arquivo'),array('nome as nomeArquivo'))
		->where('  r.inseridoDotz =0 and r.fk_produto='.$id_produto )
		->order('  p.id_produto desc ' );
		
	
		$result = $this->getAdapter()->fetchAll($select);
		$referencia = new Application_Model_DbTable_Referencia();
		$i=0;
		foreach($result as $value){
			$listaCaracteristica=$referencia->retornaCaracteristicas($value["id_referencia"]);
			//Zend_Registry::get('logger')->log($listaReferencia, Zend_Log::INFO);
			$result[$i]["caracteristicas"]=$listaCaracteristica;
		$i++;
		}
		return $result;
	}
	//busca produtos antigos que precisam ser atualizados
	public function getProdutosAntigosIdreferencia($fk_produto,$where){
	
		$select =$this->_db->select()
		->from(array('r' => 'referencia'))
		->joinInner(array('p' => 'produto'),('p.id_produto =r.fk_produto'))
		->joinInner(array('a' => 'arquivo'),('a.id_arquivo =r.fk_arquivo'),array('nome as nomeArquivo'))
		->where('  r.inseridoDotz =1 and r.fk_produto='.$fk_produto." ".$where )
		->order('  p.id_produto desc ' );
	
	
		$result = $this->getAdapter()->fetchAll($select);
		Zend_Registry::get('logger')->log($select->__toString(), Zend_Log::INFO);
		$referencia = new Application_Model_DbTable_Referencia();
		$i=0;
		foreach($result as $value){
			$listaCaracteristica=$referencia->retornaCaracteristicas($value["id_referencia"]);
			//Zend_Registry::get('logger')->log($listaReferencia, Zend_Log::INFO);
			$result[$i]["caracteristicas"]=$listaCaracteristica;
			$i++;
		}
		return $result;
	}
	public function getProdutosNovosXml(){
		
		$select =$this->_db->select()
		->from(array('r' => 'referencia'))
		->joinInner(array('p' => 'produto'),('p.id_produto =r.fk_produto'))
		->joinInner(array('a' => 'arquivo'),('a.id_arquivo =r.fk_arquivo'),array('nome as nomeArquivo'))
		->where('  r.inseridoDotz =0 ' )
		->group('p.id_produto')
		->order('  p.id_produto desc ' );
		
	
		$result = $this->getAdapter()->fetchAll($select);
		$i=0;
		foreach($result as $value){
		$listaReferencia=$this->getProdutosNovosIdreferencia($value["id_produto"]);
		//Zend_Registry::get('logger')->log($listaReferencia, Zend_Log::INFO);
		$result[$i]["referencias"]=$listaReferencia;
		$i++;
		}
		return $result;
	}
	//parametro lista de ids com a referencia do produto
	public function getProdutosAtualizadosXml($listaReferencias){
	$where="and (";
	$i=0;
		foreach ($listaReferencias as $ref){
			if($i==0){
				$where=$where."id_referencia ='$ref' ";
			}else{
				$where=$where." or id_referencia ='$ref' ";
			}
			$i++;
			
		}
		$where=$where.")";
		$select =$this->_db->select()
		->from(array('r' => 'referencia'))
		->joinInner(array('p' => 'produto'),('p.id_produto =r.fk_produto'))
		->joinInner(array('a' => 'arquivo'),('a.id_arquivo =r.fk_arquivo'),array('nome as nomeArquivo'))
		->where(" r.inseridoDotz =1 and r.atualizadoDotz=0 ".$where )
		->group('p.id_produto')
		->order('  p.id_produto desc ' );
		Zend_Registry::get('logger')->log($select->__toString(), Zend_Log::INFO);
		//Zend_Registry::get('logger')->log($where, Zend_Log::INFO);
		//Zend_Registry::get('logger')->log("Depois where", Zend_Log::INFO);
		$result = $this->getAdapter()->fetchAll($select);
		$i=0;
		//Zend_Registry::get('logger')->log("Antes result", Zend_Log::INFO);
		//Zend_Registry::get('logger')->log($result, Zend_Log::INFO);
		//Zend_Registry::get('logger')->log("Antes foreach", Zend_Log::INFO);
		foreach($result as $value){
			$listaReferencia=$this->getProdutosAntigosIdreferencia($value["fk_produto"],$where);
			Zend_Registry::get('logger')->log("ID_REFERENCIA=".$value["fk_produto "], Zend_Log::INFO);
			$result[$i]["referencias"]=$listaReferencia;
			$i++;
		}
		Zend_Registry::get('logger')->log($result, Zend_Log::INFO);
		return $result;
	}
	
	/*Retorna produtos que nao foram atualizados na dotz*/
	public function getProdutosAlterar(){
		$select =$this->_db->select()
		->from(array('r' => 'referencia'))
		->joinInner(array('p' => 'produto'),('p.id_produto =r.fk_produto'))
		->joinInner(array('a' => 'arquivo'),('a.id_arquivo =r.fk_arquivo'),array('nome as nomeArquivo'))
		->where('  r.inseridoDotz =1 and r.atualizadoDotz=0 ' );
		$result = $this->getAdapter()->fetchAll($select);
		return $result;
	}
	public function getProdutoReferencia($fk_referencia){	 
    	     $select =$this->_db->select()
             ->from(array('r' => 'referencia'))
             ->joinInner(array('p' => 'produto'),('p.id_produto =r.fk_produto'))
             ->joinInner(array('a' => 'arquivo'),('a.id_arquivo =r.fk_arquivo'),array('nome as nomeArquivo'))
             ->where('  r.id_referencia = ' . $fk_referencia);
			/*->joinInner(array('a' => 'arquivo'),('a.id_arquivo =pha.fk_arquivo'),array('nome as nomeArquivo'))
			->joinInner(array('p' => 'projeto'),('p.id_projeto =pha.fk_projeto'),array('nome as nomeProjeto'))
            ->where('  fk_projeto = ' . $fk_projeto);*/
              
       $result = $this->getAdapter()->fetchRow($select);
       return $result;                          
	}
	public function getProdutosReferenciaInativoAtivo($inativo){	 
    	     $select =$this->_db->select()
             ->from(array('r' => 'referencia'))
             ->joinInner(array('p' => 'produto'),('p.id_produto =r.fk_produto'))
             ->joinInner(array('a' => 'arquivo'),('a.id_arquivo =r.fk_arquivo'),array('nome as nomeArquivo'))
             ->where('ativo = ' . $inativo);
			/*->joinInner(array('a' => 'arquivo'),('a.id_arquivo =pha.fk_arquivo'),array('nome as nomeArquivo'))
			->joinInner(array('p' => 'projeto'),('p.id_projeto =pha.fk_projeto'),array('nome as nomeProjeto'))
            ->where('  fk_projeto = ' . $fk_projeto);*/
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;                           
	}
	public function getProdutos(){	 
    	 $select =$this->_db->select()
             ->from(array('p' => 'produto'))
        		->order('p.nome asc');
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;                
    }
    /*Retorna o produto atraves da referencia*/
	public function getProdutoAtravesReferencia($id_produto){	 
    	 $select =$this->_db->select()
             ->from(array('r' => 'referencia'))
             ->joinInner(array('p' => 'produto'),('p.id_produto =r.fk_produto'))
        		->where("r.fk_produto= '$id_referencia'");
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;                
    }


 	public function addProduto($nome,$descricao,$urlGeral,$palavrachave){
 		$data = array('nome' => $nome,'palavrachave' => $palavrachave,'descricao' => $descricao,'url' => $urlGeral);
        return $this->insert($data);
 	}
    public function addProdutoReferencia($listaNomeCaract,$listaValorCaract,$nome,$descricao,$urlGeral,$palavrachave,$nomeArquivo, $extensao,$ativo,$precode,$precopor,$fretemedio,$disponivel,$saldo,$codigoean)
    {
        
       
      $this->_db->beginTransaction();
 
		try {
		    $arquivo = new Application_Model_DbTable_Arquivo();
       		$produto = new Application_Model_DbTable_Produto();
       		$referencia = new Application_Model_DbTable_Referencia();
       		$caracteristica = new Application_Model_DbTable_Caracteristica();
       		
	   		$fk_arquivo=$arquivo->addArquivo($nomeArquivo, $extensao);
	   		$id_produto=$produto->addProduto($nome,$descricao,$urlGeral,$palavrachave);
	   		$id_referencia=$referencia->addReferencia($fk_arquivo,$ativo,$precode,$precopor,$fretemedio,$disponivel,$saldo,$codigoean,$id_produto);
	   		
	   		if($listaNomeCaract>0){
	   			$i=0;
	   			foreach ($listaNomeCaract as $value){
	   				$id_caracteristica=$caracteristica->addCaracteristica($value, $listaValorCaract[$i],$id_referencia);
	   				
	   				$i++;	
	   			}
	   		}
	   		
	   		Zend_Registry::get('logger')->log("Ativo=$ativo Disponivel= $disponivel", Zend_Log::INFO);
		    $this->_db->commit();
		    return $id_produto;
		 Zend_Registry::get('logger')->log("Adicionado com sucesso", Zend_Log::INFO);
		} catch (Exception $e) {
		    // If any of the queries failed and threw an exception,
		    // we want to roll back the whole transaction, reversing
		    // changes made in the transaction, even those that succeeded.
		    // Thus all changes are committed together, or none are.
		    $this->_db->rollBack();
		   // Zend_Registry::get('logger')->log("erro".$e->getMessage(), Zend_Log::INFO);
		   throw new Exception($e->getMessage());
		  
		}
	   
    }
	public function addReferenciaAoProduto($listaNomeCaract,$listaValorCaract,$fk_produto,$nomeArquivo, $extensao,$ativo,$precode,$precopor,$fretemedio,$disponivel,$saldo,$codigoean)
    {
        
       
      $this->_db->beginTransaction();
 
		try {
		    $arquivo = new Application_Model_DbTable_Arquivo();
       		
       		$referencia = new Application_Model_DbTable_Referencia();
	   		$fk_arquivo=$arquivo->addArquivo($nomeArquivo, $extensao);
	   		$id_referencia=$referencia->addReferencia($fk_arquivo,$ativo,$precode,$precopor,$fretemedio,$disponivel,$saldo,$codigoean,$fk_produto);
	   		Zend_Registry::get('logger')->log("Ativo=$ativo Disponivel= $disponivel", Zend_Log::INFO);
			$caracteristica = new Application_Model_DbTable_Caracteristica();
       	
	   		if($listaNomeCaract>0){
	   			$i=0;
	   			foreach ($listaNomeCaract as $value){
	   				$id_caracteristica=$caracteristica->addCaracteristica($value, $listaValorCaract[$i],$id_referencia);
	   				
	   				$i++;	
	   			}
	   		}
		    $this->_db->commit();
		    return $fk_produto;
		 Zend_Registry::get('logger')->log("Adicionado com sucesso", Zend_Log::INFO);
		} catch (Exception $e) {
		    // If any of the queries failed and threw an exception,
		    // we want to roll back the whole transaction, reversing
		    // changes made in the transaction, even those that succeeded.
		    // Thus all changes are committed together, or none are.
		    $this->_db->rollBack();
		   // Zend_Registry::get('logger')->log("erro".$e->getMessage(), Zend_Log::INFO);
		   throw new Exception($e->getMessage());
		  
		}
	   
    }
    public function updateProduto ($id,$nome,$descricao,$url,$palavrachave)
    {
        $data = array('id_produto'=>$id,'nome' => $nome,'palavrachave' => $palavrachave,'descricao' => $descricao);
         
       return $this->update($data, 'id_produto = ' . (int) $id);
    }
   
    
    public function pr ($id)
    {
        $this->delete('id_produto =' . (int) $id);
    }
	


}

