/*
 * zotop javascript library
 *
 * @version: 2.1
 * @Requires jQuery v1.3.2 or later
 * @Copyright 2009-2011 (http://www.zotop.com/)
 */

var zotop = window.zotop || {};

/*
 * 格式化字符串,支持argsay和object，string三种种数据源
 *
 * zotop.format('test string {1} or {2}',['value1','value2'])
 * zotop.format('test string {name1} or {name2}',{name1:'value1',name2:'value2'})
 * zotop.format('test string %s or %s','value1','value2')
 *
 * @param singular
 *   待格式化的字符串
 * @param args
 *   替换参数
 *		1，数组，如：['value1','value2']
 *		1，JSON，如：{name1:'value1',name2:'value2'}
 *		1，字符串，允许使用多个字符串参数
 * @return
 *   格式化后的字符串
 */
zotop.format = function(str, args){
	var tmp;
	if(args.constructor == Array){
		for (var i = 0; i < args.length; i++) {
			var re = new RegExp('\\{' + (i) + '\\}', 'gm');
			tmp = String(args[i]).replace(/\$/g, "$$$$");
			str = str.replace(re, tmp);
		}
	}else if(args.constructor == Object){
		for (var elem in args) {
			var re = new RegExp('\\{' + elem + '\\}', 'gm');
			tmp = String(args[elem]).replace(/\$/g, "$$$$");
			str = str.replace(re, tmp);
		}
	}else{
		for(i=1;i<=arguments.length;i++){
			str = str.replace("%s",arguments[i]);
		}
	}
	return str;
}

/**
 * 格式化文件大小
 *
 * @method formatSize
 * @param {Number} size Size to format as string.
 * @return {String} Formatted size string.
 */
zotop.formatsize = function(size) {
	if (size === undefined || /\D/.test(size)) {
		return zotop.t('N/A');
	}

	if (size > 1073741824) {
		return Math.round(size / 1073741824, 1) + " GB";
	}

	if (size > 1048576) {
		return Math.round(size / 1048576, 1) + " MB";
	}

	if (size > 1024) {
		return Math.round(size / 1024, 1) + " KB";
	}

	return size + " b";
}

/*
 * 翻译字符串，如果存在格式化参数则格式化翻译后的字符串
 *
 * @param str 待翻译的字符串
 * @param args 替换参数，详细参见zotop.format
 * @return 格式化后的字符串
 */
zotop.t = function(str,args){
	if(args){
		str = zotop.format(str, args);
	}
	return str;
}

/*
 * 调试函数，在控制台输出
 *
 * @param obj 任意数据或者对象
 * @return null
 */
zotop.debug = function(obj)
{
    if (typeof console != 'undefined')
    {
        console.log(obj);
    }
}

zotop.debug('Welcome to use zotop, our website: http://www.zotop.com');

/*
 * 扩展Array，在指定的位置插入数据
 *
 * @param index 索引位置
 * @param obj 插入的数据
 * @return array
 */
Array.prototype.insert = function(index, obj) {
	if ( isNaN(index) || index < 0 || index > this.length ) {
		this.push(obj)
	} else {
		var c = this.slice(index),e;
		this[index] = obj;
		for (e = 0; e < c.length; e++) {
			this[index + 1 + e] = c[e];
		}
	}
};

