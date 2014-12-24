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

    public $statuses = array();

    public function __construct()
    {
        parent::__construct();

        $this->statuses = array(
            'publish'   => t('发布'),
            'pending'   => t('待审'),
            'reject'    => t('退稿'),
            'draft'     => t('草稿'),
            'trash'     => t('回收站'),
        );
    }

    /**
     * 获取数据
     *
     */
    public function getAll()
    {
        $data = $this->db()->getAll();

        foreach ($data as &$d)
        {
            if (empty($d['url']))
            {
                $d['url'] = empty($d['alias']) ? U("content/detail/{$d['id']}") : U($d['alias']);
            }
        }

        return $data;
    }


    /**
     * 获取列表数据，url状态转换
     *
     * @param integer $page 页数
     * @param integer $pagesize 每页条数
     * @param bool $total 总数
     * @return
     */
    public function getPage($page = 0, $pagesize = 20, $total = false)
    {
        $dataset = $this->db()->getPage($page, $pagesize, $total);

        foreach ($dataset['data'] as &$d)
        {
            if (empty($d['url']))
            {
                $d['url'] = empty($d['alias']) ? U("content/detail/{$d['id']}") : U($d['alias']);
            }
        }

        return $dataset;
    }

    /**
     * 获取未处理数据条数
     *
     */
    public function getPendingCount()
    {
        static $count = null;

        if ($count === null)
        {
            $count = $this->db()->where('status', '=', 'pending')->count();
        }

        return $count;
    }

    /**
     * 计算条数
     *
     */
    public function count()
    {
        $result = $this->getField('COUNT(id) AS zotop_count');

        return is_numeric($result) ? $result : 0;
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

        if ( is_array($data) and $data['app'] and $data['modelid'] )
        {
            // 附件编号
            $data['dataid'] = "content-{$id}";

            // 初始化模型
            $model = m("{$data['app']}.{$data['modelid']}")->init($this);

			// 模型前置接口
			$model->before_get($data);

			// 合并数据
			$data = array_merge($data, $model->get($id));

			// 模型后置接口
			$model->after_get($data);

            unset($model);
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
        if ( empty($data['title']) ) return $this->error(t('标题不能为空'));
        if ( empty($data['app']) ) return $this->error(t('应用不能为空'));
        if ( empty($data['modelid']) ) return $this->error(t('模型不能为空'));
        if ( empty($data['categoryid']) ) return $this->error(t('栏目不能为空'));

        // 检查别名是否存在
        if ( $data['alias'] and $alias = alias($data['alias']) )
        {
            if ($alias != "content/detail/{$data['id']}") return $this->error(t('别名已经存在'));
        }        

		// 自动提取摘要
		if ( intval(C('content.autosummary')) && empty($data['summary']) && isset($data['content']))
		{
			$data['summary'] = str_replace(array("\r","\n","\r\n","\t",'[page]','[/page]','&ldquo;','&rdquo;','&nbsp;'), '', strip_tags(trim($data['content'])));
			$data['summary'] = str::cut($data['summary'], intval(C('content.autosummary')));

		}

		// 自动提取缩略图
		if ( intval(C('content.autothumb')) && empty($data['thumb']) && isset($data['content']) )
		{
			$imageid = intval(C('content.autothumb')) - 1 ; //自动提取第几张图片作为缩略图

			if( $imageid >= 0 and preg_match_all("/(src)=([\"|']?)([^ \"'>]+\.(gif|jpg|jpeg|bmp|png))\\2/i", stripslashes($data['content']), $matches) )
			{
				$data['thumb'] = $matches[3][$imageid];
			}
		}

        // 关键词处理：中文逗号替换为英文逗号
        if ( $data['keywords'] )
        {
            $data['keywords'] = str_replace('，', ',', $data['keywords']);
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

            // 保存到区块
            m('block.datalist')->setCommend($data['blockids'], array(
                'app'           => 'content',
                'dataid'        => "content-{$data['id']}",
                'title'         => $data['title'],
                'style'         => $data['style'],
                'url'           => $data['alias'] ? $data['alias'] : "content/detail/{$data['id']}",                
                'image'         => $data['thumb'],
                'description'   => $data['summary'],
                'time'          => $data['createtime']
            ));
        }

        return $result;
    }

    /**
     * 添加数据
     *
     */
    public function add(&$data)
    {
        // 获取模型
        $model = m("{$data['app']}.{$data['modelid']}")->init($this);

        if (!$model)
        {
            return $this->error(t('模型 %s 不存在', $data['app'] . '.' . $data['modelid']));
        }

        // 前置添加检验
        if (false === $model->before_add($data))
        {
            return $this->error($model->error());
        }

        // 填充数据
        $data['id']         = null;
        $data['userid']     = zotop::user('id');
        $data['createtime'] = empty($data['createtime']) ? ZOTOP_TIME : strtotime($data['createtime']);
        $data['status']     = empty($data['status']) ? 'pending' : $data['status'];

        // 添加数据
        if ( $data['id'] = $this->insert($data) and $model->add($data) )
        {
            // 后置添加
            $model->after_add($data);

            //添加数据成功
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
        // 获取模型
        $model = m("{$data['app']}.{$data['modelid']}")->init($this);

        if (!$model)
        {
            return $this->error(t('模型 %s 不存在', $data['app'] . '.' . $data['modelid']));
        }

        // 前置编辑检验
        if (false === $model->before_edit($data))
        {
            return $this->error($model->error());
        }

        // 填充数据
        $data['createtime'] = empty($data['createtime']) ? ZOTOP_TIME : strtotime($data['createtime']);
        $data['updatetime'] = ZOTOP_TIME;

        if ( $this->update($data) and $model->edit($data) )
        {
            // 后置编辑
            $model->after_edit($data);

            //编辑数据成功
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

        // 前置删除接口
        zotop::run('content.before_delete', $id);

        if ($data = $this->getbyid($id))
        {
            $model = m("{$data['app']}.{$data['modelid']}")->init($this);

			// 前置编辑检验
			if ( false === $model->before_delete($data) )
			{
				return $this->error($model->error());
			}

			if (parent::delete($id))
			{
				// 删除附表数据
				$model->delete($id);

				// 删除数据的同时删除别名
				alias(null, "content/detail/{$id}");

				// 删除关联附件
				m('system.attachment')->delRelated("content-{$id}");

                // 后置编辑
                $model->after_delete($data);

				// 后置删除接口
				zotop::run('content.after_delete', $data);

				return true;
			}

            unset($model);
        }

        return $this->error(t('编号[%s]数据不存在', $id));
    }


	/**
	 * 点击
	 *
	 */
	public function hit($id)
	{
	    zotop::run('content.hit',$id);

		if( $id )
		{
			return $this->where('id',$id)->set('hits',array('hits','+',1))->update();
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

		// 读取栏目数据
		$cid = isset($cid)? $cid : $categoryid;

		if ( $cid )
		{
			$cids = strpos($cid,',') ? explode(',', $cid) : explode(',', m('content.category.get', $cid, 'childids'));
		}

		// 初始化读取数据，只读取已经发布的数据
		$db = $this->db()->select('*')->where('status','=','publish');

		// 读取分类数据
		if ( is_array($cids) and $cids )
		{
			 ( count($cids) == 1 ) ? $db->where('categoryid','=',intval(reset($cids))) : $db->where('categoryid','in',$cids);
		}

		// 单独模型数据
		if ( $modelid ) $db->where('modelid','=',$modelid);

		// 查询结果是否必须包含缩略图
		if ( strtolower($thumb) == 'true' ) $db->where('thumb','!=','');
		if ( strtolower($thumb) == 'false' ) $db->where('thumb','=','');

		// 前后的ID数据
		if ( intval($prev) ) { $db->where('id','>',intval($prev)); $orderby = 'id asc'; }
		if ( intval($next) ) { $db->where('id','<',intval($next)); $orderby = 'id desc'; }

		// 前后的时间数据
		if ( intval($prevtime) ) { $db->where('createtime','>',intval($prevtime)); $orderby = 'createtime asc'; }
		if ( intval($nexttime) ) { $db->where('createtime','<',intval($nexttime)); $orderby = 'createtime desc'; }


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
			$return['data'] = $this->process($return['data'], $modelid, $modeldata);
		}
		else
		{
			$return = $db->limit($size)->getAll();
			$return = $this->process($return, $modelid, $modeldata);
		}

		return $return;
    }


	// 处理数据
	public function process($data, $model=null, $modeldata=null)
	{
		$return = array();

		// 处理数据
        foreach ($data as $d)
        {
            if (empty($d['url']))
            {
                $d['url'] = empty($d['alias']) ? U("content/detail/{$d['id']}") : U($d['alias']);
            }

			if ( $d['style'] )
			{
				$d['style'] = ' style="'.$d['style'].'"';
			}

			// 插入标签 newflag 及 默认的最新标识：“新”
			if ( $f = C('content.newflag') )
			{
				if ( ( ZOTOP_TIME - $d[$f]) <= C('content.newflag_expires') * 3600 ) $d['new'] = ' <i class="new">'.t('新').'</i>';
			}

            // 处理tags

            $d['tags'] = explode(',', $d['keywords']);

			$return[$d['id']] = $d;
        }

		// 调用附表数据
		if ( $model and $modeldata and !empty($return) )
		{
			$models = m('content.model')->cache();

			if ( $model = $models[strtolower($model)] and $model['tablename'] )
			{
				$modeldata = ($modeldata == 'true') ? '*' : 'id,'.$modeldata;

				$_data = m("{$model['app']}.{$model['id']}")->select($modeldata)->where('id','in', array_keys($return))->orderby(null)->getall();

				foreach($_data as $r)
				{
					if ( isset( $return[$r['id']] ) ) $return[$r['id']] = array_merge($return[$r['id']], $r);
				}
			}
		}

		return zotop::filter('content.process', $return);
	}
}
?>