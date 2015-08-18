<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * member_field
 *
 * @package		member
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class member_model_field extends model
{
	protected $pk = 'id';
	protected $table = 'user_field';

	public $controls;

	public function __construct()
	{
        parent::__construct();

        // 控件类型
		$this->controls = zotop::filter('field.controls',array(
            'text'		=> array('name'=>t('单行文本') ,'type'=>'varchar', 'length'=>'255'),
			'textarea'	=> array('name'=>t('多行文本') ,'type'=>'text'),
			'number'	=> array('name'=>t('数字'),'type'=>'int', 'length'=>'10'),
			'radio'		=> array('name'=>t('单选'),'type'=>'varchar', 'length'=>'50', 'settings' => A('member.path').DS.'templates'.DS.'settings'.DS.'radio_checkbox_select.php'),
			'checkbox'	=> array('name'=>t('多选'),'type'=>'varchar', 'length'=>'50', 'settings' => A('member.path').DS.'templates'.DS.'settings'.DS.'radio_checkbox_select.php'),
			'select'	=> array('name'=>t('下拉选择'),'type'=>'varchar', 'length'=>'50', 'settings' => A('member.path').DS.'templates'.DS.'settings'.DS.'radio_checkbox_select.php'),
			'editor'	=> array('name'=>t('编辑器'),'type'=>'text'),
			'email'		=> array('name'=>t('电子邮件'),'type'=>'varchar', 'length'=>'100'),
			'url'		=> array('name'=>t('网址'),'type'=>'varchar', 'length'=>'100'),
			'datetime'	=> array('name'=>t('日期时间'),'type'=>'int', 'length'=>'10'),
			'image'		=> array('name'=>t('图片'),'type'=>'varchar', 'length'=>'100'),
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


	/*
	 *  获取数据集
	 */
	public function select()
	{
		$data = array();

		$rows = $this->db()->orderby('listorder','asc')->select();

		foreach( $rows as &$r )
		{
			$r['settings'] = $r['settings'] ? unserialize($r['settings']) : array();

			$data[$r['id']] = $r;
		}

		return $data;
	}

	/*
	 *  获取添加编辑时的表单字段
	 */
	public function getfields($modelid, $data=array())
	{
		$fields = array();

		if ( $dataset = $this->cache() )
		{
			foreach( $dataset as $i=>$r )
			{
				if ( $r['disabled'] or $r['modelid'] != $modelid ) continue;

				$fields[$i]['label']	= $r['label'];
				$fields[$i]['for']		= $r['name'];
				$fields[$i]['required']	= $r['notnull'];

				$fields[$i]['field']['id']			= $r['name'];
				$fields[$i]['field']['name']		= $r['name'];
				$fields[$i]['field']['value']		= isset($data[$r['name']]) ? $data[$r['name']]  : $r['default'];
				$fields[$i]['field']['type']		= $r['control'];
				$fields[$i]['field']['required']	= $r['notnull'];

				// 将setting中的属性合并到字段
				if ( is_array($r['settings']) )
				{
					foreach( $r['settings'] as $key=>$val )
					{
						if ( $key[0] != '_' and $val )
						{
							$fields[$i]['field'][$key] = $val;
						}
					}
				}

				$fields[$i]['tips']	= $r['tips'];
				$fields[$i]['base']	= $r['base'];
			}
		}

		return $fields;
	}

	/*
	 *  获取数据
	 */
	public function get($id, $field='')
	{
		$data = $this->cache();

		if ( isset($data[$id])  )
		{
			return $data[$id];
		}

		return array();
	}

	/*
	 *  添加数据
	 */
	public function add($data)
	{
		$data['listorder'] = $this->max('id') + 1;

		if ( $data = $this->checkdata($data) )
		{
			$table = $this->db->schema($data['tablename']);

			// 检查字段名称是否已经存在
			if ( $table->existsField($data['name']) )
			{
				return $this->error(t('字段名 %s 已经存在', $data['name']));
			}

			if ( $table->addField($data['name'], $this->fielddata($data)) and ( $id = $this->insert($data) ) )
			{
				// 更新数据表字段缓存
				zotop::cache("{$data['tablename']}.fields",null);

				// 更新字段缓存
				$this->cache(true);
				return $id;
			}
		}

		return false;
	}

	/*
	 *  编辑数据
	 */
	public function edit($data,$id)
	{

		if ( $data = $this->checkdata($data) )
		{
			$table = $this->db->schema($data['tablename']);

			$name = $data['_name'] ? $data['_name'] : $data['name']; //改变字段名称

			// 更名的时候检查字段名称是否已经存在
			if ( $name != $data['name'] and $table->existsField($data['name']) )
			{
				return $this->error(t('字段名 %s 已经存在', $data['name']));
			}

			// 更该字段
			if ( $table->changeField($name, $this->fielddata($data)) and $this->update($data,$id) )
			{
				// 更新数据表字段缓存
				zotop::cache("{$data['tablename']}.fields",null);

				// 更新字段缓存
				$this->cache(true);
				return $id;
			}
		}

		return false;
	}

	/*
	 *  删除数据
	 */
	public function delete($id)
	{
		if ( $data = $this->get($id) )
		{
			if ( empty($data['tablename']) )
			{
				$data['tablename'] = m('member.model')->where('id',$data['modelid'])->getField('tablename');
			}

			if ( $this->db->schema($data['tablename'])->dropField($data['name']) and parent::delete($id) )
			{
				// 更新数据表字段缓存
				zotop::cache("{$data['tablename']}.fields",null);

				// 更新字段缓存
				$this->cache(true);
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

		$this->cache(true);
		return true;
	}

	/**
	 * 缓存数据
	 *
	 * @param bool $refresh 是否强制刷新缓存
	 * @return bool
	 */
	public function cache($refresh=false)
	{
		$cache = zotop::cache("member.field");

		if ( $refresh or empty($cache) or !is_array($cache) )
		{
			// 更新缓存
			$cache = $this->select();

			zotop::cache("member.field", $cache, false);
		}

		return $cache;
	}

	/**
	 * 检查并完善添加编辑时传入的数据
	 *
	 */
	private function checkdata($data)
	{
		if ( empty($data['name']) ) return $this->error(t('字段名不能为空'));
		if ( empty($data['label']) ) return $this->error(t('标签名不能为空'));
		if ( empty($data['modelid']) ) return $this->error(t('模型不能为空'));
		if ( empty($data['type']) ) return $this->error(t('数据类型不能为空'));

		if ( empty($data['length']) and in_array($data['type'], array('char','varchar','tinyint','smallint','mediumint','int')) )
		{
			return $this->error(t('数据字段长度不能为空'));
		}

		if ( empty($data['tablename']) )
		{
			$data['tablename'] = m('member.model')->where('id',$data['modelid'])->getField('tablename');
		}

		return $data;
	}

	/**
	 * 从数据组中组合出数据表字段数组
	 *
	 */
	private function fielddata($data)
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
}
?>