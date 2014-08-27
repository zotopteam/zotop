<?php
defined('ZOTOP') or die('No direct access allowed.');
/*
* 网站地图
*
* @package		sitemap
* @version		1.0
* @author		zotop team
* @copyright	zotop team
* @license		http://www.zotop.com
*/
class sitemap
{
    public $charset = 'utf-8';
    public $items = array();

    private $header = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
    private $footer = "</urlset>\n";

    /**
     * 初始化函数，添加网站首页
     * 
     * @return void
     */
    public function __construct()
    {
        //默认添加站点首页
        $this->add(ZOTOP_URL, gmdate('Y-m-d'), 'always', '1.0');
    }

    /**
     * 添加一条
     * 
     * @param string $loc URL链接地址,长度不得超过256字节
     * @param string $lastmod 链接的最后更新时间
     * @param string $changefreq 链接可能会出现的更新频率
     * @param string $priority 指定此链接相对于其他链接的优先权比值，此值定于0.0-1.0之间
     * @return void
     */
    public function add($loc, $lastmod = '', $changefreq = '', $priority = '')
    {
        $this->items[] = array(
            'loc' 			=> $loc,
            'lastmod' 		=> $lastmod,
            'changefreq' 	=> $changefreq,
            'priority' 		=> $priority
        );
    }

    /**
     * 生成网站地图xml
     * 
     * @return string
     */
    public function build()
    {
        // hook
        zotop::run('sitemap.items', $this);

        // 拼接xml
        $map = $this->header . "\n";

        foreach ($this->items as $item)
        {
            $item['loc'] = htmlentities($item['loc'], ENT_QUOTES);

            $map .= "\t<url>\n\t\t<loc>" . $item['loc'] . "</loc>\n";

            if (!empty($item['lastmod']))
            {
                $map .= "\t\t<lastmod>" . $item['lastmod'] . "</lastmod>\n";
            }

            if (!empty($item['changefreq']))
            {
                $map .= "\t\t<changefreq>" . $item['changefreq'] . "</changefreq>\n";
            }

            if (!empty($item['priority']))
            {
                $map .= "\t\t<priority>" . $item['priority'] . "</priority>\n";
            }

            $map .= "\t</url>\n";
        }

        $map .= $this->footer . "\n";

        return $map;
    }

    /**
     * 在根目录下创建网站地图 sitemap.xml 文件
     * 
     * @return bool
     */
    public function create()
    {
        if ( file::put($this->path(), $this->build()) )
        {
            return true;
        }

        return false;
    }

    /**
     * 检查 sitemap.xml 文件是否存在
     * 
     * @return bool
     */
    public function exists()
    {
    	return file::exists($this->path());
    }

    /**
     * 获取网站地图 sitemap.xml 文件的 路径
     * 
     * @return bool
     */
    public function path()
    {
    	return ZOTOP_PATH . DS . 'sitemap.xml';
    }    

    /**
     * 获取网站地图 sitemap.xml 文件的 URL
     * 
     * @return bool
     */
    public function url()
    {
    	return request::host() . ZOTOP_URL . '/sitemap.xml';
    }
}
?>