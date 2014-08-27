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
class block_model_block extends model
{
	protected $pk = 'id';
	protected $table = 'block';

	public $types = array();

	public function __construct()
	{
		parent::__construct();

		$this->types = array
		(
			'list'	=> t('列表'),
			'hand'	=> t('手动'),
			'html'	=> t('内容'),
			'text'	=> t('文本'),
		);
	}

    /**
     * 获取
     *
     */
	public function get($id)
	{
		$data = $this->getbyid($id);
		$data['fields'] = unserialize($data['fields']);

		return $data;
	}

    /**
     * 插入
     *
     */
	public function add($data)
	{
		if ( empty($data['uid']) ) return $this->error(t('区块编号不能为空'));
		if ( empty($data['name']) ) return $this->error(t('区块名称不能为空'));

		$data['createtime'] =  ZOTOP_TIME ;
		$data['updatetime'] =  ZOTOP_TIME ;
		$data['userid'] 	= zotop::user('id');
		$data['listorder'] 	= $this->max('listorder') + 1; // 默认排在后面

		if ( $id = $this->insert($data) )
		{
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
		if ( empty($data['uid']) ) return $this->error(t('区块编号不能为空'));
		if ( empty($data['name']) ) return $this->error(t('区块名称不能为空'));

		$data['updatetime'] =  ZOTOP_TIME ;

		if ( $this->update($data, $id) )
		{
			$data['data'] and $this->clearcache($id);

			return $id;
		}

		return false;
	}

    /**
     * 更新数据
     *
     */
	public function savedata($data, $id)
	{
		if ( empty($data) ) return $this->error(t('内容不能为空'));

		if ( $this->update(array('data'=>$data,'updatetime'=>ZOTOP_TIME), $id) )
		{
			$data and $this->clearcache($id);

			return $id;
		}

		return false;
	}

	/**
	 * 清空缓存数据
	 *
	 * @param string $id ID
	 * @return bool
	 */
	public function clearcache($id='')
	{
		// 删除全部区块缓存
		if ( empty($id) ) 
		{
			return folder::clear(BLOCK_PATH_CACHE);
		}

		// 删除多个区块缓存
		if ( is_array($id) )
		{
			return array_map(array($this,'clearcache'), $id);
		}

		// 删除指定区块缓存
		if( $uid = $this->where('id',$id)->getField('uid') )
		{
			return file::delete(BLOCK_PATH_CACHE.DS."{$uid}.html");
		}

		return false;		
	}


	/**
	 * 根据区块的唯一编号发布区块
	 *
	 * @param string $id ID
	 * @return bool
	 */
	public function publish($uid, $tpl)
	{
		if( $uid and $block = $this->where('uid',$uid)->getRow() )
		{
			if ( empty($block['data']) )	return $this->error(t('数据不能为空'));
			
			if ( $block['type'] == 'list' or $block['type'] == 'hand' )
			{
				$block['data'] = json_decode($block['data'], true);
			}

			$template = $block['template']; unset($block['template']);

			$content = $tpl->assign($block)->render($template);	

			file::put(BLOCK_PATH_CACHE.DS."{$block['uid']}.html", $content);

			return $content;
		}

		return $this->error(t('区块 %s 不存在', $uid));
	}

	/**
     * 获取排序过的全部数据
     *
     */
	public function getAll()
	{
		return $this->db()->orderby('listorder','asc')->getAll();
	}

	/**
	 * 根据传入的编号顺序排序
	 *
	 * @param string $id ID
	 * @return bool
	 */
	public function order($ids)
	{
		foreach( (array)$ids as $i=>$id )
		{
			$this->update(array('listorder' => $i+1), $id);
		}

		return true;
	}

	/**
	 * 删除区块
	 *
	 * @param string $id ID
	 * @return bool
	 */
	public function delete($id)
	{
		if ( $block = $this->getbyid($id) )
		{
			if ( parent::delete($id) )
			{
				return $this->clearcache($block['uid']);
			}
		}

		return $this->error(t('区块 %s 不存在', $id));
	}
}
?>