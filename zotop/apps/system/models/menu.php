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
        $select   = array();

        foreach ($this->select() as $d)
        {
            $data = unserialize(arr::pull($d,'data'));

            $data[$d['id']] = array_merge($d, (array)$data);
        }

        return $select;
    }


    /**
     * 取得缓存的数据
     * 
     * @param  string  $rootid  根编号
     * @param  boolean $refresh 是否强制刷新
     * @return array
     */
	public function cache($rootid=null, $refresh=false)
	{
        $rootid = $rootid ? $rootid : $this->rootid;
        
        $name   = 'menu.'.$rootid;
        
        $data   = zotop::cache($name);

        if ( $refresh or empty($data) or !is_array($data) )
        {
            $data = $this->where('rootid', $rootid)->where('disabled',0)->orderby('listorder','asc')->select();

            zotop::cache($name, $data, false);
        }

        return $data;
	}

}
?>