<?php

class Application_Model_DbTable_Referencia extends Zend_Db_Table_Abstract
{

    protected $_name = 'referencia';
	protected $_primary = 'id_referencia';
	
	protected $_dependentTables = array("Application_Model_DbTable_Caracteristica");
	protected $_referenceMap = array(
 		"produto" => array(
			"columns" => array("fk_produto"),
			"refTableClass" => "Application_Model_DbTable_Produto",
			
			"refColumns" => array("id_produto")
		),"arquivo" => array(
			"columns" => array("fk_arquivo"),
			"refTableClass" => "Application_Model_DbTable_Arquivo",
			
			"refColumns" => array("id_arquivo")
		)

	);

	public function getReferencia ($id)
    {
        $id = (int) $id;
        $row = $this->fetchRow('id_referencia = ' . $id);
        if (! $row) {
            throw new Exception("Não foi possivel encontrar a linha $id");
        }
        return $row->toArray();
    }
   
	public function getReferencias(){	 
    	 $select =$this->_db->select()
             ->from(array('p' => 'referencia'))
        		->order('p.nome asc');
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;                
    }
    

	public function retornaCaracteristicas($id_referencia){
		
		 $select =$this->_db->select()
             ->from(array('r' => 'referencia'),null)
             ->joinInner(array('c' => 'caracteristica'),('c.fk_referencia =r.id_referencia'))
             ->where('  r.id_referencia = ' . $id_referencia);
			/*->joinInner(array('a' => 'arquivo'),('a.id_arquivo =pha.fk_arquivo'),array('nome as nomeArquivo'))
			->joinInner(array('p' => 'projeto'),('p.id_projeto =pha.fk_projeto'),array('nome as nomeProjeto'))
            ->where('  fk_projeto = ' . $fk_projeto);*/
              
       $result = $this->getAdapter()->fetchAll($select);
       $nomeCaracteristica=array();
       $valorCaracteristica=array();
       if(count($result)>0){
       	 foreach($result as $value){
       	 	$nomeCaracteristica[]=$value["nome"];
       	 	$valorCaracteristica[]=$value["valor"];
       	 }
       	 $result=array();
     
       	 $result["nomeCaracteristica"]=$nomeCaracteristica;
       	 $result["valorCaracteristica"]=$valorCaracteristica;
       }else{
       	return array();
       }
       return $result;  
       
       
       
	}

    public function addReferencia($fk_arquivo,$ativo,$precode,$precopor,$fretemedio,$disponivel,$saldo,$codigoean,$fk_produto)
    {
    	$dataAtualizacao =new Zend_Date();
    	$novaData=$dataAtualizacao->get('YYYY-MM-dd HH:mm:ss');
        $data = array('atualizadoDotz'=>0,'dataAtualizacao'=>$novaData,'fk_arquivo' => $fk_arquivo,'ativo' => $ativo,'precode' => $precode,'precopor' => $precopor,'fretemedio' => $fretemedio,'disponivel' => $disponivel,'saldo' => $saldo,'codigoean' => $codigoean,'fk_produto' => $fk_produto);
        return $this->insert($data);
    }
    public function updateReferencia ($id,$listaNomeCaract,$listaValorCarac,$ativo,$precode,$precopor,$fretemedio,$disponivel,$saldo,$codigoean,$nomeArquivo, $extensao)
    {
    	$this->_db->beginTransaction();
 
		try {
		    $arquivo = new Application_Model_DbTable_Arquivo();
       		
       		$referencia = new Application_Model_DbTable_Referencia();
	   		$fk_arquivo=$arquivo->addArquivo($nomeArquivo, $extensao);	
			$caracteristica = new Application_Model_DbTable_Caracteristica();
       		$caracteristica->deleteCaracteristicaPorReferencia($id);
	   		if($listaNomeCaract>0){
	   			$i=0;
	   			foreach ($listaNomeCaract as $value){
	   				$id_caracteristica=$caracteristica->addCaracteristica($value, $listaValorCarac[$i],$id);
	   				
	   				$i++;	
	   			}
	   		}
	   		$data = array('id_referencia'=>$id,'ativo' => $ativo,'precode' => $precode,'precopor' => $precopor,'fretemedio' => $fretemedio,'disponivel' => $disponivel,'saldo' => $saldo,'codigoean' => $codigoean,'fk_arquivo' => $fk_arquivo,'atualizadoDotz'=>0);
         
      	    $this->update($data, 'id_referencia = ' . (int) $id);
		    $this->_db->commit();
		    Zend_Registry::get('logger')->log("commit updateReferencia", Zend_Log::INFO);
		    return true;
		} catch (Exception $e) {
			Zend_Registry::get('logger')->log("rollBack updateReferencia", Zend_Log::INFO);
		    // If any of the queries failed and threw an exception,
		    // we want to roll back the whole transaction, reversing
		    // changes made in the transaction, even those that succeeded.
		    // Thus all changes are committed together, or none are.
		    $this->_db->rollBack();
		   // Zend_Registry::get('logger')->log("erro".$e->getMessage(), Zend_Log::INFO);
		   throw new Exception($e->getMessage());
		  
		}
		return false;
        
    }
    public function updateReferenciaSemFoto ($id,$listaNomeCaract,$listaValorCarac,$ativo,$precode,$precopor,$fretemedio,$disponivel,$saldo,$codigoean)
    {
        $this->_db->beginTransaction();
 
		try {
		    
       		
       		$referencia = new Application_Model_DbTable_Referencia();	
			$caracteristica = new Application_Model_DbTable_Caracteristica();
       		$caracteristica->deleteCaracteristicaPorReferencia($id);
	   		if($listaNomeCaract>0){
	   			$i=0;
	   			foreach ($listaNomeCaract as $value){
	   				$id_caracteristica=$caracteristica->addCaracteristica($value, $listaValorCarac[$i],$id);
	   				
	   				$i++;	
	   			}
	   		}
	   		$data = array('id_referencia'=>$id,'ativo' => $ativo,'precode' => $precode,'precopor' => $precopor,'fretemedio' => $fretemedio,'disponivel' => $disponivel,'saldo' => $saldo,'codigoean' => $codigoean,'atualizadoDotz'=>0);
            $this->update($data, 'id_referencia = ' . (int) $id);
		    $this->_db->commit();
		    Zend_Registry::get('logger')->log("commit updateReferencia", Zend_Log::INFO);
		    return true;
		    
		 Zend_Registry::get('logger')->log("Alterado com sucesso", Zend_Log::INFO);
		} catch (Exception $e) {
			Zend_Registry::get('logger')->log("rollBack updateReferencia", Zend_Log::INFO);
		    // If any of the queries failed and threw an exception,
		    // we want to roll back the whole transaction, reversing
		    // changes made in the transaction, even those that succeeded.
		    // Thus all changes are committed together, or none are.
		    $this->_db->rollBack();
		   // Zend_Registry::get('logger')->log("erro".$e->getMessage(), Zend_Log::INFO);
		   throw new Exception($e->getMessage());
		  
		}
		return false;
        
    }
    public function updateReferenciaInseridoDotz ()
    {
    	$data = array('inseridoDotz'=>'1','atualizadoDotz'=>'1');
    
    	return $this->update($data,'inseridoDotz = 0' );
    }
    public function updateReferenciaAtualizadoDotz ($id_referencia)
    {
    	$data = array('atualizadoDotz'=>'1');
    	Zend_Registry::get('logger')->log("updateReferenciaAtualizadoDotz", Zend_Log::INFO);
    	return $this->update($data, 'id_referencia = ' . (int) $id_referencia);
    	
    }
    public function deleteReferencia ($id)
    {
    	
    	$referencia=$this->getReferencia ($id);
    	if($referencia["inseridoDotz"]==0){
    		$this->delete('id_referencia =' . (int) $id);
    	}else{
    		throw new Exception("Não pode excluir uma referencia de produto que já foi enviado para dotz");
    	}
    	Zend_Registry::get('logger')->log($referencia, Zend_Log::INFO);
        //
    }
	


}

