<?php
defined('ZOTOP') or die('No direct access allowed.');
/**
 * content
 *
 * @package		content
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class content_model_content extends model
{
    protected $pk = 'id';
    protected $table = 'content';

    protected $full_url = true;

    /**
     * 内容状态
     *
     * @param  string $s 传递具体状态值将获得该状态的名称
     * @return mixed
     */
    public function status($s='')
    {
        $status = zotop::filter('content.status',array(
            'publish'   => t('已发布'),
            'pending'   => t('待发布'),
            'draft'     => t('草稿箱'),
            'trash'     => t('回收站'),
        ));

        return $s ? $status[$s] : $status;
    }

    /**
     * 内容url
     *
     * @param  int  $id    内容编号
     * @param  string  $alias URL别名
     * @param  string  $url   url值
     * @param  boolean $full  是否返回格式化的url
     * @return string
     */
    public function url($id, $alias, $url, $full=true)
    {
        if ( $url ) return $url;

        if ( $alias )
        {
            return $full? U($alias) : $alias;
        }

        return $full? U("content/detail/{$id}") : "content/detail/{$id}";
    }

    /**
     * 是否为完整链接
     * 
     * @param  boolean $full_url [description]
     * @return [type]            [description]
     */
    public function full_url($full_url=true)
    {
        $this->full_url = $full_url;

        return $this;
    }

    /**
     * 获取数据
     *
     * @return array
     */
    public function select()
    {
        $data = $this->db()->select();

        foreach ($data as &$d)
        {
            $d['url']    = $this->url($d['id'], $d['alias'], $d['url'], $this->full_url);
            $d['dataid'] = "content-{$d['id']}";
        }

        return $data;
    }


    /**
     * 获取列表数据
     *
     * @param integer $page 页数
     * @param integer $pagesize 每页条数
     * @param bool $total 总数
     * @return
     */
    public function paginate($page = 0, $pagesize = 20, $total = false)
    {
        $dataset = $this->db()->paginate($page, $pagesize, $total);

        foreach ($dataset['data'] as &$d)
        {
            $d['url'] = $this->url($d['id'], $d['alias'], $d['url']);
        }

        return $dataset;
    }


    /**
     * 获取未处理数据条数
     *
     */
    public function statuscount($status='publish')
    {
        if ( $status )
        {
            $this->where('status', '=', $status);
        }

        return $this->count();
    }

    /**
     * 获取扩展模型对象
     *
     * @param  string $modelid 模型id
     * @return object 模型对象
     */
    public function extend($modelid)
    {
        if ( $modelid )
        {
            $model = m('content.model.get',$modelid);
        }

        if ( $model and $model['app'] and $model['model'] )
        {
            return m("{$model['app']}.{$model['model']}")->init($this, $modelid);
        }

        return null;
    }


    /**
     * 获取数据
     *
     * @param int $id 内容编号
     * @return array
     */

    public function get($id, $field='')
    {
        $data = $this->getbyid($id);

        if ( is_array($data) )
        {
            // 附件编号
            $data['dataid'] = "content-{$id}";

            // 扩展模型数据
            if ( $extend = $this->extend($data['modelid']) )
            {
                $data = array_merge($data, $extend->get($id));
            }

            unset($extend);

            $data = zotop::filter('content.get', $data);          
        }

        return $data;

    }

    /**
     * 保存数据，根据传入编号自动判断是新增还是保存
     *
     * @param mixed $data
     * @return bool
     */
    public function save($data)
    {
        // 预处理 HOOK
        $data = zotop::filter('content.data', $data , $this);        

        if ( empty($data['title']) ) return $this->error(t('标题不能为空'));
        if ( empty($data['modelid']) ) return $this->error(t('模型不能为空'));
        //if ( empty($data['categoryid']) ) return $this->error(t('栏目不能为空'));

        // 检查别名是否存在
        if ( $data['alias'] and $alias = alias($data['alias']) )
        {
            if ($alias != "content/detail/{$data['id']}") return $this->error(t('别名已经存在'));
        }

        // 预处理数据
        $fields = m('content.field.cache', $data['modelid']);

        foreach ($fields as $name => $field)
        {
            if ( $field['notnull'] and empty($data[$name]) ) return $this->error(t('$1不能为空', $field['label']));
            if ( $field['settings']['maxlength'] and str::len($data[$name]) > $field['settings']['maxlength'] ) return $this->error(t('$1最大长度为$2', $field['label'],$field['settings']['maxlength']));
            if ( $field['settings']['minlength'] and str::len($data[$name]) < $field['settings']['minlength'] ) return $this->error(t('$1最小长度为$2', $field['label'],$field['settings']['minlength']));
            if ( $field['settings']['max'] and intval($data[$name]) > $field['settings']['max'] ) return $this->error(t('$1最大值为$2', $field['label'],$field['settings']['max']));
            if ( $field['settings']['min'] and intval($data[$name]) < $field['settings']['min'] ) return $this->error(t('$1最小值为$2', $field['label'],$field['settings']['min']));
            if ( $field['control'] == 'date' or $field['control'] == 'datetime' ) $data[$name] = empty($data[$name]) ? ZOTOP_TIME : strtotime($data[$name]);
            if ( $field['control'] == 'keywords' and $data[$name] ) $data[$name] = str_replace('，', ',', $data[$name]);
            if ( $field['control'] == 'editor' and $data[$name] )
            {
                if ( intval(C('content.auto_summary')) > 0 and empty($data['summary']) )
                {
                    $data['summary'] = str_replace(array("\r","\n","\r\n","\t",'[page]','[/page]','&ldquo;','&rdquo;','&nbsp;'), '', strip_tags(trim($data[$name])));
                    $data['summary'] = str::cut($data['summary'], intval(C('content.auto_summary')));
                }
                if ( intval(C('content.auto_image')) >= 1 and empty($data['image']) and preg_match_all("/(src)=([\"|']?)([^ \"'>]+\.(gif|jpg|jpeg|bmp|png))\\2/i", stripslashes($data[$name]), $matches) )
                {
                    $data['image'] = $matches[3][intval(C('content.auto_image')) - 1];
                }
            }
            if ( $field['control'] == 'images' and $data[$name] )
            {
                $data[$name] = array_values($data[$name]); // 清除键值，修复图集的排序问题

                if ( intval(C('content.auto_image')) >= 1 and empty($data['image']) )
                {
                    $data['image'] = $data[$name][intval(C('content.auto_image')) - 1]['image'];
                }
            }
            if ( $field['control'] == 'files' and $data[$name] )
            {
                $data[$name] = array_values($data[$name]);
            }
        }

        // 保存
        $result = empty($data['id']) ? $this->add($data) : $this->edit($data);

        if ( $result )
        {
            // 保存别名
            alias($data['alias'], "content/detail/{$data['id']}");

            // 保存关联附件
            m('system.attachment')->setRelated("content-{$data['id']}");

            // 保存标签
            m('content.tag')->setRelated($data['id'], $data['keywords']);

            zotop::run('content.save', $data);

            // 保存到区块
            // m('block.datalist')->setCommend($data['blockids'], array(
            //     'app'           => 'content',
            //     'dataid'        => "content-{$data['id']}",
            //     'title'         => $data['title'],
            //     'style'         => $data['style'],
            //     'url'           => $this->url($data['id'], $data['alias'], $data['url'], false),
            //     'image'         => $data['image'],
            //     'description'   => $data['summary'],
            //     'time'          => $data['createtime']
            // ));
        }

        return $result;
    }

    /**
     * 添加数据
     *
     */
    public function add(&$data)
    {
        // 填充数据
        $data['id']         = null;
        $data['userid']     = zotop::user('id');
        $data['createtime'] = empty($data['createtime']) ? ZOTOP_TIME : $data['createtime'];
        $data['updatetime'] = ZOTOP_TIME;
        $data['listorder']  = ZOTOP_TIME;
        $data['status']     = empty($data['status']) ? 'publish' : $data['status'];

        // 添加数据
        if ( $data['id'] = $this->insert($data) )
        {
            if ( $extend = $this->extend($data['modelid']) )
            {
                if ( $extend->insert($data) )
                {
                    return $data['id'];
                }

                $this->delete($data['id']);

                return $this->error($extend->error());
            }

            return $data['id'];
        }

        return false;
    }

    /**
     * 编辑数据
     *
     */
    public function edit(&$data)
    {
        // 填充数据
        $data['createtime'] = empty($data['createtime']) ? ZOTOP_TIME : $data['createtime'];
        $data['updatetime'] = ZOTOP_TIME;

        // 更新数据
        if ( $this->update($data, $data['id']) )
        {
            if ( $extend = $this->extend($data['modelid']) )
            {
                if ( $extend->insert($data, true) )
                {
                    return $data['id'];
                }

                return $this->error($extend->error());
            }

            return $data['id'];
        }

        return false;
    }


    /**
     * 删除数据
     *
     */
    public function delete($id)
    {
        if (is_array($id)) return array_map(array($this, 'delete'), $id);

        if (empty($id)) return $this->error(t('编号格式错误'));

        if ( $data = $this->getbyid($id) )
        {
            if ( $extend = $this->extend($data['modelid']) )
            {
                if ( !$extend->delete($id) )
                {
                    return $this->error($extend->error());
                }
            }

			if ( parent::delete($id) )
			{
				// 删除数据的同时删除别名
				alias(null, "content/detail/{$id}");

				// 删除关联附件
				m('system.attachment')->delRelated("content-{$id}");

                //删除关联推荐
                m('block.datalist')->delCommend("content-{$id}");

				return true;
			}

            return false;

        }

        return $this->error(t('编号[%s]数据不存在', $id));
    }


	/**
	 * 点击
	 *
	 */
	public function hit($id)
	{
		if( $id )
		{
			return $this->where('id',$id)->data('hits',array('hits','+',1))->update();
		}

		return false;
	}


    /**
     * 模板标签解析，返回数据组
     *
     * @param array $options
     * @return array
     */
    public function tag_content($options)
    {
		if ( !is_array($options) ) return array();

		// 导入变量
		extract( $options );

		// 默认支持的标签缩写
        $mid = isset($mid)? $mid : $modelid;
		$cid = isset($cid)? intval($cid) : intval($categoryid);

		if ( $cid )
		{
			$cids = strpos($cid,',') ? explode(',', $cid) : explode(',', m('content.category.get', $cid, 'childids'));
		}

		// 初始化读取数据，只读取已经发布的数据
		$db = $this->db()->field('*')->where('status','=','publish');

        // 如果有id标签，直接返回 TODO 这段暂时未测试
        if ( $id )
        {
            $data = $db->where('id','in',$id)->select();

            return $this->process($data);
        }

		// 读取分类数据
		if ( is_array($cids) and $cids )
		{
			 ( count($cids) == 1 ) ? $db->where('categoryid','=',intval(reset($cids))) : $db->where('categoryid','in',$cids);
		}

		// 读取模型数据
		if ( $mid ) $db->where('modelid','=',$mid);

		// 查询结果是否必须包含缩略图
		if ( strtolower($image) == 'true' ) $db->where('image','!=','');
		if ( strtolower($image) == 'false' ) $db->where('image','=','');

		// 前后的ID数据
		if ( intval($prev) ) { $db->where('id','>',intval($prev)); $orderby = 'id asc'; }
		if ( intval($next) ) { $db->where('id','<',intval($next)); $orderby = 'id desc'; }

		// 前后的时间数据
		if ( intval($prevtime) ) { $db->where('createtime','>',intval($prevtime)); $orderby = 'createtime asc'; }
		if ( intval($nexttime) ) { $db->where('createtime','<',intval($nexttime)); $orderby = 'createtime desc'; }


		// 权重,支持整数以及范围，如:weight="10" 或者weight="0,10", weight="10,"

        /*
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
        */

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
		$orderby ? $db->orderby($orderby) : $db->orderby('stick desc, listorder desc');

		// 读取数据条数
		$size = intval($size) ? intval($size) : 10;

		// 分页
		if ( !empty($page) )
		{
			$page = ( intval($page)>0 ) ? intval($page) : 0;

			$return = $db->paginate($page, $size, intval($total));
			$return['data'] = $this->process($return['data'], $mid);
		}
		else
		{
			$return = $db->limit($size)->select();
			$return = $this->process($return, $mid);
		}

		return $return;
    }


	// 处理数据
	public function process($data, $mid=null)
	{
		$return = array();

		// 处理数据
        foreach ($data as $d)
        {
            $d['url']   = $this->url($d['id'], $d['alias'], $d['url']);
            $d['style'] = $d['style'] ? ' style="'.$d['style'].'"' : '';
            $d['tags']  = explode(',', $d['keywords']);

			// 插入标签 newflag 及 默认的最新标识：“新”
			if ( $f = C('content.newflag') )
			{
				if ( ( ZOTOP_TIME - $d[$f]) <= C('content.newflag_expires') * 3600 ) $d['new'] = ' <i class="new">'.t('新').'</i>';
			}

			$return[$d['id']] = $d;
        }

		// 调用附表数据
		if ( $mid and $return )
		{
			if ( $extend = $this->extend($mid) )
            {
                $data = $extend->field('*')->where('id','in', array_keys($return))->orderby(null)->select();

				foreach($data as $r)
				{
					if ( isset( $return[$r['id']] ) ) $return[$r['id']] = array_merge($return[$r['id']], $r);
				}
			}
		}

		return zotop::filter('content.process', $return);
	}
}
?>