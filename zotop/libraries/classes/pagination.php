<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 页面组件
 *
 * @copyright  (c)2009 zotop team
 * @package    zotop.ui
 * @author     zotop team
 * @license    http://zotop.com/license.html
 */
class pagination
{
    public $total; // 总行数
	public $pagesize; // 列表每页显示行数    
    public $showpage = 9 ; // 分页栏每页显示的页码数

	public $first	= '1...' ; // 首页
	public $prev	= '<<' ; // 上一页
	public $next	= '>>' ; // 下一页
	public $last	= '...$totalpage' ; // 末页
	public $theme	= '<ul class="pagination">$prev $first $pages $last $next</ul>';  // 显示模版


	public $param   = 'page'; // 分页参数名
    public $url     = ''; // 当前链接URL
    public $page	 = 1; // 当前页	
    
    /**
     * 初始化控制器
     * 
     */
    public function __construct()
    {
		$this->prev = t('上页');
		$this->next = t('下页');
		$this->page = empty($_GET[$this->param]) ? 1 : intval($_GET[$this->param]);
    }   
	

    /**
     * 输出一个分页
     * 
     * @param int $total 总条数
	 * @param int $page 页码
	 * @param int $pagesize 每页显示行数
	 * @param string $template 分页模板
	 * @param string $url url链接
     * @return mixed
     */
	public static function instance($total, $pagesize, $page=null, $theme='', $url=null)
	{
		$p = new pagination();

		if( $total and intval($total)>0 ) $p->total = $total;
		if( $pagesize and intval($pagesize)>0 ) $p->pagesize = $pagesize;		
		if( $page and intval($page)>0 ) $p->page = $page;
		if( $theme ) $p->theme = $theme;
		if( $url ) $p->url = $url;
		
		return $p->render();
	}

    /**
     * 分页url计算
     * 
     * @param int $page 页码
     * @return mixed
     */
	public function url($page=1)
	{
		if ( empty($this->url) )
		{
			return url::set_query_arg(array($this->param => $page));
		}
		
		$url = str_replace('[page]',$page,$this->url);

		return $url;
	}

    /**
     * 渲染一个分页
     * 
     * @return mixed
     */
	public function render()
	{
        $total    = intval($this->total); //总条数
        $pagesize = intval($this->pagesize); //每页显示条数
        $page     = intval($this->page); //当前页码
        $showpage = intval($this->showpage); //显示页码数,如值为10的时候，一共显示10个页码
        $offset   = ceil($showpage/2)-1; //页码偏移数
		
		if (  $total == 0 ||  $pagesize == 0 ) return '';

		//计算全部页数
		$totalpage = @ceil($total / $pagesize);

		//当前页码
		$page = $page <=0 ? 1 : $page;
		$page = $page > $totalpage ? $totalpage : $page;		
		
		if ( $showpage > $totalpage )
		{
			$from = 1;
			$to = $totalpage;
		}
		else
		{
			$from = $page - $offset;
			$to = $from + $showpage -1;

			if ( $from < 1 )
			{
				$from = 1;
				$to = $page + 1 - $from;
				if ( $to - $from < $showpage )
				{
					$to = $showpage;
				}
			}
			elseif ( $to > $totalpage )
			{
				$from = $totalpage - $showpage + 1;
                $to = $totalpage;
			}

		}

		for($i = $from; $i <= $to; $i++)
		{
			$pages .= $i == $page ? '<li class="active"><a href="javascript:;">'.$i.'</a></li>' : '<li><a href="'.$this->url($i).'">'.$i.'</a></li>';
        }
		
		//上下翻页
		$prev = $page - 1;
		$next = $page + 1;

        $prevPage  = $prev > 0 ? '<li><a class="prev" href="'.$this->url($prev).'">'.$this->prev.'</a></li>' : '';
        
        $nextPage  = $next <= $totalpage ? '<li><a class="next" href="'.$this->url($next).'">'.$this->next.'</a></li>' : '';
        
        $firstPage = $from == 1 ? '' : '<li><a class="first" href="'.$this->url(1).'">'.$this->first.'</a></li>';
        
        $lastPage  = $to == $totalpage ? '' : '<li><a class="last" href="'.$this->url($totalpage).'">'.$this->last.'</a></li>';


		$str = str_ireplace(
				array('$total','$pages','$page','$first','$prev','$next','$last','$totalpage'),
				array($total,$pages,$page,$firstPage,$prevPage,$nextPage,$lastPage,$totalpage),
				$this->theme
		);

		return $str;
	}
}
?>