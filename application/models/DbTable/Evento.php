<?php

class Application_Model_DbTable_Evento extends Zend_Db_Table_Abstract
{

    protected $_name = 'evento';
	protected $_primary = 'id';
	protected $_referenceMap = array(
 		"usuario" => array(
			"columns" => array("fk_usuario_negocio"),
			"refTableClass" => "Application_Model_DbTable_Usuario",
			
			"refColumns" => array("id")
		),"usuario" => array(
			"columns" => array("fk_usuario_produtor"),
			"refTableClass" => "Application_Model_DbTable_Usuario",
			
			"refColumns" => array("id")
		),
		"usuario" => array(
			"columns" => array("fk_usuario_gerente"),
			"refTableClass" => "Application_Model_DbTable_Usuario",
			
			"refColumns" => array("id")
		),
		"casa_noturna" => array(
			"columns" => array("fk_casa_noturna"),
			"refTableClass" => "Application_Model_DbTable_CasaNoturna",
			"refColumns" => array("id")
		)

	);
	//protected $_dependentTables = array("Application_Model_DbTable_Usuario");
	

	public function getEvento ($id)
    {
        $id = (int) $id;
       // $row = $this->fetchRow("nome",null,'id = ' . $id);
        $select= $select =$this->_db->select()
             ->from(array('e' => 'evento'),array("*",'dt_evento' => new Zend_Db_Expr("DATE_FORMAT(e.dt_evento,'%d/%m/%Y')")))->where('id ='.$id);
             $row = $this->getAdapter()->fetchRow($select);
        if (! $row) {
            throw new Exception("N��o foi possivel encontrar a linha $id");
        }
        
        return $row;
       // return $row->toArray();
    }
public function getEventos ()
    {
       
       // $row = $this->fetchRow("nome",null,'id = ' . $id);
        $select= $select =$this->_db->select()
             ->from(array('e' => 'evento'),array("*",'dt_evento' => new Zend_Db_Expr("DATE_FORMAT(e.dt_evento,'%d/%m')")))->order('e.dt_evento asc');;
             $row = $this->getAdapter()->fetchAll($select);
        if (! $row) {
            throw new Exception("N��o foi possivel encontrar a linha $id");
        }
        
        return $row;
       // return $row->toArray();
    }
	public function getImagemEvento($id){	 
    	 $select =$this->_db->select()
             ->from(array('e' => 'arquivo'),array("nome"))->where('fk_evento ='.$id);
             
       $result = $this->getAdapter()->fetchAll($select);
       return $result;            
    }
   /* public function getUltimaAtualizacao(){	 
    	 $select =$this->_db->select()
             ->from(array('e' => 'ultima_atualizacao'))->order('id desc');

       $result = $this->getAdapter()->fetchRow($select);
       return $result;

                    
    }*/
	public function getEventosHomeUsuarioProdutor($idUsuario,$tipo_evento){	 
    	 $select =$this->_db->select()
             ->from(array('e' => 'evento'))
             ->joinInner(array('c' => 'casa_noturna'),('e.fk_casa_noturna =c.id'),array('cidade','regiao','nome as nomeCasaNoturna', 'dataFormatada2' => new Zend_Db_Expr("DATE_FORMAT(e.dt_evento,'%d/%m/%Y')"),'dataFormatada' => new Zend_Db_Expr("DATE_FORMAT(e.dt_evento,'%d/%m')")))
              ->joinInner(array('u' => 'usuario'),('u.id =e.fk_usuario_negocio'),array('nome as nomeUsuarioNegocio'))
               ->joinInner(array('u2' => 'usuario'),('u2.id =e.fk_usuario_gerente'),array('nome as nomeUsuarioGerente'))
                ->joinInner(array('u3' => 'usuario'),('u3.id =e.fk_usuario_produtor'),array('nome as nomeUsuarioProdutor'))
               ->where("tipo_evento=$tipo_evento and u3.id =".$idUsuario)
               ->order('e.dt_evento asc');
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;                
    }
	public function getEventosHomeUsuarioNegocio($idUsuario,$tipo_evento){	 
    	 $select =$this->_db->select()
             ->from(array('e' => 'evento'))
             ->joinInner(array('c' => 'casa_noturna'),('e.fk_casa_noturna =c.id'),array('cidade','regiao','nome as nomeCasaNoturna', 'dataFormatada2' => new Zend_Db_Expr("DATE_FORMAT(e.dt_evento,'%d/%m/%Y')"),'dataFormatada' => new Zend_Db_Expr("DATE_FORMAT(e.dt_evento,'%d/%m')")))
              ->joinInner(array('u' => 'usuario'),('u.id =e.fk_usuario_negocio'),array('nome as nomeUsuarioNegocio'))
               ->joinInner(array('u2' => 'usuario'),('u2.id =e.fk_usuario_gerente'),array('nome as nomeUsuarioGerente'))
                ->joinInner(array('u3' => 'usuario'),('u3.id =e.fk_usuario_produtor'),array('nome as nomeUsuarioProdutor'))
               ->where("tipo_evento=$tipo_evento and u.id =".$idUsuario)
               ->order('e.dt_evento asc');
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;                
    }
public function getEventosHomeUsuarioGerente($idUsuario,$tipo_evento){	 
    	 $select =$this->_db->select()
             ->from(array('e' => 'evento'))
             ->joinInner(array('c' => 'casa_noturna'),('e.fk_casa_noturna =c.id'),array('cidade','regiao','nome as nomeCasaNoturna', 'dataFormatada2' => new Zend_Db_Expr("DATE_FORMAT(e.dt_evento,'%d/%m/%Y')"),'dataFormatada' => new Zend_Db_Expr("DATE_FORMAT(e.dt_evento,'%d/%m')")))
              ->joinInner(array('u' => 'usuario'),('u.id =e.fk_usuario_negocio'),array('nome as nomeUsuarioNegocio'))
               ->joinInner(array('u2' => 'usuario'),('u2.id =e.fk_usuario_gerente'),array('nome as nomeUsuarioGerente'))
                ->joinInner(array('u3' => 'usuario'),('u3.id =e.fk_usuario_produtor'),array('nome as nomeUsuarioProdutor'))
               ->where(" tipo_evento=$tipo_evento and  u2.id =".$idUsuario)
               ->order('e.dt_evento asc');
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;                
    }
    public function getEventosHome($tipo_evento){	 
    	 $select =$this->_db->select()
             ->from(array('e' => 'evento'))
             ->joinInner(array('c' => 'casa_noturna'),('e.fk_casa_noturna =c.id'),array('cidade','regiao','nome as nomeCasaNoturna', 'dataFormatada2' => new Zend_Db_Expr("DATE_FORMAT(e.dt_evento,'%d/%m/%Y')"),'dataFormatada' => new Zend_Db_Expr("DATE_FORMAT(e.dt_evento,'%d/%m')")))
              ->joinInner(array('u' => 'usuario'),('u.id =e.fk_usuario_negocio'),array('nome as nomeUsuarioNegocio'))
               ->joinInner(array('u2' => 'usuario'),('u2.id =e.fk_usuario_gerente'),array('nome as nomeUsuarioGerente'))
                ->joinInner(array('u3' => 'usuario'),('u3.id =e.fk_usuario_produtor'),array('nome as nomeUsuarioProdutor'))
                ->where(" tipo_evento=$tipo_evento ")
               ->order('e.dt_evento asc');
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;                
    }
	public function getOutrosEventos($tipo_evento,$idCasaNoturna){	 
    	 $select =$this->_db->select()
             ->from(array('e' => 'evento'))
             ->joinInner(array('c' => 'casa_noturna'),('e.fk_casa_noturna =c.id'),array('cidade','regiao','nome as nomeCasaNoturna', 'dataFormatada2' => new Zend_Db_Expr("DATE_FORMAT(e.dt_evento,'%d/%m/%Y')"),'dataFormatada' => new Zend_Db_Expr("DATE_FORMAT(e.dt_evento,'%d/%m')")))
              ->joinInner(array('u' => 'usuario'),('u.id =e.fk_usuario_negocio'),array('nome as nomeUsuarioNegocio'))
               ->joinInner(array('u2' => 'usuario'),('u2.id =e.fk_usuario_gerente'),array('nome as nomeUsuarioGerente'))
                ->joinInner(array('u3' => 'usuario'),('u3.id =e.fk_usuario_produtor'),array('nome as nomeUsuarioProdutor'))
                ->where(" tipo_evento=$tipo_evento and c.id=$idCasaNoturna")
               ->order('e.dt_evento asc');
               
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;                
    }
    /*busca lista de casa noturnas com a quantidade de eventos associados*/
 	public function getCasasOutrosEventos($tipo_evento){	 
    	 $select =$this->_db->select()
    	 ->from(array('c' => 'casa_noturna'),array('id as id_evento','nome as nomeCasaNoturna','regiao','cidade','totalEventos' => 'COUNT(*)'))
    	  ->joinLeft(array('e' => 'evento'),('e.fk_casa_noturna =c.id'))
    	  ->where(" tipo_evento=$tipo_evento ")
    	  ->group('c.id')
    	  ->order('c.nome asc');
             /*->from(array('e' => 'evento'))
             ->joinInner(array('c' => 'casa_noturna'),('e.fk_casa_noturna =c.id'),array('cidade','regiao','nome as nomeCasaNoturna', 'dataFormatada2' => new Zend_Db_Expr("DATE_FORMAT(e.dt_evento,'%d/%m/%Y')"),'dataFormatada' => new Zend_Db_Expr("DATE_FORMAT(e.dt_evento,'%d/%m')")))
              ->joinInner(array('u' => 'usuario'),('u.id =e.fk_usuario_negocio'),array('nome as nomeUsuarioNegocio'))
               ->joinInner(array('u2' => 'usuario'),('u2.id =e.fk_usuario_gerente'),array('nome as nomeUsuarioGerente'))
                ->joinInner(array('u3' => 'usuario'),('u3.id =e.fk_usuario_produtor'),array('nome as nomeUsuarioProdutor'))
                ->where(" tipo_evento=$tipo_evento ")
               ->order('e.dt_evento asc');*/
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;                
    }

public function getEventosExecNegociosRankingGeral(){	
$data_atual = date("Y-m-d 00:00:00"); 
Zend_Registry::get('logger')->log("Data atual=".$data_atual , Zend_Log::INFO);
    	 $select =$this->_db->select()
             ->from(array('e' => 'evento'))
             ->joinInner(array('c' => 'casa_noturna'),'e.fk_casa_noturna =c.id',array('mediaPonderada' => new Zend_Db_Expr("SUM(e.qt_garrafas)/COUNT(*)"),'totalEventos' => 'COUNT(*)','totalGarrafas' => 'SUM(e.qt_garrafas)','cidade','regiao','nome as nomeCasaNoturna', 'dataFormatada' => new Zend_Db_Expr("DATE_FORMAT(e.dt_evento,'%d/%m/%Y')")))
              ->joinInner(array('u' => 'usuario'),('u.id =e.fk_usuario_negocio'),array('nome as nomeUsuarioNegocio'))
               ->joinInner(array('u2' => 'usuario'),('u2.id =e.fk_usuario_gerente'),array('nome as nomeUsuarioGerente'))
               ->where(" e.tipo_evento=0 and e.dt_evento <>'0000-00-00 00:00:00' and e.dt_evento < '$data_atual'")
			   ->group('u.id')
               
               ->order(array('mediaPonderada DESC'));
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;                
    }
	public function getEventosExecNegociosRanking($id_gerente){	 
    	  $select =$this->_db->select()
             ->from(array('e' => 'evento'))
             ->joinInner(array('c' => 'casa_noturna'),'e.fk_casa_noturna =c.id',array('mediaPonderada' => new Zend_Db_Expr("SUM(e.qt_garrafas)/COUNT(*)"),'totalEventos' => 'COUNT(*)','totalGarrafas' => 'SUM(e.qt_garrafas)','cidade','regiao','nome as nomeCasaNoturna', 'dataFormatada' => new Zend_Db_Expr("DATE_FORMAT(e.dt_evento,'%d/%m/%Y')")))
              ->joinInner(array('u' => 'usuario'),('u.id =e.fk_usuario_negocio'),array('nome as nomeUsuarioNegocio'))
               ->joinInner(array('u2' => 'usuario'),('u2.id =e.fk_usuario_gerente'),array('nome as nomeUsuarioGerente'))
               ->group('u.id')
               ->order(array('mediaPonderada DESC'));
              
        $result = $this->getAdapter()->fetchAll($select);
        $i=0;
        $gerente=array();
        if (count($result) > 0) {
        	
            foreach ($result as $value) {
            	$i++;
            	if($value["fk_usuario_negocio"]==$id_gerente){
            		$value["posicaoRanking"]=$i;
            		$gerente[]=$value;
            	return $gerente;	
            	}
          
            }
        }
        return $gerente;                
    }
 	public function updateAprovaObservacao ($id, $observacoes,$aprovado_observacao)
    {
       $data = array(  'observacoes' => $observacoes, 'aprovado_observacao' => $aprovado_observacao);
        $this->update($data, 'id = ' . (int) $id);
    }
    public function getEventosGerenteRanking($id_gerente){	 
    	 $select =$this->_db->select()
             ->from(array('e' => 'evento'))
             ->joinInner(array('c' => 'casa_noturna'),'e.fk_casa_noturna =c.id',array('totalEventos' => 'COUNT(*)','totalGarrafas' => 'SUM(e.qt_garrafas)','cidade','regiao','nome as nomeCasaNoturna', 'dataFormatada' => new Zend_Db_Expr("DATE_FORMAT(e.dt_evento,'%d/%m/%Y')")))
              ->joinInner(array('u' => 'usuario'),('u.id =e.fk_usuario_negocio'),array('nome as nomeUsuarioNegocio'))
               ->joinInner(array('u2' => 'usuario'),('u2.id =e.fk_usuario_gerente'),array('nome as nomeUsuarioGerente'))
               ->group('u2.id')
			    ->order(array('e.qt_garrafas DESC'));
              // ->order(array('totalGarrafas DESC'));
        $result = $this->getAdapter()->fetchAll($select);
        $i=0;
        $gerente=array();
        if (count($result) > 0) {
        	
            foreach ($result as $value) {
            	$i++;
            	if($value["fk_usuario_gerente"]==$id_gerente){
            		$value["posicaoRanking"]=$i;
            		$gerente[]=$value;
            	return $gerente;	
            	}
          
            }
        }
        return $gerente;                
    }
 	public function getEventosGerenteRankingGeral(){	 
    	 $select =$this->_db->select()
             ->from(array('e' => 'evento'))
             ->joinInner(array('c' => 'casa_noturna'),'e.fk_casa_noturna =c.id',array('maiorGarrafaVendida' => 'Max(e.qt_garrafas)','totalEventos' => 'COUNT(*)','totalGarrafas' => 'SUM(e.qt_garrafas)','cidade','regiao','nome as nomeCasaNoturna', 'dataFormatada' => new Zend_Db_Expr("DATE_FORMAT(e.dt_evento,'%d/%m/%Y')")))
              ->joinInner(array('u' => 'usuario'),('u.id =e.fk_usuario_negocio'),array('nome as nomeUsuarioNegocio'))
               ->joinInner(array('u2' => 'usuario'),('u2.id =e.fk_usuario_gerente'),array('nome as nomeUsuarioGerente'))
               ->group('u2.id')
			   ->order(array('maiorGarrafaVendida DESC'));
              // ->order(array('totalGarrafas DESC'));
              
       $result = $this->getAdapter()->fetchAll($select);
       return $result;                
    }
    public function addEvento ($nome, $dt_evento, $qt_garrafas, $fk_usuario_negocio, $fk_usuario_gerente, $fk_casa_noturna,$fk_usuario_produtor, $observacoes,$tipo_evento)
    {
        $data = array('nome' => $nome, 'dt_evento' => $dt_evento, 'qt_garrafas' => $qt_garrafas, 'fk_usuario_negocio' => $fk_usuario_negocio, 'fk_usuario_produtor' => $fk_usuario_produtor, 'fk_usuario_gerente' => $fk_usuario_gerente, 'fk_casa_noturna' => $fk_casa_noturna, 'observacoes' => $observacoes,  'tipo_evento' => $tipo_evento);
        $this->insert($data);
    }
    public function updateEvento ($id,$nome, $dt_evento, $qt_garrafas, $fk_usuario_negocio, $fk_usuario_gerente, $fk_casa_noturna,$fk_usuario_produtor, $observacoes,$tipo_evento,$pergunta1)
    {
       $data = array('nome' => $nome, 'dt_evento' => $dt_evento, 'qt_garrafas' => $qt_garrafas, 'fk_usuario_negocio' => $fk_usuario_negocio, 'fk_usuario_produtor' => $fk_usuario_produtor, 'fk_usuario_gerente' => $fk_usuario_gerente, 'fk_casa_noturna' => $fk_casa_noturna, 'observacoes' => $observacoes,  'tipo_evento' => $tipo_evento,'pergunta1' => $pergunta1);
        $this->update($data, 'id = ' . (int) $id);
    }
 public function updateEventoObservacao ($id, $aprovado_observacao,$observacoes,$qt_garrafas,$pergunta1,$pergunta2,$pergunta3,$pergunta4,$pergunta5,$pergunta6,$pergunta7,$pergunta8,$pergunta9)
    {
       $data = array( 'aprovado_observacao' => $aprovado_observacao, 'observacoes' => $observacoes,'qt_garrafas'=>$qt_garrafas,'pergunta1' => $pergunta1,'pergunta2' => $pergunta2,'pergunta3' => $pergunta3,'pergunta4' => $pergunta4,'pergunta5' => $pergunta5,'pergunta6' => $pergunta6,'pergunta7' => $pergunta7,'pergunta8' => $pergunta8,'pergunta9' => $pergunta9);
        $this->update($data, 'id = ' . (int) $id);
    }
    public function deleteEvento ($id)
    {
        $this->delete('id =' . (int) $id);
    }
    
    

}

