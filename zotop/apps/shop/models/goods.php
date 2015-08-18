<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 规格
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class shop_model_goods extends model
{
	protected $pk = 'id';
	protected $table = 'shop_goods';

	public function __construct()
	{
		parent::__construct();
	}


	/*
	 *  生成商品货号
	 */
	public function sn()
	{
		static $sn = '';

		if ( empty($sn) )
		{
			$sn = strtoupper(c('shop.goods_sn').ZOTOP_TIME.rand(10, 99));
		}

		return $sn;
	}

	/*
	 *  商品状态
	 */
	public function status($s='')
	{
		$status = array(
			'publish' 	=> t('上架'),
			'disabled' 	=> t('下架'),
			'draft' 	=> t('草稿'),
			'trash' 	=> t('回收站'),
		);

		return $s ? $status[$s] : $status;
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
			$data['gallery'] = unserialize($data['gallery']);
			$data['gallery'] = is_array($data['gallery']) ? $data['gallery'] : array();

			$data[$r['id']] = $r;
		}

		return $data;
	}

	/*
	 *  获取数据
	 */
	public function get($id, $field='')
	{
		$data = $this->getbyid($id);

		if ( is_array($data)  )
		{

			$data['gallery'] = unserialize($data['gallery']);
			$data['gallery'] = is_array($data['gallery']) ? $data['gallery'] : array();

			// 保存关联附件
            $data['dataid'] = "shop-goods-{$data['id']}";

            // 获取属性
            $data['attrs'] = m('shop.goods_attr.get', $id);

			return $data;
		}

		return array();
	}

    /**
     * 保存数据，根据传入编号自动判断是新增还是保存
     *
     * @param mixed $data
     * @return bool
     */
    public function save($data)
    {
        if ( empty($data['sn']) ) return $this->error(t('商品编号不能为空'));
        if ( empty($data['name']) ) return $this->error(t('商品名称不能为空'));
		if ( empty($data['typeid']) ) return $this->error(t('商品类型不能为空'));

		// 中文逗号替换为英文逗号
        $data['keywords'] = str_replace('，', ',', $data['keywords']);
		$data['gallery'] = is_array($data['gallery']) ? $data['gallery'] : array();


        return empty($data['id']) ? $this->add($data) : $this->edit($data);
    }

	/*
	 *  添加数据
	 */
	public function add($data)
	{
        // 填充数据
        $data['userid'] 	= zotop::user('id');
        $data['keywords'] 	= str_replace('，', ',', $data['keywords']);
        $data['status'] 	= empty($data['status']) ? 'publish' : $data['status'];
        $data['createtime'] = empty($data['createtime']) ? ZOTOP_TIME : strtotime($data['createtime']);

		if ( $data['id'] = $this->insert($data) )
		{
			// 保存商品属性
			m('shop.goods_attr')->set($data['id'], $data['attrs']);

            // 保存关联附件
            m('system.attachment')->setRelated("shop-goods-{$id}");

             // 后置编辑接口
            zotop::run('goods.after_add', $data, $this);

			return $data['id'];
		}

		return false;
	}

	/*
	 *  编辑数据
	 */
	public function edit($data)
	{
        // 填充数据
        $data['status'] 	= empty($data['status']) ? 'publish' : $data['status'];
        $data['createtime'] = empty($data['createtime']) ? ZOTOP_TIME : strtotime($data['createtime']);
        $data['updatetime'] = ZOTOP_TIME;

		if ( $data['id'] and $this->update($data) )
		{
			// 保存商品属性
			m('shop.goods_attr')->set($data['id'], $data['attrs']);

            // 保存关联附件
            m('system.attachment')->setRelated("shop-goods-{$data['id']}");

            // 后置编辑接口
            zotop::run('goods.after_edit', $data, $this);

			return $data['id'];
		}

		return false;
	}

	/*
	 *  删除数据
	 */
	public function delete($id)
	{
		if ( parent::delete($id) )
		{
			// 删除商品属性
			m('shop.goods_attr')->set($id, null);

            // 删除关联附件
            m('system.attachment')->delRelated("shop-goods-{$id}");

			return true;
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

		return true;
	}

    /**
     * 模板标签解析，返回数据组
     *
     * @param array $options
     * @return array
     */
    public function tag_list($options)
    {
		if ( !is_array($options) ) return array();

		// 导入变量
		extract( $options );

		// 读取栏目数据
		$cid = isset($cid)? $cid : $categoryid;

		if ( $cid )
		{
			$cids = strpos($cid,',') ? explode(',', $cid) : explode(',', m('shop.category.get', $cid, 'childids'));
		}

		// 初始化读取数据，只读取已经发布的数据
		$db = $this->db()->field('*')->where('status','=','publish');

		// 读取分类数据
		if ( is_array($cids) and $cids )
		{
			 ( count($cids) == 1 ) ? $db->where('categoryid','=',intval(reset($cids))) : $db->where('categoryid','in',$cids);
		}

		// 单独模型数据
		if ( $model ) $db->where('modelid','=',$model);

		// 查询结果是否必须包含缩略图
		if ( strtolower($thumb) == 'true' ) $db->where('thumb','!=','');
		if ( strtolower($thumb) == 'false' ) $db->where('thumb','=','');

		// 前后的ID数据
		if ( intval($prev) ) { $db->where('id','>',intval($prev)); $orderby = 'id desc'; }
		if ( intval($next) ) { $db->where('id','<',intval($next)); $orderby = 'id asc'; }

		// 前后的时间数据
		if ( intval($prevtime) ) { $db->where('createtime','>',intval($prevtime)); $orderby = 'createtime desc'; }
		if ( intval($nexttime) ) { $db->where('createtime','<',intval($nexttime)); $orderby = 'createtime asc'; }


		// 权重,支持整数以及范围，如:weight="10" 或者weight="0,10", weight="10,"
		if ( !empty($weight) )
		{
			if ( strpos( $weight, "," ) === FALSE )
			{
				$db->where('weight','=',intval($weight));
			}
			elseif ( preg_match( "/^\\s*([\\d]*)\\s*\\,\\s*([\\d]*)\\s*\$/", $weight, $m ) )
			{
				if ( $m[1] ) $db->where('weight','>=',intval($m[1]));
				if ( $m[2] ) $db->where('weight','<=',intval($m[2]));
			}
		}

		// 根据关键词筛选
		if ( !empty($keywords) )
		{
			$keywords = explode(',', $keywords);
			$keywhere = array();
			foreach($keywords as $k)
			{
				$keywhere[] = 'or';
				$keywhere[] = array('keywords','like', $k);
			}
			array_shift($keywhere);
			$db->where($keywhere);
		}

		// 忽略数据
		$ignore and $db->where('id', '!=' , $ignore);

		// 排序
		$orderby ? $db->orderby($orderby) : $db->orderby('weight desc, createtime desc');

		// 读取数据条数
		$size = intval($size) ? intval($size) : 10;

		// 分页
		if ( !empty($page) )
		{
			$page = ( intval($page)>0 ) ? intval($page) : 0;

			$return = $db->getPage($page, $size, intval($total));
			$return['data'] = $this->process($return['data'], $model, $modeldata);
		}
		else
		{
			$return = $db->limit($size)->select();
			$return = $this->process($return, $model, $modeldata);
		}

		return $return;
    }


	// 处理数据
	private function process($data, $model, $modeldata)
	{
		$return = array();

		// 处理数据
        foreach ($data as $d)
        {
            if (empty($d['url']))
            {
                $d['url'] = empty($d['alias']) ? U("shop/detail/{$d['id']}") : U($d['alias']);
            }

			if ( $d['style'] )
			{
				$d['style'] = ' style="'.$d['style'].'"';
			}

			// 插入标签 newflag 及 默认的最新标识：“新”
			if ( $f = C('shop.newflag') )
			{
				if ( ( ZOTOP_TIME - $d[$f]) <= C('shop.newflag_expires') * 3600 ) $d['new'] = ' <i class="new">'.t('新').'</i>';
			}

            // 处理tags

            $d['tags'] = explode(',', $d['keywords']);

			$return[$d['id']] = $d;
        }

		// 调用附表数据
		if ( $model and $modeldata and !empty($return) )
		{
			$models = m('shop.model')->cache();

			if ( $model = $models[strtolower($model)] and $model['tablename'] )
			{
				$modeldata = ($modeldata == 'true') ? '*' : 'id,'.$modeldata;

				$_data = m("{$model['app']}.{$model['id']}")->select($modeldata)->where('id','in', array_keys($return))->orderby(null)->select();

				foreach($_data as $r)
				{
					if ( isset( $return[$r['id']] ) ) $return[$r['id']] = array_merge($return[$r['id']], $r);
				}
			}
		}

		return zotop::filter('shop.process', $return);
	}
}
?>