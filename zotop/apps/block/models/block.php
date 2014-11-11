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

	/**
	 * 区块类型，当类型参数不为空时，返回类型名称
	 * 
	 * @param  string $type [description]
	 * @return [type]       [description]
	 */
	public function types($type='')
	{
		$types = array(
			'list'	=> t('列表'),
			'hand'	=> t('手动'),
			'html'	=> t('内容'),
			'text'	=> t('文本'),
		);

		return $type ? $types[$type] : $types;
	}



	/**
	 *	列表中可以使用的字段 
	 *
	 * @param  array $fields 数据库中存储的字段集合
	 * @return array 返回当前字段结合
	 */
	public function fieldlist($fields=array())
	{
		$fieldlist = array(
			'title'			=> array('show'=>1,'label'=>t('标题'),'type'=>'title','name'=>'title','minlength'=>1,'maxlength'=>50, 'required'=>'required'),
			'url'			=> array('show'=>1,'label'=>t('链接'),'type'=>'text','name'=>'url', 'required'=>'required'),
			'image'			=> array('show'=>1,'label'=>t('图片'),'type'=>'image','name'=>'image', 'required'=>'required','image_resize'=>1,'image_width'=>'','image_height'=>'', 'watermark'=>0),
			'description'	=> array('show'=>1,'label'=>t('摘要'),'type'=>'textarea','name'=>'description', 'required'=>'required','minlength'=>0,'maxlength'=>255),
			'createtime'	=> array('show'=>1,'label'=>t('日期'),'type'=>'datetime','name'=>'createtime', 'required'=>'required'),
			'c1'			=> array('show'=>0,'label'=>t('自定义1'),'type'=>'text','name'=>'c1'),
			'c2'			=> array('show'=>0,'label'=>t('自定义2'),'type'=>'text','name'=>'c2'),
			'c3'			=> array('show'=>0,'label'=>t('自定义3'),'type'=>'text','name'=>'c3'),
			'c4'			=> array('show'=>0,'label'=>t('自定义4'),'type'=>'text','name'=>'c4'),
			'c5'			=> array('show'=>0,'label'=>t('自定义5'),'type'=>'text','name'=>'c5'),
		);

		return array_merge($fieldlist, $fields);	
	}

	/**
	 * 列表允许选择的字段类型，当类型参数不为空时，返回类型名称
	 * 
	 * @param  string $type 类型
	 * @return mixed
	 */
	public function fieldtypes($type='')
	{
		$fieldtypes = zotop::filter('block.fieldtypes',array(
			'text'		=>	t('单行文本'),
			'textarea'	=>	t('多行文本'),
			'number'	=>	t('数字'),
			'url'		=>	t('文本'),
			'image'		=>	t('图像'),
			'file'		=>	t('文件'),
			'date'		=>	t('日期'),
			'datetime'	=>	t('日期时间'),
			'editor'	=>	t('编辑器'),
		));

		return $type ? $fieldtypes[$type] : $fieldtypes;
	}	

    /**
     * 获取
     *
     */
	public function get($id, $field='')
	{
		$data = $this->getbyid($id);

		if ( in_array($data['type'], array('list','hand')) )
		{
			$data['data'] = unserialize($data['data']);
			$data['fields'] = unserialize($data['fields']);
		}

		return $data;
	}

    /**
     * 插入
     *
     */
	public function add($data)
	{
		if ( empty($data['uid']) ) return $this->error(t('区块标识不能为空'));		
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
		if ( empty($data['uid']) ) return $this->error(t('区块标识不能为空'));			
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