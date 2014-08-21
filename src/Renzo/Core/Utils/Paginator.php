<?php 
namespace RZ\Renzo\Core\Utils;

use Doctrine\ORM\EntityManager;

/**
 *  A simple paginator class to filter entities with limit and search				
 */
class Paginator 
{
	protected $itemsPerPage;
	protected $itemCount;
	protected $entityName;
	protected $criteria;
	protected $searchPattern = null;
	protected $_em;
	
	/**
	 * 
	 * @param Doctrine\ORM\EntityManager  $_em Entity manager
	 * @param string  $entityName Full qualified entity classname
	 * @param integer $itemPerPages
	 */
	function __construct( EntityManager $_em, $entityName, $itemPerPages = 20, array $criteria = array())
	{
		$this->_em = $_em;
		$this->entityName = $entityName;
		$this->setItemsPerPage( $itemPerPages );
		$this->criteria = $criteria;

		if ($this->entityName == "") {
			throw new \RuntimeException("Entity name could not be empty", 1);
		}
		if ($this->itemsPerPage < 1) {
			throw new \RuntimeException("Items par page could not be lesser than 1.", 1);
		}
	}


	/**
	 * @return string
	 */
	public function getSearchPattern() {
	    return $this->searchPattern;
	}
	
	/**
	 * @param string $newsearchPattern
	 */
	public function setSearchPattern($searchPattern) {
	    $this->searchPattern = $searchPattern;
	    return $this;
	}

	/**
	 * Return page count according to criteria.
	 * 
	 * **Warning** : EntityRepository must implements *countBy* method
	 * 
	 * @return integer
	 */
	public function getPageCount()
	{	
		if ($this->searchPattern !== null ) {
			$total = $this->_em->getRepository($this->entityName)->countSearchBy($this->searchPattern, $this->criteria);
		}
		else{
			$total = $this->_em->getRepository($this->entityName)->countBy($this->criteria);
		}
		return ceil($total / $this->getItemsPerPage());
	}

	/**
	 * Return entities filtered for current page.
	 * 
	 * @param  array   $order [description]
	 * @param  integer $page  [description]
	 * @return Doctrine\Common\Collections\ArrayCollection 
	 */
	public function findByAtPage( array $order = array(), $page = 1 )
	{
		if ( $this->searchPattern !== null ) {
			return $this->_em->getRepository($this->entityName)
						->searchBy($this->searchPattern, $this->criteria, $order, $this->getItemsPerPage(), $this->getItemsPerPage() * ($page - 1));
		}
		else{
			return $this->_em->getRepository($this->entityName)
						->findBy( $this->criteria, $order, $this->getItemsPerPage(), $this->getItemsPerPage() * ($page - 1));
		}
	}

	/**
	 * 
	 * @param integer $itemsPerPage 
	 */
	public function setItemsPerPage( $itemsPerPage )
	{
		$this->itemsPerPage = $itemsPerPage;
		return $this;
	}
	/**
	 * 
	 * @return integer $itemsPerPage 
	 */
	public function getItemsPerPage()
	{
		return $this->itemsPerPage;
	}

}