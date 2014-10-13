<?php

class Application_Model_DbTable_Arquivo extends Zend_Db_Table_Abstract
{

    protected $_name = 'arquivo';
    protected $_dependentTables = array("Application_Model_DbTable_Referencia","Application_Model_DbTable_Usuario");
   /* protected $_referenceMap = array(
 		"evento" => array(
			"columns" => array("fk_evento"),
			"refTableClass" => "Application_Model_DbTable_Evento",
			
			"refColumns" => array("id")
		)

	);*/

	public function getArquivo ($id)
    {
        $id = (int) $id;
        $row = $this->fetchRow('id_arquivo = ' . $id);
        if (! $row) {
            throw new Exception("Não foi possível encontrar linha $id");
        }
        return $row->toArray();
    }
    public function existeArquivo($nome)
    {
    	Zend_Registry::get('logger')->log("Dentro function existeArquivo", Zend_Log::INFO);
    	$id = (int) $id;
    	$row = $this->fetchRow("nome = '$nome'");
    	if (! $row) {
    		return 0;
    		//throw new Exception("Não foi possível encontrar linha $id");
    	}
    	return 1;
    }
    public function addArquivo ($nome, $extensao)
    {
        $data = array('nome' => $nome, 'extensao' => $extensao);
        return $this->insert($data);
      
    }
    public function updateArquivo($id,$nome, $extensao)
    {
         $data = array('nome' => $nome, 'extensao' => $extensao);
        $this->update($data, 'id_arquivo = ' . (int) $id);
    }
    public function deleteArquivo ($id)
    {
    	$id = (int) $id;
        $row = $this->fetchRow('id_arquivo = ' . $id);
        if (! $row) {
            throw new Exception("Não foi possível encontrar linha $id");
        }
        $arquivo=$row->toArray();
        Zend_Registry::get('logger')->log("arquivo =".$arquivo["nome"], Zend_Log::INFO);
        Zend_Registry::get('logger')->log($arquivo, Zend_Log::INFO);
        $this->delete('id_arquivo =' . (int) $id);
        unlink("upload/".$arquivo["nome"]);
    }
}

