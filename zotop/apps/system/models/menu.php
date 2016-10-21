<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 菜单模型
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_model_menu extends model
{
    protected $pk    = array('id','rootid');
    protected $table = 'menu';

    public $rootid = null;
    
    /**
     * 覆盖默认的数据选择，解码数据中的data为数组并展开
     * 
     * @return array
     */
    public function select()
    {   
        $select = array();

        foreach (parent::select() as $r)
        {
            if ( $data = unserialize($r['data']) )
            {
                $r['data'] = $data;
            }

            $select[$r['id']] = $r;            
        }

        return $select;
    }


    /**
     * 获取树形
     * 
     * @param  string  $rootid  根编号
     * @param  boolean $refresh 是否强制刷新
     * @return array
     */
	public function tree($rootid=null, $refresh=false)
	{
        $rootid = $rootid ? $rootid : $this->rootid;
        
        $name   = 'menu.'.$rootid;
        
        $data   = zotop::cache($name);

        if ( $refresh or empty($data) or !is_array($data) )
        {
            $data = $this->field('id,parentid,data')->where('rootid', $rootid)->where('disabled',0)->orderby('listorder','asc')->select();
            $data = arr::tree($data, $rootid);
            $data = $this->flatten($data);

            zotop::cache($name, $data, false);
        }
        
        $data = zotop::filter($name, $data);

        //tt($data);

        return $data;
	}

    /**
     * 将树形转化为扁平树形
     * 
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    private function flatten($data)
    {
        $flatten = array();

        foreach ($data as $key => $value)
        {
            $flatten[$key] = $value['data'];

            if ( is_array($value['data']) AND is_array($value['children']) AND $value['children'] )
            {
                $flatten[$key]['children'] = $this->flatten($value['children']);
            }
        }

        return $flatten;
    }
}
?>