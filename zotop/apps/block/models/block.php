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
			//'hand'	=> t('手动'),
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

		return is_array($fields) ? array_merge($fieldlist, $fields) : $fieldlist;	
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
			$data['data'] 	= unserialize($data['data']);
			$data['data'] 	= is_array($data['data']) ? $data['data'] : array();

			$data['fields'] = unserialize($data['fields']);
		}

		return $field ? $data[$field] : $data;
	}

    /**
     * 插入
     *
     */
	public function add($data)
	{
		if ( empty($data['name']) ) return $this->error(t('区块名称不能为空'));

		$data['createtime'] = ZOTOP_TIME;
		$data['updatetime'] = ZOTOP_TIME;
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
		if ( empty($data['name']) ) return $this->error(t('区块名称不能为空'));

		$data['updatetime'] = ZOTOP_TIME ;

		if ( $this->update($data, $id) )
		{
			$this->clearcache($id);

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
			$this->clearcache($id);

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
		return file::delete(BLOCK_PATH_CACHE.DS."{$id}.html");	
	}


	/**
	 * 根据区块的编号发布区块
	 *
	 * @param string $id 区块编号
	 * @param object $tpl 模板对象
	 * @return bool
	 */
	public function publish($id, $tpl, $attrs=array())
	{	
		$block = $this->get($id);

		// 自动创建区块
		if ( empty($block) and is_array($attrs) )
		{
			$block = array_merge(array('type'=>'list','name'=>t('自动创建'),'userid'=>0), $attrs);
			$block['data'] = in_array($block['type'],array('list','hand')) ? array() : '';
			$block['template'] = empty($block['template']) ? "block/{$block['type']}.php" : $block['template']; 

			$this->add($block);
		}

		if ( is_array($block['data']) )
		{
			foreach($block['data'] as &$d)
			{
				$d['url'] = U($d['url']);
			}
		}

		$content = $tpl->assign($block)->render($block['template']);	

		file::put(BLOCK_PATH_CACHE.DS."{$id}.html", $content);

		return $content;		

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
				return $this->clearcache($id);
			}
		}

		return $this->error(t('区块 %s 不存在', $id));
	}
}
?>