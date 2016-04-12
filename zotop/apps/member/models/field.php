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
	protected $pk    = 'id';
	protected $table = 'user_field';

	public $controls;
	public $system_fields;

	public function __construct()
	{
        parent::__construct();

        // 控件类型
		$this->controls = zotop::filter('field.controls',array(
            'text'		=> array('name'=>t('单行文本') ,'type'=>'varchar', 'length'=>'255'),
			'textarea'	=> array('name'=>t('多行文本') ,'type'=>'text'),
			'number'	=> array('name'=>t('数字'),'type'=>'int', 'length'=>'10'),
			'radio'		=> array('name'=>t('单选'),'type'=>'varchar', 'length'=>'50'),
			'checkbox'	=> array('name'=>t('多选'),'type'=>'varchar', 'length'=>'50'),
			'select'	=> array('name'=>t('下拉选择'),'type'=>'varchar', 'length'=>'50'),
			'editor'	=> array('name'=>t('编辑器'),'type'=>'text'),
			'email'		=> array('name'=>t('电子邮件'),'type'=>'varchar', 'length'=>'100'),
			'url'		=> array('name'=>t('网址'),'type'=>'varchar', 'length'=>'100'),
			'date'		=> array('name'=>t('日期'),'type'=>'int', 'length'=>'10'),
			'datetime'	=> array('name'=>t('日期+时间'),'type'=>'int', 'length'=>'10'),
			'image'		=> array('name'=>t('图片'),'type'=>'varchar', 'length'=>'100'),
        ));

		// 系统字段
        $this->system_fields = zotop::filter('member.field.system',array(
			'username'         => array('control'=>'username','label'=>t('用户名'),'name'=>'username','type'=>'varchar','length'=>'32','notnull'=>'1','settings'=>array('minlength'=>'2','maxlength'=>'32'),'base'=>'1','tips'=>t('4-20位字符，允许中文、英文、数字和下划线，不能含有特殊字符')),
			'password'         => array('control'=>'password','label'=>t('密码'),'name'=>'password','type'=>'varchar','length'=>'32','notnull'=>'1','settings'=>array('minlength'=>'6','maxlength'=>'32'),'base'=>'1','tips'=>t('6-20位字符，可使用英文、数字或者符号组合，不建议使用纯数字、纯字母或者纯符号')),
			'email'            => array('control'=>'email','label'=>t('邮箱'),'name'=>'email','type'=>'varchar','length'=>'50','notnull'=>'1','settings'=>array('maxlength'=>'32'),'base'=>'1'),
			'mobile'           => array('control'=>'mobile','label'=>t('手机'),'name'=>'mobile','type'=>'varchar','length'=>'13','notnull'=>'1','settings'=>array('maxlength'=>'13'),'base'=>'1'),
			'nickname'         => array('control'=>'nickname','label'=>t('昵称'),'name'=>'nickname','type'=>'varchar','length'=>'32','notnull'=>'0','settings'=>array('maxlength'=>'32'),'base'=>'0'),
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
	public function select($modelid)
	{
		$data = array();

		$rows = $this->db()->where('modelid',$modelid)->orderby('listorder','asc')->select();

		foreach( $rows as &$r )
		{
			$r['settings'] = $r['settings'] ? unserialize($r['settings']) : array();

			$data[$r['name']] = $r;
		}

		return $data;
	}

	/**
	 * 获取添加编辑时候的表单字段
	 * 
	 * @param  string $modelid 模型编号
	 * @param  array  $data    字段数据
	 * @return mixed
	 */
	public function getfields($modelid, $data=array())
	{
		$fields = array();

		if ( $dataset = $this->cache($modelid) )
		{
			foreach( $dataset as $i=>$r )
			{
				if ( $r['disabled'] ) continue;

				$fields[$i]['label']             = $r['label'];
				$fields[$i]['for']               = $r['name'];
				$fields[$i]['required']          = $r['notnull'];
				$fields[$i]['tips']              = $r['tips'];
				$fields[$i]['base']              = $r['base'];
				$fields[$i]['system']            = $r['system'];				
				
				$fields[$i]['field']['id']       = $r['name'];
				$fields[$i]['field']['name']     = $r['name'];
				$fields[$i]['field']['value']    = isset($data[$r['name']]) ? $data[$r['name']]  : $r['default'];
				$fields[$i]['field']['type']     = $r['control'];
				$fields[$i]['field']['required'] = $r['notnull'];

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
			}
		}

		return zotop::filter('member.field.getfields',$fields);
	}

	/*
	 *  获取数据
	 */
	public function get($id, $field='', $default=null)
	{
		$data = $this->getbyid($id);
		$data['settings'] = $data['settings'] ? unserialize($data['settings']) : array();

		if ( $field )
		{
			return isset($data[$field]) ? $data[$field] : $default;
		}

		return $data;
	}

	/*
	 *  添加数据
	 */
	public function add($data)
	{
		$data['listorder'] = $this->max('id') + 1;

		if ( $data = $this->checkdata($data) )
		{
			// 模型扩展表名称
			$tablename 	= $data['tablename'];

			// 检查数据表是否存在
			if ( !$this->db->existsTable($tablename) )
			{
				$schema = array(
					'fields'  =>array(
						'id'      => array ( 'type'=>'mediumint', 'length'=>8, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('用户编号') ),
					),
					'index'   =>array(),
					'unique'  =>array(),
					'primary' =>array ( 'id' ),
					'comment' => t('%s会员扩展信息', $data['name'])
				);

				if ( !$this->db->createTable($tablename, $schema) )
				{
					return $this->error(t('创建扩展数据表 $1 失败', $tablename));
				}
			}			

			// 检查字段名称是否已经存在
			if ( $this->db->existsField($tablename, $data['name']) )
			{
				return $this->error(t('字段名 %s 已经存在', $data['name']));
			}

			if ( $this->db->addField($tablename, $this->fielddata($data)) and ( $id = $this->insert($data) ) )
			{
				// 更新数据表字段缓存
				zotop::cache("{$tablename}.fields",null);

				// 更新字段缓存
				$this->cache($data['modelid'],true);
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
			$name = $data['_name'] ? $data['_name'] : $data['name']; //改变字段名称

			// 更名的时候检查字段名称是否已经存在
			if ( $name != $data['name'] and $this->db->existsField($data['tablename'], $data['name']) )
			{
				return $this->error(t('字段名 %s 已经存在', $data['name']));
			}

			// 更该字段
			if ( $this->db->changeField($data['tablename'], $name, $this->fielddata($data)) and $this->update($data,$id) )
			{
				// 更新数据表字段缓存
				zotop::cache("{$data['tablename']}.fields",null);

				// 更新字段缓存
				$this->cache($data['modelid'],true);
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

			if ( $this->db->dropField($data['tablename'],$data['name']) and parent::delete($id) )
			{
				// 更新数据表字段缓存
				zotop::cache("{$data['tablename']}.fields",null);

				// 更新字段缓存
				$this->cache($data['modelid'],true);
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

		$this->cache($modelid, true);
		return true;
	}

	/**
	 * 缓存数据
	 *
	 * @param bool $refresh 是否强制刷新缓存
	 * @return bool
	 */
	public function cache($modelid, $refresh=false)
	{
		$cache = zotop::cache("member.field.{$modelid}");

		if ( $refresh or empty($cache) or !is_array($cache) )
		{
			$cache = $this->select($modelid);

			zotop::cache("member.field.{$modelid}", $cache, false);
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