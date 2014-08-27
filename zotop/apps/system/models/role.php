<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 角色模型, 对应的数据表为用户组
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_model_role extends model
{
	protected $pk = 'id';
	protected $table = 'user_group';

    /**
     * 获取数据
     *
     */
	public function getAll()
	{
		$dataset = array();

		$results = $this->db()->where('modelid','admin')->orderby('listorder','asc')->getall();

		foreach( $results as $r )
		{
			$dataset[$r['id']] = $r;
		}

		return $dataset;
	}

    /**
     * 获取选项，用于select、checkbox、radio
     *
     */
	public function getOptions($description=true)
	{
		$dataset = array();

		$results = $this->getall();

		foreach( $results as $r )
		{
			$dataset[$r['id']] = $r['name'].($description ? '<span class="description">'.$r['description'].'</span>' : '');
		}

		return $dataset;
	}

    /**
     * 添加
     *
     */
	public function add($data)
	{

		if ( strlen($data['name']) < 1 or strlen($data['name']) > 32 )
		{
			return $this->error(t('角色名称长度必须在1-32位之间'));
		}

		$data['id'] = $this->max('id') + 1;
		$data['listorder'] = $data['id'];

		// 强制用户组类型为admin
		$data['modelid'] = 'admin';

		return $this->insert($data,true);
	}


    /**
     * 修改
     *
     */
	public function edit($data, $id)
	{

		if ( strlen($data['name']) < 1 or strlen($data['name']) > 32 )
		{
			return $this->error(t('角色名称长度必须在1-32位之间'));
		}

		return $this->update($data, $id);
	}


    /**
     * 删除
     *
     */
	public function delete($id)
	{
		if ( $id == 1 ) return $this->error(t('超级管理员不能被删除'));

		if ( m('system.user')->where('groupid',$id)->count() ) return $this->error(t('该角色下尚有管理员，不能被删除'));

		return parent::delete($id);
	}
}
?>