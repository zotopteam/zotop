<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * kefu
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class kefu_model_kefu extends model
{
	protected $pk = 'id';
	protected $table = 'kefu';


	public function types($type='')
	{
		$types = zotop::filter('kefu.types',array(
			'qq' 	=> t('QQ'),
			'skype' => t('Skype'),
			'phone' => t('电话'),
			'group' => t('分组'),
			'text' 	=> t('文字'),
			'code' 	=> t('代码'),
		));

		return empty($type) ? $types : $types[$type];
	}

	public function select()
	{
		$data = $this->db()->orderby('listorder','asc')->select();

		foreach ($data as &$r)
		{

			$style = $r['style'] ? 'style="'. $r['style'] .'"' : '';

			switch ($r['type'])
			{
				case 'qq':
					$r['show'] .= '<a href="http://wpa.qq.com/msgrd?v=3&uin='.$r['account'].'&site='.c('site.url').'&menu=yes" target="_blank" title="'.$r['text'].'">';
					$r['show'] .= '	<img src="http://wpa.qq.com/pa?p=2:'.$r['account'].':52" class="icon vm"/> <span class="vm" '.$style.'>'.$r['text'].'</span>';
					$r['show'] .= '</a>';
					break;
				case 'skype':
					$r['show'] .= '<a href="skype:'.$r['account'].'?call" title="'.$r['text'].'">';
					$r['show'] .= '	<img src="http://mystatus.skype.com/mediumicon/'.$r['account'].'" class="icon vm"/> <span class="vm" '.$style.'>'.$r['text'].'</span>';
					$r['show'] .= '</a>';
					break;
				case 'phone':
					$r['show'] = '<i class="icon icon-phone"></i> <span title="'.$r['text'] .'" '.$style.'>'.$r['text'].' '.$r['account'].'</span>';
					break;
				default:
					$r['show'] = '<span '.$style.'>'.$r['text'].'</span>';
					break;
			}

		}



		return zotop::filter('kefu.select', $data);
	}


    /**
     * 添加
     *
     */
	public function add($data)
	{
		if ( empty($data['text']) ) return $this->error(t('内容不能为空'));

		$data['listorder'] = $this->max('listorder') + 1;

		return $this->insert($data,true);
	}

    /**
     * 编辑
     *
     */
	public function edit($data, $id)
	{
		if ( empty($data['text']) ) return $this->error(t('内容不能为空'));

		return $this->update($data, $id);
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
     * 保存后处理数据
     *
     */
	protected function _after_insert()
	{
		$this->cache(true);
	}

    /**
     * 保存后处理数据
     *
     */
	protected function _after_update()
	{
		$this->cache(true);
	}

    /**
     * 删除后处理数据
     *
     */
	protected function _after_delete()
	{
		$this->cache(true);
	}

	/*
	 * 缓存数据和设置缓存
	 * @param string $refresh 强制刷新
	 * @return string 缓存数据
	 */
	public function cache($refresh=false)
	{
		$cache = zotop::cache("kefu.data");

		if ( $refresh or empty($cache) or !is_array($cache) )
		{

			$cache = array();

			foreach( $this->select() as $r )
			{
				if (!$r['disabled']) $cache[] = $r;
			}

			zotop::cache("kefu.data", $cache, false);
		}

		return $cache;
	}
}
?>