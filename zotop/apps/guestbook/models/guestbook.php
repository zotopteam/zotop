<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * guestbook
 *
 * @package		guestbook
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class guestbook_model_guestbook extends model
{
	protected $pk = 'id';
	protected $table = 'guestbook';

    /**
     * 状态
     *
     * @param  string $s 传递具体状态值将获得该状态的名称
     * @return mixed
     */
    public function status($s='')
    {
        $status = zotop::filter('guestbook.status',array(
			'publish'	=> t('通过审核'),
			'pending'	=> t('等待审核'),
			'trash'		=> t('垃圾信息'),
        ));

        return $s ? $status[$s] : $status;
    }

    /**
     * 获取数据
     *
     */
	public function select()
	{
		return $this->db()->select();
	}

    /**
     * 获取列表数据
     *
     */
	public function paginate($page=0, $pagesize=20, $total = false)
	{
		return $this->db()->paginate($page,$pagesize,$total);
	}


    /**
     * 获取特定状态 数据条数
     *
     */
    public function statuscount($status)
    {
        static $statuscount = array();

        if ( !isset($statuscount[$status]) )
        {
            $statuscount[$status] = $this->where('status', '=', $status)->count();
        }

        return $statuscount[$status];
    }	

    /**
     * 插入
     *
     */
	public function add($data)
	{
		$data['userid'] 	= zotop::user('id');
		$data['createip'] 	= request::ip();
		$data['createtime'] = ZOTOP_TIME;
		$data['status'] 	= 'pending';

		if ( empty($data['name']) ) return $this->error(t('名称不能为空'));
		if ( empty($data['content']) ) return $this->error(t('内容不能为空'));
		if ( strlen($data['content']) > intval(c('guestbook.maxlength')) )  return $this->error(t('内容最多 %s 个字符',c('guestbook.maxlength')));
		if ( strlen($data['content']) < intval(c('guestbook.minlength')) )  return $this->error(t('内容最少 %s 个字符',c('guestbook.minlength')));

		// 检查url个数
		if ( $maxlinks = intval(c('guestbook.maxlinks')) )
		{
			$numlinks = preg_match_all("/[a-zA-z0-9][\/\:a-zA-z0-9-]+[\.][^\x80-\xff\s]+[\?\#\/\\\a-zA-z0-9]/i", $data['content'], $out );

			if ( $numlinks >= $maxlinks )
			{
				$data['status'] = 'trash';  //设为垃圾
			}
		}

		// ipbanned
		if ( m('system.ipbanned')->isbanned(IP) )
		{
			return $this->error(t('您的IP已被屏蔽，请联系管理员'));
		}

		if ( $this->insert($data,true) )
		{
			if ( c('guestbook.addmail') == 1 AND c('guestbook.addmail_sendto') )
			{
				//解析邮件内容
				$title 		= $this->parseMail(c('guestbook.addmail_title'),$data);
				$content 	= $this->parseMail(c('guestbook.addmail_content'),$data);

				$mail = new mail();
				$mail->sender = c('site.name');
				$mail->send(c('guestbook.addmail_sendto'), $title, $content);
			}

			return true;
		}

		return false;
	}


    /**
     * 回复
     *
     */
	public function reply($data, $id)
	{
		if ( empty($data['reply']) ) return $this->error(t('回复内容不能为空'));

		$data['replyuserid'] = zotop::user('id');
		$data['replytime'] = ZOTOP_TIME;

		if ( $this->update($data,$id) )
		{
			// 如果是回复留言
			if ( $data['email'] and c('guestbook.replymail') == 1 )
			{
				//解析邮件内容
				$title = $this->parseMail(c('guestbook.replymail_title'),$data);
				$content = $this->parseMail(c('guestbook.replymail_content'),$data);

				$mail = new mail();
				$mail->sender = c('site.name');
				$mail->send($data['email'], $title, $content);
			}

			return true;
		}

		return false;
	}

    /**
     * 解析邮件内容
     *
     */
	public function parseMail($str, $post)
	{
		$str = zotop::filter('guestbook.parsemail', $str, $post);

		$str = str_replace('{site}',' '.c('site.name').' ',$str);
		$str = str_replace('{url}',' <a href="'.c('site.url').'" target="_blank">'.c('site.url').'</a> ',$str);
		$str = str_replace('{name}',' '.$post['name'].' ',$str);
		$str = str_replace('{email}',' '.$post['email'].' ',$str);
		$str = str_replace('{createtime}','<b>'.format::date($post['createtime']).'</b>',$str);
		$str = str_replace('{content}','<div style="padding:20px">'.format::textarea($post['content']).'</div>',$str);
		$str = str_replace('{reply}','<div style="padding:20px">'.format::textarea($post['reply']).'</div>',$str);

		return $str;
	}

}
?>