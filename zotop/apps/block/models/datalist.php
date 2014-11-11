<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * block_model
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class block_model_datalist extends model
{
	protected $pk = 'id';
	protected $table = 'block_datalist';

	public $statuses = array();


	public function __construct()
	{
		parent::__construct();

		$this->statuses = array
		(
			'publish'	=> t('发布'),
			'pending'	=> t('待审'),
			'reject'	=> t('退稿'),
			'draft'		=> t('草稿'),
			'history'	=> t('历史'),
			'trash'		=> t('回收'),
		);
	}

    /**
     * 根据区块获取区块的全部数据
     * 
     * @param  int $blockid 区块编号
     * @return array        返回数据
     */
	public function getAll($blockid)
	{
		return $this->db()->where('blockid','=',$blockid)->orderby('listorder','desc')->getAll();
	}

    /**
     * 根据编号
     * 
     * @param  int $blockid 区块编号
     * @return array        返回数据
     */
	public function getList($blockid, $status='publish')
	{
		$block = m('block.block.get',$blockid);

		$db = $this->db()->where('blockid','=',$blockid)->where('status','=',$status)->orderby('listorder','desc');

		if ( $block['rows'] > 0  )
		{
			$db->limit($block['rows']);
		}

		return $db->getAll();		
	}


    /**
     * 获取
     *
     */
	public function get($id)
	{
		return $this->getbyid($id);
	}

    /**
     * 插入
     *
     */
	public function add($data)
	{
		if ( empty($data['blockid']) ) return $this->error(t('区块编号不能为空'));
		if ( empty($data['title']) ) return $this->error(t('标题不能为空'));

		$data['createtime'] = ZOTOP_TIME ;
		$data['updatetime'] = ZOTOP_TIME ;
		$data['status'] 	= 'publish';
		$data['userid'] 	= zotop::user('id');
		$data['listorder'] 	= $this->max('listorder') + 1; // 默认排在后面

		if ( $id = $this->insert($data) )
		{
			$this->updatedata($data['blockid']);
			return $id;
		}

		return false;
	}

    /**
     * 编辑
     *
     */
	public function edit($data, $id)
	{
		if ( empty($data['blockid']) ) return $this->error(t('区块编号不能为空'));
		if ( empty($data['title']) ) return $this->error(t('标题不能为空'));

		if ( $this->update($data, $id) )
		{
			$this->updatedata($data['blockid']);
			return $id;
		}

		return false;
	}

	/**
	 * 根据传入的编号顺序排序
	 *
	 * @param string $id ID
	 * @return bool
	 */
	public function order($blockid, $ids)
	{
		foreach( (array)$ids as $i=>$id )
		{
			$this->update(array('listorder' => $i+1), $id);

		}

		$this->updatedata($blockid);
		return true;
	}

	/**
	 * 更新应用数据
	 *
	 * @param string $id ID
	 * @return bool
	 */
	public function updatedata($blockid)
	{
		$data = array();

		$datalist = $this->getList($blockid);

		foreach( $datalist as $list )
		{
			foreach( $list as $k=>$f )
			{
				if ( in_array($k, array('title','style','url','image','description','createtime','c1','c2','c3','c4','c5')) )
				{
					if ( $f ) $d[$k] = $f;
				}
			}

			$data[] = $d;
		}

		return m('block.block')->savedata($data,$blockid);
	}

}
?>