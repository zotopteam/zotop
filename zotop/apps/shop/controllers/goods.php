<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 商城 后台控制器
*
* @package		shop
* @version		1.0
* @author		zotop team
* @copyright	zotop team
* @license		http://www.zotop.com
*/
class shop_controller_goods extends admin_controller
{
	

	/**
	 * 重载__init函数
	 */
	public function __init()
	{
		parent::__init();

		$this->goods = m('shop.goods');
	}

	/**
	 * index 动作
	 *
	 */
	public function action_index($categoryid=0, $status='publish')
    {
    	// 搜索: 标题和关键词
		if ( $keywords = $_REQUEST['keywords'] )
		{
			$dataset = $this->goods->where(array(	array('name','like',$keywords), 'or', array('keywords','like',$keywords), 'or', array('sn','like',$keywords) ))->orderby('createtime','desc')->getPage();
		}
		else
		{
	    	$category = m('shop.category.get',$categoryid);

	    	if ( $categoryid ) $this->goods->where('categoryid', 'in' ,$category['childids']);

	    	if ( $status ) $this->goods->where('status', $status);

	    	// 商品数据
	    	$dataset = $this->goods->orderby('weight','desc')->orderby('createtime','desc')->getPage();

			// 获取当前状态的数据条数
			foreach( $this->goods->status() as $s=>$t)
			{
				if ( $categoryid )
				{
					$statuscount[$s] = $this->goods->where('categoryid','in',$category['childids'])->where('status','=',$s)->count();
				}
				else
				{
					$statuscount[$s] = $this->goods->where('status','=',$s)->count();
				}
			}
		}


		$this->assign('title',$title);
		$this->assign('categoryid',$categoryid);
		$this->assign('category',$category);
		$this->assign('status',$status);
		$this->assign('statuscount',$statuscount);
		$this->assign('keywords',$keywords);
		$this->assign($dataset);
		$this->display();
	}

	/**
	 * 多选操作
	 *
	 * @param $operation 操作
	 * @return mixed
	 */
    public function action_operate($operation)
    {

		if ( $post = $this->post() )
		{
			if ( empty($post['id']) )
			{
				return $this->error(t('请选择要操作的项'));
			}

			switch($operation)
			{
				case 'delete' :
					$result = $this->goods->delete($post['id']);
					break;
				case 'publish' :
				case 'disabled' :
				case 'draft' :
				case 'trash' :
					$result = $this->goods->where('id', 'in', $post['id'])->set('status', $operation)->update();
					break;
				case 'move':
					$result = $this->goods->where('id', 'in', $post['id'])->set('categoryid', intval($post['categoryid']))->update();
					break;
				case 'weight':
					$result = $this->goods->where('id', 'in', $post['id'])->set('weight', $post['weight'])->update();
					break;
				default :
					break;
			}

			return $result ? $this->success(t('操作成功'),request::referer()) : $this->error(t('操作失败'));
		}

		return $this->error(t('禁止访问'));
    }	

 	/**
	 * 设置单项数据
	 *
	 */
	public function action_set($id, $key, $value='')
	{
		if ( $post = $this->post() )
		{
			$value = $value ? $value : $post['newvalue'];

			if ( $key == 'weight' and ( intval($value) > 100 or intval($value) < 0 ) )
			{
				return $this->error(t('权重必须是0-100之间的数字'));
			}	

			if ( $this->goods->where('id',$id)->set($key, $value)->update() )
			{
				return $this->success(t('操作成功'), request::referer());
			}

			return $this->error($this->goods->error());
		}
	}	

	/**
	 * 添加商品
	 *
	 */
	public function action_add($categoryid=0)
    {
    	if ( $post = $this->post() )
    	{
     		// 预处理数据
    		$post['status'] = ( $post['publish'] == 1 ) ? 'publish' : 'disabled';
    		$post['status'] = ( $post['operate'] == 'draft' ) ? 'draft' : $post['status'];

 			if ( $id = $this->goods->save($post) )
			{
				// 保存草稿
				if (  $post['operate'] == 'draft' ) return $this->message(array('state' => 1, 'content' => t('保存成功'), 'returnvalue' => $id));

				// 保存数据
				if (  $post['operate'] == 'save' ) return $this->success(t('保存成功'));

				// 保存并返回
				return $this->success(t('保存成功'),u('shop/goods/index/'.$categoryid));
			}

			return $this->error($this->goods->error());   		
    	}

    	$category = m('shop.category.get',$categoryid);

    	$data = array();
    	$data['sn'] = $this->goods->sn();
    	$data['typeid'] = $category['settings']['typeid'];
    	$data['categoryid'] = $categoryid;

		$this->assign('title',$category['name']);
		$this->assign('categoryid',$categoryid);
		$this->assign('category',$category);
		$this->assign('data',$data);
		$this->display('shop/goods_post.php');
	}

	/**
	 * 编辑商品
	 *
	 */
	public function action_edit($id=0)
    {
    	if ( $post = $this->post() )
    	{
     		// 预处理数据
    		$post['status'] = ( $post['publish'] == 1 ) ? 'publish' : 'disabled';

 			if ( $id = $this->goods->save($post) )
			{
				// 保存草稿
				if (  $post['operate'] == 'draft' ) return $this->message(array('state' => 1, 'content' => t('保存成功'), 'returnvalue' => $id));

				// 保存数据
				if (  $post['operate'] == 'save' ) return $this->success(t('保存成功'));

				// 保存并返回
				return $this->success(t('保存成功'),u('shop/goods/index/'.$post['categoryid']));
			}

			return $this->error($this->goods->error());   		
    	}

    	$data = $this->goods->get($id);
    	$category = m('shop.category.get',$data['categoryid']);

		$this->assign('title',$category['name']);
		$this->assign('categoryid',$categoryid);
		$this->assign('category',$category);
		$this->assign('data',$data);
		$this->display('shop/goods_post.php');
	}	

	/**
	 * 获取商品属性表单，用于添加和编辑
	 *
	 */
	public function action_attrs()
	{
		$data = $_POST;

		$attrs = m('shop.type.attrs', $data['typeid']);
		$fields = array();

		foreach( $attrs as $id=>$attr )
		{
			$fields[$id]['label'] = $attr['name'];
			$fields[$id]['field'] = array(
				'type'		=> $attr['type'],
				'name'		=> "attrs[{$attr['id']}]",
				'options'	=> $attr['options'],
				'value'		=> $attr['type'] == 'checkbox' ? explode(',', $data['attrs'][$attr['id']]) : $data['attrs'][$attr['id']],
				'class'		=> 'short',
			);
		}

		if ( empty($fields) )
		{
			exit('');
		}

		$this->assign('fields',$fields);
		$this->display('shop/goods_post_attrs.php');
	}

    /**
     * 检查栏目名称是否被占用
     *
     * @return bool
     */
	public function action_check($key,$ignore='')
	{
		$ignore = empty($ignore) ? $_GET['ignore'] : $ignore;

		if ( empty($ignore) )
		{
			$count = $this->goods->where($key,$_GET[$key])->count();
		}
		else
		{
			$count = $this->goods->where($key,$_GET[$key])->where($key,'!=',$ignore)->count();
		}

		exit($count ? '"'.t('已经存在，请重新输入').'"' : 'true');
	}	
}
?>