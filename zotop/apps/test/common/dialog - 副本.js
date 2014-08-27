// artdialog
(function ($){
    var _count = 0;
    var _isIE6 = !("minWidth" in $("html")[0].style);
    var _isFixed = !_isIE6;
    function Popup() {
        this.destroyed = false;
        this.__popup = $("<div />").attr({
            tabindex:"-1"
        }).css({
            display:"none",
            position:"absolute",
            left:0,
            top:0,
            bottom:"auto",
            right:"auto",
            margin:0,
            padding:0,
            outline:0,
            border:"0 none",
            background:"transparent"
        }).html(this.innerHTML).appendTo("body");
        this.__backdrop = $("<div />");
        this.node = this.__popup[0];
        this.backdrop = this.__backdrop[0];
        _count++;
    }
    $.extend(Popup.prototype, {
        node:null,
        backdrop:null,
        fixed:false,
        destroyed:true,
        open:false,
        returnValue:"",
        autofocus:true,
        align:"bottom left",
        backdropBackground:"#000",
        backdropOpacity:.5,
        innerHTML:"",
        className:"ui-popup",
        show:function(anchor) {
            if (this.destroyed) {
                return this;
            }
            var that = this;
            var popup = this.__popup;
            this.__activeElement = this.__getActive();
            this.open = true;
            this.follow = anchor || this.follow;
            if (!this.__ready) {
                popup.addClass(this.className);
                if (this.modal) {
                    this.__lock();
                }
                if (!popup.html()) {
                    popup.html(this.innerHTML);
                }
                if (!_isIE6) {
                    $(window).on("resize", this.__onresize = function() {
                        that.reset();
                    });
                }
                this.__ready = true;
            }
            popup.addClass(this.className + "-show").attr("role", this.modal ? "alertdialog" :"dialog").css("position", this.fixed ? "fixed" :"absolute").show();
            this.__backdrop.show();
            this.reset().focus();
            this.__dispatchEvent("show");
            return this;
        },
        showModal:function() {
            this.modal = true;
            return this.show.apply(this, arguments);
        },
        close:function(result) {
            if (!this.destroyed && this.open) {
                if (result !== undefined) {
                    this.returnValue = result;
                }
                this.__popup.hide().removeClass(this.className + "-show");
                this.__backdrop.hide();
                this.open = false;
                this.blur();
                this.__dispatchEvent("close");
            }
            return this;
        },
        remove:function() {
            if (this.destroyed) {
                return this;
            }
            this.__dispatchEvent("beforeremove");
            if (Popup.current === this) {
                Popup.current = null;
            }
            this.__unlock();
            this.__popup.remove();
            this.__backdrop.remove();
            this.blur();
            if (!_isIE6) {
                $(window).off("resize", this.__onresize);
            }
            this.__dispatchEvent("remove");
            for (var i in this) {
                delete this[i];
            }
            return this;
        },
        reset:function() {
            var elem = this.follow;
            if (elem) {
                this.__follow(elem);
            } else {
                this.__center();
            }
            this.__dispatchEvent("reset");
            return this;
        },
        focus:function() {
            var node = this.node;
            var current = Popup.current;
            if (current && current !== this) {
                current.blur(false);
            }
            if (!$.contains(node, this.__getActive())) {
                var autofocus = this.__popup.find("[autofocus]")[0];
                if (!this._autofocus && autofocus) {
                    this._autofocus = true;
                } else {
                    autofocus = node;
                }
                this.__focus(autofocus);
            }
            Popup.current = this;
            this.__popup.addClass(this.className + "-focus");
            this.__zIndex();
            this.__dispatchEvent("focus");
            return this;
        },
        blur:function() {
            var activeElement = this.__activeElement;
            var isBlur = arguments[0];
            if (isBlur !== false) {
                this.__focus(activeElement);
            }
            this._autofocus = false;
            this.__popup.removeClass(this.className + "-focus");
            this.__dispatchEvent("blur");
            return this;
        },
        addEventListener:function(type, callback) {
            this.__getEventListener(type).push(callback);
            return this;
        },
        removeEventListener:function(type, callback) {
            var listeners = this.__getEventListener(type);
            for (var i = 0; i < listeners.length; i++) {
                if (callback === listeners[i]) {
                    listeners.splice(i--, 1);
                }
            }
            return this;
        },
        __getEventListener:function(type) {
            var listener = this.__listener;
            if (!listener) {
                listener = this.__listener = {};
            }
            if (!listener[type]) {
                listener[type] = [];
            }
            return listener[type];
        },
        __dispatchEvent:function(type) {
            var listeners = this.__getEventListener(type);
            if (this["on" + type]) {
                this["on" + type]();
            }
            for (var i = 0; i < listeners.length; i++) {
                listeners[i].call(this);
            }
        },
        __focus:function(elem) {
            try {
                if (this.autofocus && !/^iframe$/i.test(elem.nodeName)) {
                    elem.focus();
                }
            } catch (e) {}
        },
        __getActive:function() {
            try {
                var activeElement = document.activeElement;
                var contentDocument = activeElement.contentDocument;
                var elem = contentDocument && contentDocument.activeElement || activeElement;
                return elem;
            } catch (e) {}
        },
        __zIndex:function() {
            var index = Popup.zIndex++;
            this.__popup.css("zIndex", index);
            this.__backdrop.css("zIndex", index - 1);
            this.zIndex = index;
        },
        __center:function() {
            var popup = this.__popup;
            var $window = $(window);
            var $document = $(document);
            var fixed = this.fixed;
            var dl = fixed ? 0 :$document.scrollLeft();
            var dt = fixed ? 0 :$document.scrollTop();
            var ww = $window.width();
            var wh = $window.height();
            var ow = popup.width();
            var oh = popup.height();
            var left = (ww - ow) / 2 + dl;
            var top = (wh - oh) * 382 / 1e3 + dt;
            var style = popup[0].style;
            style.left = Math.max(parseInt(left), dl) + "px";
            style.top = Math.max(parseInt(top), dt) + "px";
        },
        __follow:function(anchor) {
            var $elem = anchor.parentNode && $(anchor);
            var popup = this.__popup;
            if (this.__followSkin) {
                popup.removeClass(this.__followSkin);
            }
            if ($elem) {
                var o = $elem.offset();
                if (o.left * o.top < 0) {
                    return this.__center();
                }
            }
            var that = this;
            var fixed = this.fixed;
            var $window = $(window);
            var $document = $(document);
            var winWidth = $window.width();
            var winHeight = $window.height();
            var docLeft = $document.scrollLeft();
            var docTop = $document.scrollTop();
            var popupWidth = popup.width();
            var popupHeight = popup.height();
            var width = $elem ? $elem.outerWidth() :0;
            var height = $elem ? $elem.outerHeight() :0;
            var offset = this.__offset(anchor);
            var x = offset.left;
            var y = offset.top;
            var left = fixed ? x - docLeft :x;
            var top = fixed ? y - docTop :y;
            var minLeft = fixed ? 0 :docLeft;
            var minTop = fixed ? 0 :docTop;
            var maxLeft = minLeft + winWidth - popupWidth;
            var maxTop = minTop + winHeight - popupHeight;
            var css = {};
            var align = this.align.split(" ");
            var className = this.className + "-";
            var reverse = {
                top:"bottom",
                bottom:"top",
                left:"right",
                right:"left"
            };
            var name = {
                top:"top",
                bottom:"top",
                left:"left",
                right:"left"
            };
            var temp = [ {
                top:top - popupHeight,
                bottom:top + height,
                left:left - popupWidth,
                right:left + width
            }, {
                top:top,
                bottom:top - popupHeight + height,
                left:left,
                right:left - popupWidth + width
            } ];
            var center = {
                left:left + width / 2 - popupWidth / 2,
                top:top + height / 2 - popupHeight / 2
            };
            var range = {
                left:[ minLeft, maxLeft ],
                top:[ minTop, maxTop ]
            };
            $.each(align, function(i, val) {
                if (temp[i][val] > range[name[val]][1]) {
                    val = align[i] = reverse[val];
                }
                if (temp[i][val] < range[name[val]][0]) {
                    align[i] = reverse[val];
                }
            });
            if (!align[1]) {
                name[align[1]] = name[align[0]] === "left" ? "top" :"left";
                temp[1][align[1]] = center[name[align[1]]];
            }
            className += align.join("-");
            that.__followSkin = className;
            if ($elem) {
                popup.addClass(className);
            }
            css[name[align[0]]] = parseInt(temp[0][align[0]]);
            css[name[align[1]]] = parseInt(temp[1][align[1]]);
            popup.css(css);
        },
        __offset:function(anchor) {
            var isNode = anchor.parentNode;
            var offset = isNode ? $(anchor).offset() :{
                left:anchor.pageX,
                top:anchor.pageY
            };
            anchor = isNode ? anchor :anchor.target;
            var ownerDocument = anchor.ownerDocument;
            var defaultView = ownerDocument.defaultView || ownerDocument.parentWindow;
            if (defaultView == window) {
                return offset;
            }
            var frameElement = defaultView.frameElement;
            var $ownerDocument = $(ownerDocument);
            var docLeft = $ownerDocument.scrollLeft();
            var docTop = $ownerDocument.scrollTop();
            var frameOffset = $(frameElement).offset();
            var frameLeft = frameOffset.left;
            var frameTop = frameOffset.top;
            return {
                left:offset.left + frameLeft - docLeft,
                top:offset.top + frameTop - docTop
            };
        },
        __lock:function() {
            var that = this;
            var popup = this.__popup;
            var backdrop = this.__backdrop;
            var backdropCss = {
                position:"fixed",
                left:0,
                top:0,
                width:"100%",
                height:"100%",
                overflow:"hidden",
                userSelect:"none",
                opacity:0,
                background:this.backdropBackground
            };
            popup.addClass(this.className + "-modal");
            Popup.zIndex = Popup.zIndex + 2;
            this.__zIndex();
            if (!_isFixed) {
                $.extend(backdropCss, {
                    position:"absolute",
                    width:$(window).width() + "px",
                    height:$(document).height() + "px"
                });
            }
            backdrop.css(backdropCss).animate({
                opacity:this.backdropOpacity
            }, 150).insertAfter(popup).attr({
                tabindex:"0"
            }).on("focus", function() {
                that.focus();
            });
        },
        __unlock:function() {
            if (this.modal) {
                this.__popup.removeClass(this.className + "-modal");
                this.__backdrop.remove();
                delete this.modal;
            }
        }
    });
    Popup.zIndex = 1024;
    Popup.current = null;
    var defaults = {
        content:'<span class="ui-dialog-loading">Loading..</span>',
        title:"",
        statusbar:"",
        button:null,
        ok:null,
        cancel:null,
        okValue:"确认",
        cancelValue:"取消",
        width:"",
        height:"",
        padding:"",
        skin:"",
        quickClose:false,
        cssUri:"../css/ui-dialog.css",
        innerHTML:'<div i="dialog" class="ui-dialog">' + '<div class="ui-dialog-arrow-a"></div>' + '<div class="ui-dialog-arrow-b"></div>' + '<table class="ui-dialog-grid">' + "<tr>" + '<td i="header" class="ui-dialog-header">' + '<button i="close" class="ui-dialog-close">&#215;</button>' + '<div i="title" class="ui-dialog-title"></div>' + "</td>" + "</tr>" + "<tr>" + '<td i="body" class="ui-dialog-body">' + '<div i="content" class="ui-dialog-content"></div>' + "</td>" + "</tr>" + "<tr>" + '<td i="footer" class="ui-dialog-footer">' + '<div i="statusbar" class="ui-dialog-statusbar"></div>' + '<div i="button" class="ui-dialog-button"></div>' + "</td>" + "</tr>" + "</table>" + "</div>"
    };
    var _version = "6.0.0";
    var _count = 0;
    var _expando = new Date() - 0;
    var _isIE6 = !("minWidth" in $("html")[0].style);
    var _isMobile = "createTouch" in document && !("onmousemove" in document) || /(iPhone|iPad|iPod)/i.test(navigator.userAgent);
    var _isFixed = !_isIE6 && !_isMobile;
    artDialog = function(options, ok, cancel) {
        var originalOptions = options = options || {};
        if (typeof options === "string" || options.nodeType === 1) {
            options = {
                content:options,
                fixed:!_isMobile
            };
        }
        options = $.extend(true, {}, artDialog.defaults, options);
        options._ = originalOptions;
        var id = options.id = options.id || _expando + _count;
        var api = artDialog.get(id);
        if (api) {
            return api.focus();
        }
        if (!_isFixed) {
            options.fixed = false;
        }
        if (options.quickClose) {
            options.modal = true;
            if (!originalOptions.backdropOpacity) {
                options.backdropOpacity = 0;
            }
        }
        if (!$.isArray(options.button)) {
            options.button = [];
        }
        if (ok !== undefined) {
            options.ok = ok;
        }
        if (options.ok) {
            options.button.push({
                id:"ok",
                value:options.okValue,
                callback:options.ok,
                autofocus:true
            });
        }
        if (cancel !== undefined) {
            options.cancel = cancel;
        }
        if (options.cancel) {
            options.button.push({
                id:"cancel",
                value:options.cancelValue,
                callback:options.cancel
            });
        }
        return artDialog.list[id] = new artDialog.create(options);
    };
    var popup = function() {};
    popup.prototype = Popup.prototype;
    var prototype = artDialog.prototype = new popup();
    artDialog.version = _version;
    artDialog.create = function(options) {
        var that = this;
        $.extend(this, new Popup());
        var $popup = $(this.node).html(options.innerHTML);
        this.options = options;
        this._popup = $popup;
        $.each(options, function(name, value) {
            if (typeof that[name] === "function") {
                that[name](value);
            } else {
                that[name] = value;
            }
        });
        if (options.zIndex) {
            Popup.zIndex = options.zIndex;
        }
        $popup.attr({
            "aria-labelledby":this._$("title").attr("id", "title:" + this.id).attr("id"),
            "aria-describedby":this._$("content").attr("id", "content:" + this.id).attr("id")
        });
        this._$("close").css("display", this.cancel === false ? "none" :"").on("click", function(event) {
            that._trigger("cancel");
            event.preventDefault();
        });
        this._$("dialog").addClass(this.skin);
        this._$("body").css("padding", this.padding);
        $popup.on("click", "[data-id]", function(event) {
            var $this = $(this);
            if (!$this.attr("disabled")) {
                that._trigger($this.data("id"));
            }
            event.preventDefault();
        });
        if (options.quickClose) {
            $(this.backdrop).on("onmousedown" in document ? "mousedown" :"click", function() {
                that._trigger("cancel");
            });
        }
        this._esc = function(event) {
            var target = event.target;
            var nodeName = target.nodeName;
            var rinput = /^input|textarea$/i;
            var isTop = Popup.current === that;
            var keyCode = event.keyCode;
            if (!isTop || rinput.test(nodeName) && target.type !== "button") {
                return;
            }
            if (keyCode === 27) {
                that._trigger("cancel");
            }
        };
        $(document).on("keydown", this._esc);
        this.addEventListener("remove", function() {
            $(document).off("keydown", this._esc);
            delete artDialog.list[this.id];
        });
        _count++;
        artDialog.oncreate(this);
        return this;
    };
    artDialog.create.prototype = prototype;
    $.extend(prototype, {
        content:function(html) {
            this._$("content").empty("")[typeof html === "object" ? "append" :"html"](html);
            return this.reset();
        },
        title:function(text) {
            this._$("title").text(text);
            this._$("header")[text ? "show" :"hide"]();
            return this;
        },
        width:function(value) {
            this._$("content").css("width", value);
            return this.reset();
        },
        height:function(value) {
            this._$("content").css("height", value);
            return this.reset();
        },
        button:function(args) {
            args = args || [];
            var that = this;
            var html = "";
            this.callbacks = {};
            this._$("footer")[args.length ? "show" :"hide"]();
            if (typeof args === "string") {
                html = args;
            } else {
                $.each(args, function(i, val) {
                    val.id = val.id || val.value;
                    that.callbacks[val.id] = val.callback;
                    html += "<button" + ' type="button"' + ' data-id="' + val.id + '"' + (val.disabled ? " disabled" :"") + (val.autofocus ? ' autofocus class="btn sbtn"' :'class="btn cancleBtn"') + ">" + val.value + "</button>";
                });
            }
            this._$("button").html(html);
            return this;
        },
        statusbar:function(html) {
            this._$("statusbar").html(html)[html ? "show" :"hide"]();
            return this;
        },
        _$:function(i) {
            return this._popup.find("[i=" + i + "]");
        },
        _trigger:function(id) {
            var fn = this.callbacks[id];
            return typeof fn !== "function" || fn.call(this) !== false ? this.close().remove() :this;
        }
    });
    artDialog.oncreate = $.noop;
    artDialog.getCurrent = function() {
        return Popup.current;
    };
    artDialog.get = function(id) {
        return id === undefined ? artDialog.list :artDialog.list[id];
    };
    artDialog.list = {};
    artDialog.defaults = defaults;
    var $window = $(window);
    var $document = $(document);
    var isTouch = "createTouch" in document;
    var html = document.documentElement;
    var isIE6 = !("minWidth" in html.style);
    var isLosecapture = !isIE6 && "onlosecapture" in html;
    var isSetCapture = "setCapture" in html;
    var types = {
        start:isTouch ? "touchstart" :"mousedown",
        over:isTouch ? "touchmove" :"mousemove",
        end:isTouch ? "touchend" :"mouseup"
    };
    var getEvent = isTouch ? function(event) {
        if (!event.touches) {
            event = event.originalEvent.touches.item(0);
        }
        return event;
    } :function(event) {
        return event;
    };
    var DragEvent = function() {
        this.start = $.proxy(this.start, this);
        this.over = $.proxy(this.over, this);
        this.end = $.proxy(this.end, this);
        this.onstart = this.onover = this.onend = $.noop;
    };
    DragEvent.types = types;
    DragEvent.prototype = {
        start:function(event) {
            event = this.startFix(event);
            $document.on(types.over, this.over).on(types.end, this.end);
            this.onstart(event);
            return false;
        },
        over:function(event) {
            event = this.overFix(event);
            this.onover(event);
            return false;
        },
        end:function(event) {
            event = this.endFix(event);
            $document.off(types.over, this.over).off(types.end, this.end);
            this.onend(event);
            return false;
        },
        startFix:function(event) {
            event = getEvent(event);
            this.target = $(event.target);
            this.selectstart = function() {
                return false;
            };
            $document.on("selectstart", this.selectstart).on("dblclick", this.end);
            if (isLosecapture) {
                this.target.on("losecapture", this.end);
            } else {
                $window.on("blur", this.end);
            }
            if (isSetCapture) {
                this.target[0].setCapture();
            }
            return event;
        },
        overFix:function(event) {
            event = getEvent(event);
            return event;
        },
        endFix:function(event) {
            event = getEvent(event);
            $document.off("selectstart", this.selectstart).off("dblclick", this.end);
            if (isLosecapture) {
                this.target.off("losecapture", this.end);
            } else {
                $window.off("blur", this.end);
            }
            if (isSetCapture) {
                this.target[0].releaseCapture();
            }
            return event;
        }
    };
    DragEvent.create = function(elem, event) {
        var $elem = $(elem);
        var dragEvent = new DragEvent();
        var startType = DragEvent.types.start;
        var noop = function() {};
        var className = elem.className.replace(/^\s|\s.*/g, "") + "-drag-start";
        var minX;
        var minY;
        var maxX;
        var maxY;
        var api = {
            onstart:noop,
            onover:noop,
            onend:noop,
            off:function() {
                $elem.off(startType, dragEvent.start);
            }
        };
        dragEvent.onstart = function(event) {
            var isFixed = $elem.css("position") === "fixed";
            var dl = $document.scrollLeft();
            var dt = $document.scrollTop();
            var w = $elem.width();
            var h = $elem.height();
            minX = 0;
            minY = 0;
            maxX = isFixed ? $window.width() - w + minX :$document.width() - w;
            maxY = isFixed ? $window.height() - h + minY :$document.height() - h;
            var offset = $elem.offset();
            var left = this.startLeft = isFixed ? offset.left - dl :offset.left;
            var top = this.startTop = isFixed ? offset.top - dt :offset.top;
            this.clientX = event.clientX;
            this.clientY = event.clientY;
            $elem.addClass(className);
            api.onstart.call(elem, event, left, top);
        };
        dragEvent.onover = function(event) {
            var left = event.clientX - this.clientX + this.startLeft;
            var top = event.clientY - this.clientY + this.startTop;
            var style = $elem[0].style;
            left = Math.max(minX, Math.min(maxX, left));
            top = Math.max(minY, Math.min(maxY, top));
            style.left = left + "px";
            style.top = top + "px";
            api.onover.call(elem, event, left, top);
        };
        dragEvent.onend = function(event) {
            var position = $elem.position();
            var left = position.left;
            var top = position.top;
            $elem.removeClass(className);
            api.onend.call(elem, event, left, top);
        };
        dragEvent.off = function() {
            $elem.off(startType, dragEvent.start);
        };
        if (event) {
            dragEvent.start(event);
        } else {
            $elem.on(startType, dragEvent.start);
        }
        return api;
    };
    artDialog.oncreate = function(api) {
        var options = api.options;
        var originalOptions = options._;
        var url = options.url;
        var oniframeload = options.oniframeload;
        var $iframe;
        if (url) {
            this.padding = options.padding = 0;
            $iframe = $("<iframe />");
            $iframe.attr({
                src:url,
                name:api.id,
                width:"100%",
                height:"100%",
                allowtransparency:"yes",
                frameborder:"no",
                scrolling:"no"
            }).on("load", function() {
                var test;
                try {
                    test = $iframe[0].contentWindow.frameElement;
                } catch (e) {}
                if (test) {
                    if (!options.width) {
                        api.width($iframe.contents().width());
                    }
                    if (!options.height) {
                        api.height($iframe.contents().height());
                    }
                }
                if (oniframeload) {
                    oniframeload.call(api);
                }
            });
            api.addEventListener("beforeremove", function() {
                $iframe.attr("src", "about:blank").remove();
            }, false);
            api.content($iframe[0]);
            api.iframeNode = $iframe[0];
        }
        if (!(originalOptions instanceof Object)) {
            var un = function() {
                api.close().remove();
            };
            for (var i = 0; i < frames.length; i++) {
                try {
                    if (originalOptions instanceof frames[i].Object) {
                        $(frames[i]).one("unload", un);
                        break;
                    }
                } catch (e) {}
            }
        }
        $(api.node).on(DragEvent.types.start, "[i=title]", function(event) {
            if (!api.follow) {
                api.focus();
                DragEvent.create(api.node, event);
            }
        });
    };
    artDialog.get = function(id) {
        if (id && id.frameElement) {
            var iframe = id.frameElement;
            var list = artDialog.list;
            var api;
            for (var i in list) {
                api = list[i];
                if (api.node.getElementsByTagName("iframe")[0] === iframe) {
                    return api;
                }
            }
        } else if (id) {
            return artDialog.list[id];
        }
    };
}(jQuery));

// 扩展artdialog
(function ($){

	/*
	 * 修改button 及 扩展shake摇头方法
	*/
	$.extend(artDialog.create.prototype,{

		button:function(args, callback) {
            args = args || [];
            var that = this;
            var html = "";
            this.callbacks = {};
            this._$("footer")[args.length ? "show" :"hide"]();
            if (typeof args === "string") {
                html = args;
            } else {
                $.each(args, function(i, val) {
                    val.id = val.id || val.value;
                    that.callbacks[val.id] = val.callback;
					html += '<label data-id="' + val.id + '"' + (val.disabled ? " disabled" :"") + (val.autofocus ? ' autofocus class="btn btn-highlight"' :'class="btn"') + '>';
					html += '<button type="button"'+ (val.autofocus ? ' autofocus' :'') +'>' + val.value + '</button>';
					html += '</label>';
                });
            }
            this._$("button").html(html);
            return this;
		},
		shake: function(){
			var timerId = null;
			var style = this._popup[0].style,
				p = [4, 8, 4, 0, -4, -8, -4, 0],
				fx = function () {
					style.marginLeft = p.shift() + 'px';
					if (p.length <= 0) {
						style.marginLeft = 0;
						clearInterval(timerId);
					};
				};
			p = p.concat(p.concat(p.concat(p)));
			timerId = setInterval(fx, 8);
			return this;
		}
	});

	/**
	 * 气泡
	 * @param   {Json}     (可选) 参数
	 * @param   {Bool}     (可选) 缓存内容
	 */
	$.fn.dialog = function (options){
		options.align = options.align || $(this).data('align');
		return this.each(function(){
			return top.artDialog(options).show(this);
		});
	}

	/**
	 * 对话框
	 * @param  options  参数
	 * @param  modal {Bool} 模型
	 */
	$.dialog = function (options, modal){

		if ( options ){
			if (typeof options === "string") {
				return top.artDialog.get(options);
			}else{
				return top.artDialog(options)[modal ? "showModal" :"show"]();
			}
		}

		return top.artDialog.get(window);
	}

	/**
	 * 消息提示
	 * @param   {Json}     (可选) 参数
	 * @param   {Bool}     (可选) 缓存内容
	 */
	$.msg = function(state, content, onclose, time){

		var options = {};

		if( typeof (state) == "object" ) {
			options = $.extend(options, {'state':state.state,'content':state.content,'onclose':function(){
				if( state.url ) parent.location.href = state.url;
			},'time':state.time});
		}else{
			options = $.extend(options, {'state':state,'content':content,'onclose':onclose,'time':time});
		}

		if( typeof(options.state) == 'boolean' ) options.state = options.state ? 'success' : 'error';

		if( parseInt(options.time) == 0) options.quickClose = true;

		options = $.extend({id:'message',title:false,padding:0,fixed:true,resize:false,skin:'dialog-message',time:2000},options);

		options.content = '<div class="d-msg '+ options.state +'"><b class="icon icon-'+ options.state +' d-msg-icon"></b><div class="d-msg-content">'+ options.content +' <a href="javascript:;" class="close" i="close">&#215;</a></div></div>';

		var dialog = $.dialog(options);

		if( parseInt(options.time) > 0){
			setTimeout(function(){dialog.close().remove();}, parseInt(options.time)*1000);
		}

		return dialog;
	}

	$.extend(artDialog.defaults, {
		cssUri : '',
		padding:5,
        backdropOpacity:.2,
		loading: '操作正在执行，请稍候……'
	});

}(jQuery));

