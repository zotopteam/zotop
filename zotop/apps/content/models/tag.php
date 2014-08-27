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
class content_model_tag extends model
{
    protected $pk = 'id';
    protected $table = 'content_tag';

	public $tagdata;

    public function __construct()
    {
        parent::__construct();

        $this->tagdata = m('content.tagdata');
    }

    /**
     * 设置标签关联
     *
     * @param mixed $contentid
     * @param mixed $tags
     * @return void
     */
    public function setRelated($contentid, $str)
    {
        if (empty($contentid)) return false;

        // 删除标签
		$this->tagdata->where('contentid', $contentid)->delete();

		if ( empty($str) ) return false;

        // 添加标签
        $str = str_replace('，', ',', $str);

        $tags = explode(',', $str);

        foreach ($tags as $tag)
        {
            if (empty($tag)) continue;

            // 读取标签信息,如果找到且未添加过关联则增加次数,否则增加新标签并增加关联
			$tagid = $this->where('name',$tag)->getField('id');

            if ( empty($tagid) )
            {
                $tagid = $this->insert(array('name' => $tag, 'quotes' => 1));
            }

			$this->tagdata->insert(array('tagid' => $tagid, 'contentid' => $contentid), true);

			// 重新计算引用次数
			$quotes = $this->tagdata->where('tagid',$tagid)->count();

			$this->where('id', $tagid)->set('quotes', $quotes)->update();
        }

        return true;
    }

    /**
     * 删除标签关联
     *
     * @param mixed $contentid
     * @param mixed $tags
     * @return void
     */
    public function delRelated($contentid)
    {
		// 获取内容的全部标签
		$data = $this->tagdata->where('contentid',$contentid)->getAll();

		if ($data)
		{
			 //删除该内容在tagdata中关联性数据
			$this->tagdata->where('contentid',$contentid)->delete();

			//查找其它关联，如果没有任何关联，则直接删除tag
			foreach ($data as $r)
			{
				if ( !$this->tagdata->where('tagid',$r['tagid'])->count() )
				{
					$this->delete($r['tagid']);
				}
			}
		}

		return true;
	}

    /**
     * 删除标签及关联数据
     *
     * @param mixed $contentid
     * @param mixed $tags
     * @return void
     */
	public function deltags($id)
	{
		if ( is_array($id) ) return array_map(array($this,'delete'), $id );

		if ( $id and $this->tagdata->where('tagid',$id)->delete() )
		{
			$this->delete($id);
			return true;
		}

		return false;
	}
}
?>