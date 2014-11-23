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
     * 根据编号获取发布数据列表，用于区块维护显示
     * 
     * @param  int $blockid 区块编号
     * @return array        返回数据
     */
	public function getList($blockid)
	{
		$rows = m('block.block.get',$blockid,'rows');

		$db = $this->db()->where('blockid','=',$blockid)->where('status','=','publish')->orderby('stick','desc')->orderby('listorder','desc');

		if ( $rows > 0  )
		{
			$db->limit($rows);
		}

		$list = $db->getall();

		return arr::hashmap($list, 'id');		
	}

	/**
	 * 根据区块编号获取区块数据
	 * 
	 * @param  [type] $blockid [description]
	 * @return [type]          [description]
	 */
	public function getdata($blockid)
	{
		$data 		= array();

		foreach( $this->getList($blockid) as $list )
		{
			$d = array();

			foreach( $list as $k=>$f )
			{
				if ( in_array($k, array('title','style','url','image','description','time','c1','c2','c3','c4','c5')) )
				{
					if ( $f ) $d[$k] = $f;
				}
			}

			$data[] = $d;

		}

		return $data;
	}

    /**
     * 插入
     *
     */
	public function add($data)
	{
		if ( empty($data['blockid']) ) return $this->error(t('区块编号不能为空'));
		if ( empty($data['title']) ) return $this->error(t('标题不能为空'));

		$data['time']       = strtotime($data['time']) ;

		$data['updatetime'] = ZOTOP_TIME ;
		$data['status']     = $data['status'] ? $data['status'] : 'publish';
		$data['userid']     = zotop::user('id');
		$data['listorder']  = $this->where('blockid',$data['blockid'])->max('listorder') + 1;

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
		
		$data['time']       = strtotime($data['time']) ;

		$data['updatetime'] = ZOTOP_TIME ;
		
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
		
		if ( is_array($ids) )
		{
			$ids = array_reverse($ids);

			foreach( $ids as $i=>$id )
			{
				$this->update(array('listorder' => $i+1), $id);

			}

			$this->updatedata($blockid);
			return true;

		}

		return false;
	}

    /**
     * 根据ID自动判断并设置置顶状态
     *
     * @param string $id ID
     * @return bool
     */
    public function stick($id)
    {
        if ( $data = $this->get($id) )
        {
            $stick = $data['stick'] ? 0 : 1;

			if ( $this->update(array('stick'=>$stick),$id) )
			{
				$this->updatedata($data['blockid']);

				return true;
			}
        }

        return false;
    }	

	/**
	 * 更新应用数据
	 *
	 * @param string $id ID
	 * @return bool
	 */
	public function updatedata($blockid)
	{
		$data = $this->getdata($blockid);
		$list = $this->getlist($blockid);

		// 将数据保存至更新数据主表
		m('block.block')->savedata($data, $blockid);

		// 将超出限制条目的已发布数据设置为历史状态
		$this->where('status','publish')->where('id','not in', array_keys($list))->set('status','history')->update();

		return true;
	}

}
?>