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

		return arr::hashmap($db->getall(), 'id');		
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
     * 根据ID删除数据
     *
     * @param string $id ID
     * @return bool
     */
	public function delete($id)
	{
		$data = $this->get($id);

		if ( parent::delete($id) )
		{
			$this->updatedata($data['blockid']);
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
     * 重新推荐信息
     *
     * @param string $id ID
     * @return bool
     */
    public function back($id)
    {
        if ( $data = $this->get($id) )
        {
			$data['updatetime'] = ZOTOP_TIME ;
			$data['status']     = 'publish';
			$data['listorder']  = $this->where('blockid',$data['blockid'])->max('listorder') + 1;

			if ( $this->update($data, $id) )
			{
				$this->updatedata($data['blockid']);

				return true;
			}
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
	 * 将数据保存至更新数据主表
	 *
	 * @param string $id ID
	 * @return bool
	 */
	public function updatedata($blockid)
	{
		if ( is_array($blockid) )
		{
			return array_map(array($this,'updatedata'), $blockid);
		}

		// 将超出限制条目的已发布数据设置为历史状态
		if ( $list = $this->getlist($blockid) )
		{
			$this->where('status','publish')->where('blockid',$blockid)->where('id','not in', array_keys($list))->set('status','history')->update();
		}
		
		return m('block.block')->savedata($this->getdata($blockid), $blockid);
	}

	/**
	 * 将数据推送到区块
	 * 
	 * @param  array|string $new 新的区块编号数据
	 * @param  array $data  推送的数据
	 * @param  bool $sync  同步更新已经存在的数据
	 * @return bool 推送结果
	 */
	public function setcommend($new, $data, $sync=true)
	{
		if ( empty($data) or !is_array($data) or empty($data['dataid']) ) return false;


		if ( is_string($new) && preg_match( "#^[\\d\\,]+\$#", $new ) )
		{
			$new = explode(',', $new);
		}

		$new 	= is_array($new) ? $new : array();
		$old 	= arr::column($this->select('blockid')->where('dataid',$data['dataid'])->getall(), 'blockid'); 
		$del 	= array_diff($old, $new);
		$add 	= array_diff($new, $old);			
		$edit 	= array_diff($old, $del);	
		
		// 删除数据
		if ( $del )
		{
			$this->db()->where('dataid',$data['dataid'])->where('blockid','in', $del)->delete();	
			$this->updatedata($del);			
		}

		// 更新数据
		if ( $edit and $sync)
		{
			foreach ($edit as $blockid)
			{
				$this->where('dataid',$data['dataid'])->where('blockid',$blockid)->update($data);						
			}

			$this->updatedata($edit);	
		}

		// 新增数据
		if ( $add )
		{
			foreach ($add as $blockid)
			{
				$data['blockid'] 	= $blockid;
				$data['status']     = 'publish';
				$data['userid']     = zotop::user('id');
				$data['listorder']  = $this->where('blockid',$data['blockid'])->max('listorder') + 1;				

				$this->insert($data);						
			}

			$this->updatedata($add);	
		}			

		return true;
	}

	/**
	 * 删除推荐数据
	 * 
	 * @param  array||string $dataid 根据数据编号删除推荐的内容
	 * @return bool
	 */
	public function delcommend($dataid)
	{
		if ( is_array($dataid) )
		{
			return array_map(array($this,'delcommend'), $dataid);
		}

		$datalist = $this->select('id,blockid')->where('dataid',$dataid)->getall();

		foreach ($datalist as $data)
		{
			if ( parent::delete($data['id']) )
			{
				$this->updatedata($data['blockid']);
			}
		}

		return true;
	}

}
?>