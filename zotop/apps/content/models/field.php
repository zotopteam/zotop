<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * form_model_field
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class content_model_field extends model
{
	protected $pk 		= 'id';
	protected $table 	= 'content_field';

	public $controls;
	public $system_fields = array();

	public function __construct()
	{
        parent::__construct();

        // 控件类型
		$this->controls = zotop::filter('content.field.controls',array(
            'text'		=> array('name'=>t('单行文本') ,'type'=>'varchar', 'length'=>'255'),
			'textarea'	=> array('name'=>t('多行文本') ,'type'=>'text'),
			'number'	=> array('name'=>t('数字'),'type'=>'int', 'length'=>'10'),
			'radio'		=> array('name'=>t('单选'),'type'=>'varchar', 'length'=>'50'),
			'checkbox'	=> array('name'=>t('多选'),'type'=>'varchar', 'length'=>'255'),
			'select'	=> array('name'=>t('下拉选择'),'type'=>'varchar', 'length'=>'50'),
			'editor'	=> array('name'=>t('编辑器'),'type'=>'text'),
			'image'		=> array('name'=>t('图片'),'type'=>'varchar', 'length'=>'100'),
			'images'	=> array('name'=>t('图集'),'type'=>'text'),
			'file'		=> array('name'=>t('文件'),'type'=>'varchar', 'length'=>'100'),
			'files'		=> array('name'=>t('文件集'),'type'=>'text'),
			'date'		=> array('name'=>t('日期'),'type'=>'int', 'length'=>'10'),
			'datetime'	=> array('name'=>t('日期+时间'),'type'=>'int', 'length'=>'10'),
			'email'		=> array('name'=>t('电子邮件'),'type'=>'varchar', 'length'=>'100'),
			'url'		=> array('name'=>t('网址'),'type'=>'varchar', 'length'=>'100'),							
        ));
		
		// 系统字段
        $this->system_fields = zotop::filter('content.field.system',array(
			array('control'=>'title','label'=>t('标题'),'name'=>'title','type'=>'varchar','length'=>'100','notnull'=>'1','settings'=>array('minlength'=>'0','maxlength'=>'100'),'base'=>'1','post'=>'1','search'=>'1'),
			array('control'=>'image','label'=>t('缩略图'),'name'=>'image','type'=>'varchar','length'=>'100','notnull'=>'0','settings'=>array('watermark'=>'0','image_resize'=>'1'),'base'=>'1','post'=>'1','search'=>'0'),
			array('control'=>'keywords','label'=>t('关键词'),'name'=>'keywords','type'=>'varchar','length'=>'100','notnull'=>'0','settings'=>array('data-source'=>'title,content'),'base'=>'1','post'=>'1','search'=>'1'),
			array('control'=>'summary','label'=>t('摘要'),'name'=>'summary','type'=>'varchar','length'=>'1000','notnull'=>'0','base'=>'1','post'=>'1','search'=>'1'),
			array('control'=>'url','label'=>t('链接'),'name'=>'url','type'=>'varchar','length'=>'100','notnull'=>'0','base'=>'1','post'=>'0','search'=>'0'),
			array('control'=>'blockcommend','label'=>t('推荐到区块'),'name'=>'blockids','type'=>'varchar','length'=>'128','notnull'=>'0','base'=>'1','post'=>'0','search'=>'0'),
			array('control'=>'bool','label'=>t('评论'),'name'=>'comment','type'=>'tinyint','length'=>'1','default'=>'1','notnull'=>'0','base'=>'0','post'=>'0','search'=>'0'),
			array('control'=>'alias','label'=>t('URL别名'),'name'=>'alias','type'=>'varchar','length'=>'128','notnull'=>'0','settings'=>array('data-source'=>'title'),'base'=>'1','post'=>'0','search'=>'0'),
			array('control'=>'template','label'=>t('内容页模板'),'name'=>'template','type'=>'varchar','length'=>'100','notnull'=>'0','base'=>'0','post'=>'0','search'=>'0'),
			array('control'=>'datetime','label'=>t('发布时间'),'name'=>'createtime','type'=>'varchar','length'=>'100','notnull'=>'0','base'=>'0','post'=>'0','search'=>'0'),
		));
	}

	/*
	 *  获取控件类型选项
	 */
	public function control_options()
	{
		$options = array();

		foreach( $this->controls as $key=>$val )
		{
			$options[$key] = $val['name'];
		}

		return $options;
	}

	

	/**
	 * 获取数据集，支持链式查询
	 *
	 * @return array
	 */
	public function getall($modelid)
	{
		$data = array();

		$rows = $this->db()->where('modelid',$modelid)->orderby('listorder','asc')->getAll();

		foreach( $rows as &$r )
		{
			$r['settings'] = $r['settings'] ? unserialize($r['settings']) : array();

			$data[$r['name']] = $r;
		}

		return $data;
	}

	/**
	 * 获取添加编辑时的表单字段并格式化
	 * 
	 * @param  int $modelid 表单编号
	 * @param  array  $data  表单数据
	 * @return array 格式化的表单
	 */
	public function getfields($modelid, $data=array())
	{
		
		$fields = array();

		if ( $_fields = $this->cache($modelid) )
		{
			foreach( $_fields as $i=>$r )
			{
				if ( $r['disabled'] ) continue;

				$fields[$i]['label']	= $r['label'];
				$fields[$i]['for']		= $r['name'];
				$fields[$i]['required']	= $r['notnull'];
				$fields[$i]['tips']		= $r['tips'];

				$fields[$i]['field']['id']			= $r['name'];
				$fields[$i]['field']['name']		= $r['name'];
				$fields[$i]['field']['value']		= isset($data[$r['name']]) ? $data[$r['name']]  : $r['default'];
				$fields[$i]['field']['type']		= $r['control'];
				$fields[$i]['field']['required']	= $r['notnull'];

				if ( $r['unique'] )
				{
					//$fields[$i]['field']['remote']	= U('content/content/check/'.$modelid.'/'.$fields[$i]['field']['name'].'/'.$fields[$i]['field']['value']);
				}

				// 将settings中的属性合并到字段
				foreach( $r['settings'] as $key=>$val )
				{
					if ( $key[0] == '_' or $val == '' ) continue;

					$fields[$i]['field'][$key] = $val;					
				}				

				// 增加上传数据编号
				if ( in_array($r['control'], array('editor','image','images','file','files')) and $data['dataid'] )
				{
					$fields[$i]['field']['dataid']	= $data['dataid'];
				}

				// 处理标题色彩
				if ( $r['control'] == 'title' and $data['style'] )
				{
					$fields[$i]['field']['style']	= $data['style'];
				}

			}
		}

		return zotop::filter('content.field.getfields', $fields, $modelid, $data);
	}

	/**
	 * 获取字段数组
	 *
	 * @param  int $id 字段编号
	 * @return mixed
	 */
	public function get($id, $field='')
	{
		$data = $this->getbyid($id);

		if ( is_array($data)  )
		{
			$data['settings'] = $data['settings'] ? unserialize($data['settings']) : array();

			if ( $field )
			{
				return isset($data[$field]) ? $data[$field] : '';
			}

			return $data;
		}

		return array();
	}	


	/**
	 * 检查并完善添加编辑时传入的数据
	 * 
	 * @param  array $data 数据
	 * @return array
	 */
	private function checkdata($data)
	{
		if ( empty($data['name']) ) return $this->error(t('字段名不能为空'));
		if ( empty($data['label']) ) return $this->error(t('标签名不能为空'));
		if ( empty($data['modelid']) ) return $this->error(t('模型编号不能为空'));
		if ( empty($data['type']) ) return $this->error(t('数据类型不能为空'));

		if ( in_array(strtolower($data['name']), m('content.content.fields')) or in_array(strtolower($data['name']), array('dataid')))
		{
			return $this->error(t('字段名 {1} 已经存在, 请重新输入', $data['name']));
		}

		if ( empty($data['length']) and in_array($data['type'], array('char','varchar','tinyint','smallint','mediumint','int')) )
		{
			return $this->error(t('数据字段长度不能为空'));
		}

		// 字段名全部小写
		$data['name'] = strtolower($data['name']);

		// 大数据字段不能设为值唯一
		$data['unique'] = in_array($data['type'], array('text','mediumtext')) ? 0 : $data['unique'];

		// 标记为自定义字段
		$data['system'] = 0;

		return $data;
	}

	/**
	 * 从数据组中组合出数据表字段数组
	 *
	 * @param  array $data 原始数据
	 * @return array
	 */
	public function fielddata($data)
	{
		$field = array(
			'name'		=> $data['name'],
			'type'		=> $data['type'],
			'length'	=> $data['length'],
			'default'	=> $data['default'],
			'notnull'	=> $data['notnull'],
			'unsigned'	=> $data['unsigned'],
			'comment'	=> $data['label']
		);

		return $field;
	}	

	/**
	 * 添加自定义字段
	 * 
	 * @param  array $data 字段设置
	 * @return mixed  操作结果或者字段编号
	 */
	public function add($data)
	{
		if ( $data = $this->checkdata($data) )
		{
			// 模型扩展表名称 content_model_[modelid]
			$tablename 	= "content_model_{$data['modelid']}";

			// 数据表对象
			$table 		= $this->db->schema($tablename);

			if ( !$table->exists() )
			{
				$schema = array(
					'fields'	=> array(
						'id' => array('type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('内容编号'))
					),
					'index'		=> array(),
					'unique'	=> array(),
					'primary'	=> array('id'),
					'comment' 	=> $data['name']
				);

				if ( !$table->create($schema) )
				{
					return $this->error(t('创建附加数据表 {1} 失败', $tablename));
				}
			}

			// 检查字段名称是否已经存在
			if ( $table->existsField($data['name']) )
			{
				return $this->error(t('字段名 {1} 已经存在, 请重新输入', $data['name']));
			}

			if ( empty($data['listorder']) )
			{
				$data['listorder'] = $this->max('id') + 1;
			}			

			if ( $table->addField($data['name'], $this->fielddata($data)) and ( $id = $this->insert($data) ) )
			{
				//更新模型类型
				if ( $this->where('modelid', $data['modelid'])->where('system',0)->count() > 0 )
				{
					m('content.model')->where('id',$data['modelid'])->data('model','extend')->update();
					m('content.model')->cache(true);
				}

				// 更新数据表字段缓存
				zotop::cache("{$tablename}.fields", null);
				
				// 更新字段缓存
				$this->cache($data['modelid'], true);

				return $id;
			}
		}

		return false;
	}

	/**
	 * 编辑
	 * 
	 * @param  array $data 数据
	 * @param  int $id   字段编号
	 * @return mixed  操作结果或者字段编号
	 */
	public function edit($data, $id)
	{
		// 系统字段
		if ( intval($data['system']) )
		{
			$this->update($data,$id);
			$this->cache($data['modelid'], true);
			return $id;
		}

		// 用户自定义字段
		if ( $data = $this->checkdata($data) )
		{
			$tablename 	= "content_model_{$data['modelid']}";

			$table 		= $this->db->schema($tablename);

			// 更名的时候检查字段名称是否已经存在
			if ( $data['_name'] != $data['name'] and $table->existsField($data['name']) )
			{
				return $this->error(t('字段名 {1} 已经存在, 请重新输入', $data['name']));
			}

			// 更该字段
			if ( $table->changeField($data['_name'], $this->fielddata($data)) and $this->update($data,$id) )
			{
				// 更新数据表字段缓存
				zotop::cache("{$tablename}.fields",null);

				// 更新字段缓存
				$this->cache($data['modelid'], true);				

				return $id;
			}
		}

		return false;
	}			

	/**
	 * 删除
	 * 
	 * @param  int $id   字段编号
	 * @return mixed  操作结果
	 */
	public function delete($id)
	{
		if ( $data = $this->get($id) )
		{
			if ( intval($data['system']) ) return $this->error(t('系统字段不能删除'));

			$tablename 	= "content_model_{$data['modelid']}";
			$table 		= $this->db->schema($tablename);

			if ( $table->dropField($data['name']) and parent::delete($id) )
			{
				//更新模型类型
				if ( $this->where('modelid', $data['modelid'])->where('system',0)->count() == 0 and $table->drop() )
				{				
					m('content.model')->where('id',$data['modelid'])->data('model','')->update();
					m('content.model')->cache(true);
				}

				// 更新数据表字段缓存
				zotop::cache("{$tablename}.fields",null);

				// 更新字段缓存
				$this->cache($data['modelid'], true);				

				return true;
			}
		}
		return false;
	}

	/**
	 * 排序
	 *
	 */
	public function order($ids)
	{
		foreach( (array)$ids as $i=>$id )
		{
			$this->update(array('listorder'=>$i+1), $id);
		}

		// 获取modelid
		$modelid = $this->get($id, 'modelid');

		// 更新字段缓存
		$this->cache($modelid, true);		

		return true;
	}

	/**
	 * 根据ID设置状态
	 *
	 * @param string $id ID
	 * @return bool
	 */
	public function status($id)
	{
		
		$modelid 	= $this->get($id, 'modelid');
		$disabled 	= $this->get($id, 'disabled') ? 0 : 1;

		if ( $this->update(array('disabled'=>$disabled), $id) )
		{
			// 更新字段缓存
			$this->cache($modelid, true);

			return true;
		}

		return false;
	}

	/**
	 * 缓存数据
	 *
	 * @param bool $refresh 是否强制刷新缓存
	 * @return bool
	 */
	public function cache($modelid, $refresh=false)
	{
		$cache = zotop::cache("content.field.{$modelid}");

		if ( $refresh or empty($cache) or !is_array($cache) )
		{
			$cache = $this->getall($modelid);

			zotop::cache("content.field.{$modelid}", $cache, false);
		}

		return $cache;
	}		
}
?>