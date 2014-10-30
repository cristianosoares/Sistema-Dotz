<?php

class Application_Model_DbTable_CasaNoturna extends Zend_Db_Table_Abstract
{

    protected $_name = 'casa_noturna';
    protected $_primary = 'id';
	protected $_dependentTables = array("Application_Model_DbTable_Evento");

	public function getCasaNoturna ($id)
    {
        $id = (int) $id;
        $row = $this->fetchRow('id = ' . $id);
        if (! $row) {
            throw new Exception("Não foi possível encontrar linha $id");
        }
        return $row->toArray();
    }
    public function addCasaNoturna ($nome, $regiao,$cidade,$interior_capital)
    {
        $data = array('nome' => $nome, 'regiao' => $regiao,'cidade' => $cidade,'interior_capital' =>$interior_capital);
        $this->insert($data);
    }
    public function updateCasaNoturna ($id,$nome, $regiao,$cidade,$interior_capital)
    {
     	 $data = array('nome' => $nome, 'regiao' => $regiao,'cidade' => $cidade,'interior_capital' =>$interior_capital);
        $this->update($data, 'id = ' . (int) $id);
    
    }
    public function deleteCasaNoturna ($id)
    {
        $this->delete('id =' . (int) $id);
    }
    public function getCasasNoturnas ()
    {
       $casasNoturnas = new Application_Model_DbTable_CasaNoturna();
       return $casasNoturnas->getAdapter()->fetchPairs( $casasNoturnas->select()->from( 'casa_noturna', array('id', 'nome') )->order('nome'));
    }
}
    