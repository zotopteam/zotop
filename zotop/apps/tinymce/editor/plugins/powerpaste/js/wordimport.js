/**
 * Word Import JavaScript Library
 * Copyright (c) 2013-2015 Ephox Corp. All rights reserved.
 * This software is provided "AS IS," without a warranty of any kind.
 */
function com_ephox_keurig_Keurig(){var Pb='',Qb='" for "gwt:onLoadErrorFn"',Rb='" for "gwt:onPropertyErrorFn"',Sb='"><\/script>',Tb='#',Ub='&',Vb='/',Wb='6403704AA3ED9AA55F14B8A45C378A31',Xb=':',Yb=':1',Zb=':2',$b=':3',_b=':4',ac=':5',bc=':6',cc=':7',dc=':8',ec=':9',fc='<script id="',gc='=',hc='?',ic='Bad handler "',jc='DOMContentLoaded',kc='SCRIPT',lc='Single-script hosted mode not yet implemented. See issue ',mc='Unexpected exception in locale detection, using default: ',nc='_',oc='__gwt_Locale',pc='__gwt_marker_com.ephox.keurig.Keurig',qc='base',rc='clear.cache.gif',sc='com.ephox.keurig.Keurig',tc='content',uc='default',vc='en',wc='gecko',xc='gecko1_8',yc='gwt.codesvr=',zc='gwt.hosted=',Ac='gwt.hybrid',Bc='gwt:onLoadErrorFn',Cc='gwt:onPropertyErrorFn',Dc='gwt:property',Ec='http://code.google.com/p/google-web-toolkit/issues/detail?id=2079',Fc='ie10',Gc='ie8',Hc='ie9',Ic='img',Jc='locale',Kc='locale=',Lc='meta',Mc='msie',Nc='name',Oc='safari',Pc='unknown',Qc='user.agent',Rc='webkit';var k=Pb,l=Qb,m=Rb,n=Sb,o=Tb,p=Ub,q=Vb,r=Wb,s=Xb,t=Yb,u=Zb,v=$b,w=_b,A=ac,B=bc,C=cc,D=dc,F=ec,G=fc,H=gc,I=hc,J=ic,K=jc,L=kc,M=lc,N=mc,O=nc,P=oc,Q=pc,R=qc,S=rc,T=sc,U=tc,V=uc,W=vc,X=wc,Y=xc,Z=yc,$=zc,_=Ac,ab=Bc,bb=Cc,cb=Dc,db=Ec,eb=Fc,fb=Gc,gb=Hc,hb=Ic,ib=Jc,jb=Kc,kb=Lc,lb=Mc,mb=Nc,nb=Oc,ob=Pc,pb=Qc,qb=Rc;var rb=window,sb=document,tb,ub,vb=k,wb={},xb=[],yb=[],zb=[],Ab=0,Bb,Cb;if(!rb.__gwt_stylesLoaded){rb.__gwt_stylesLoaded={}}if(!rb.__gwt_scriptsLoaded){rb.__gwt_scriptsLoaded={}}function Db(){var b=false;try{var c=rb.location.search;return (c.indexOf(Z)!=-1||(c.indexOf($)!=-1||rb.external&&rb.external.gwtOnLoad))&&c.indexOf(_)==-1}catch(a){}Db=function(){return b};return b}
function Eb(){if(tb&&ub){tb(Bb,T,vb,Ab)}}
function Fb(){var e,f=Q,g;sb.write(G+f+n);g=sb.getElementById(f);e=g&&g.previousSibling;while(e&&e.tagName!=L){e=e.previousSibling}function h(a){var b=a.lastIndexOf(o);if(b==-1){b=a.length}var c=a.indexOf(I);if(c==-1){c=a.length}var d=a.lastIndexOf(q,Math.min(c,b));return d>=0?a.substring(0,d+1):k}
;if(e&&e.src){vb=h(e.src)}if(vb==k){var i=sb.getElementsByTagName(R);if(i.length>0){vb=i[i.length-1].href}else{vb=h(sb.location.href)}}else if(vb.match(/^\w+:\/\//)){}else{var j=sb.createElement(hb);j.src=vb+S;vb=h(j.src)}if(g){g.parentNode.removeChild(g)}}
function Gb(){var b=document.getElementsByTagName(kb);for(var c=0,d=b.length;c<d;++c){var e=b[c],f=e.getAttribute(mb),g;if(f){if(f==cb){g=e.getAttribute(U);if(g){var h,i=g.indexOf(H);if(i>=0){f=g.substring(0,i);h=g.substring(i+1)}else{f=g;h=k}wb[f]=h}}else if(f==bb){g=e.getAttribute(U);if(g){try{Cb=eval(g)}catch(a){alert(J+g+m)}}}else if(f==ab){g=e.getAttribute(U);if(g){try{Bb=eval(g)}catch(a){alert(J+g+l)}}}}}}
function Hb(a,b){return b in xb[a]}
function Ib(a){var b=wb[a];return b==null?null:b}
function Jb(a,b){var c=zb;for(var d=0,e=a.length-1;d<e;++d){c=c[a[d]]||(c[a[d]]=[])}c[a[e]]=b}
function Kb(a){var b=yb[a](),c=xb[a];if(b in c){return b}var d=[];for(var e in c){d[c[e]]=e}if(Cb){Cb(a,d,b)}throw null}
yb[ib]=function(){var b=null;var c=V;try{if(!b){var d=location.search;var e=d.indexOf(jb);if(e>=0){var f=d.substring(e+7);var g=d.indexOf(p,e);if(g<0){g=d.length}b=d.substring(e+7,g)}}if(!b){b=Ib(ib)}if(!b){b=rb[P]}if(b){c=b}while(b&&!Hb(ib,b)){var h=b.lastIndexOf(O);if(h<0){b=null;break}b=b.substring(0,h)}}catch(a){alert(N+a)}rb[P]=c;return b||V};xb[ib]={'default':0,en:1};yb[pb]=function(){var b=navigator.userAgent.toLowerCase();var c=function(a){return parseInt(a[1])*1000+parseInt(a[2])};if(function(){return b.indexOf(qb)!=-1}())return nb;if(function(){return b.indexOf(lb)!=-1&&sb.documentMode>=10}())return eb;if(function(){return b.indexOf(lb)!=-1&&sb.documentMode>=9}())return gb;if(function(){return b.indexOf(lb)!=-1&&sb.documentMode>=8}())return fb;if(function(){return b.indexOf(X)!=-1}())return Y;return ob};xb[pb]={gecko1_8:0,ie10:1,ie8:2,ie9:3,safari:4};com_ephox_keurig_Keurig.onScriptLoad=function(a){com_ephox_keurig_Keurig=null;tb=a;Eb()};if(Db()){alert(M+db);return}Fb();Gb();try{var Lb;Jb([V,Y],r);Jb([V,eb],r+t);Jb([V,fb],r+u);Jb([V,gb],r+v);Jb([V,nb],r+w);Jb([W,Y],r+A);Jb([W,eb],r+B);Jb([W,fb],r+C);Jb([W,gb],r+D);Jb([W,nb],r+F);Lb=zb[Kb(ib)][Kb(pb)];var Mb=Lb.indexOf(s);if(Mb!=-1){Ab=Number(Lb.substring(Mb+1))}}catch(a){return}var Nb;function Ob(){if(!ub){ub=true;Eb();if(sb.removeEventListener){sb.removeEventListener(K,Ob,false)}if(Nb){clearInterval(Nb)}}}
if(sb.addEventListener){sb.addEventListener(K,function(){Ob()},false)}var Nb=setInterval(function(){if(/loaded|complete/.test(sb.readyState)){Ob()}},50)}
com_ephox_keurig_Keurig();(function () {var $gwt_version = "2.6.1";var $wnd = window;var $doc = $wnd.document;var $moduleName, $moduleBase;var $stats = $wnd.__gwtStatsEvent ? function(a) {$wnd.__gwtStatsEvent(a)} : null;var $strongName = '6403704AA3ED9AA55F14B8A45C378A31';function A(){}
function Z(){}
function Xo(){}
function ub(){}
function Og(){}
function Yg(){}
function gh(){}
function Bh(){}
function Dh(){}
function jk(){}
function nk(){}
function rk(){}
function vk(){}
function zk(){}
function Kk(){}
function _n(){}
function yg(a){}
function ac(){Kb()}
function nd(){ld()}
function qd(){ld()}
function xd(){ud()}
function kf(){jf()}
function qf(){pf()}
function wf(){vf()}
function Ff(){Ef()}
function Pf(){Of()}
function vh(){kh()}
function db(){bb(this)}
function Tl(){Rl(this)}
function am(){Xl(this)}
function bm(){Xl(this)}
function Nm(a){this.b=a}
function $m(a){this.b=a}
function W(a){this.b=a}
function Hc(a){this.b=a}
function Mc(a){this.b=a}
function Sn(a){this.b=a}
function un(a){this.c=a}
function Xd(a,b){a.i=b}
function zh(a,b){a.b+=b}
function Yd(a,b){a.h=a.i=b}
function Jc(a,b){zn(a.b,b)}
function Eg(){return Ag}
function Rl(a){a.b=new Bh}
function Xl(a){a.b=new Bh}
function nc(){this.b=new Eo}
function Kc(){this.b=new Gn}
function Eo(){this.b=new Gn}
function gg(){gg=Xo;fg=new A}
function xb(){xb=Xo;wb=new ac}
function Fc(){Dc();return tc}
function dg(){Uj().w(this)}
function dl(){dg.call(this)}
function Gk(){dg.call(this)}
function Vk(){dg.call(this)}
function Xk(){dg.call(this)}
function $k(){dg.call(this)}
function fo(){dg.call(this)}
function po(){dg.call(this)}
function Hk(a){eg.call(this,a)}
function Yk(a){eg.call(this,a)}
function _k(a){eg.call(this,a)}
function hl(a){Yk.call(this,a)}
function im(a){eg.call(this,a)}
function be(a){this.b=yl(a+Cp)}
function jc(){this.c=(Dc(),xc)}
function Ro(a,b,c){Em(a.b,b,c)}
function Tf(a,b){a.b[a.c++]=b}
function Vd(a,b){return a.i+=b}
function Qd(a,b){return a.f[b]}
function Jo(a,b){return a.e[b]}
function Bm(b,a){return b.f[Rp+a]}
function ek(b,a){return b.exec(a)}
function bk(a){return new _j[a]}
function ck(){return !!$stats}
function En(a){return Fh(a.b,a.c)}
function sn(a){return a.b<a.c.J()}
function Rd(a,b){return a.f[b]<=32}
function Hd(a,b){return Id(a,b,a.k)}
function Ld(a,b){return Md(a,b,a.k)}
function Sg(a){return Wg((Uj(),a))}
function No(a){Oo.call(this,a,0)}
function dn(a,b){this.c=a;this.b=b}
function ko(a,b){this.b=a;this.c=b}
function Sl(a,b){zh(a.b,b);return a}
function Yl(a,b){zh(a.b,b);return a}
function Do(a,b){zn(a.b,b);return b}
function Mn(a,b,c){a.splice(b,c)}
function Zl(a,b){return ml(a.b.b,b)}
function Gg(a,b){return Ch(a,b,null)}
function Eh(a){return Fh(a,a.length)}
function Xh(a){return a==null?null:a}
function Dm(b,a){return Rp+a in b.f}
function pl(b,a){return b.indexOf(a)}
function Hg(a){$wnd.clearTimeout(a)}
function eg(a){this.f=a;Uj().w(this)}
function Gn(){this.b=Ih(Nj,_o,0,0,0)}
function Ul(a){Rl(this);zh(this.b,a)}
function cm(a){Xl(this);zh(this.b,a)}
function T(a,b){L();this.c=a;this.b=b}
function Oc(a,b){return b<256&&a.b[b]}
function Rh(a,b){return a.cM&&a.cM[b]}
function Ko(a){return fk(a.c,a.b,xp)}
function Bl(a){return Ih(Pj,_o,1,a,0)}
function Tg(a){return parseInt(a)||-1}
function Dg(a){return a.$H||(a.$H=++ug)}
function Qh(a,b){return a.cM&&!!a.cM[b]}
function ad(a,b){return a.b[b>=128?0:b]}
function gk(a,b){return new RegExp(a,b)}
function Wh(a){return a.tM==Xo||Qh(a,1)}
function ml(b,a){return b.charCodeAt(a)}
function mn(a,b){(a<0||a>=b)&&pn(a,b)}
function Uh(a,b){return a!=null&&Qh(a,b)}
function fk(c,a,b){return a.replace(c,b)}
function ql(c,a,b){return c.indexOf(a,b)}
function rl(b,a){return b.lastIndexOf(a)}
function Zn(){Zn=Xo;Yn=new _n}
function Lg(){Lg=Xo;Kg=new Og}
function Wo(){Wo=Xo;Vo=new To}
function Ml(){Ml=Xo;Jl={};Ll={}}
function he(){he=Xo;ge=yl('class=')}
function We(){We=Xo;Ve=yl(Ap);Ue=yl(Bp)}
function Rc(){Rc=Xo;Qc=yl('<v:imagedata ')}
function ld(){ld=Xo;fd();kd=yl('style=')}
function Se(){Se=Xo;Re=yl('/*');Qe=yl('*/')}
function Tc(a,b){return Td(a,b)&&Fd(a,62)}
function xl(c,a,b){return c.substr(a,b-a)}
function $l(a,b,c){return Ah(a.b,b,c,pp),a}
function _l(a,b,c,d){Ah(a.b,b,c,d);return a}
function Bn(a,b){mn(b,a.c);return a.b[b]}
function Io(a){a.e=ek(a.c,a.b);return !!a.e}
function Qk(a){var b=_j[a.d];a=null;return b}
function kg(a){return a==null?null:a.name}
function jg(a){return a==null?null:a.message}
function Uo(a,b){return a!=null?a[b]:null}
function xg(a,b,c){return a.apply(b,c);var d}
function sl(c,a,b){return c.lastIndexOf(a,b)}
function Fl(a){return String.fromCharCode(a)}
function To(){this.b=new io;new io;new io}
function Ee(a){Ce();this.b=ye;this.b=a?ze:ye}
function Ec(a,b,c){this.d=a;this.c=c;this.b=b}
function Wd(a,b,c){a.f=b;a.k=c;a.h=a.i=0}
function Nn(a,b,c,d){a.splice(b,c,d)}
function Uf(a,b,c,d){gm(b,c,a.b,a.c,d);a.c+=d}
function zn(a,b){Kh(a.b,a.c++,b);return true}
function Xg(){try{null.a()}catch(a){return a}}
function pg(a){var b;return b=a,Wh(b)?b.cZ:Fi}
function Rk(a){return typeof a=='number'&&a>0}
function wl(b,a){return b.substr(a,b.length-a)}
function Vh(a){return a!=null&&a.tM!=Xo&&!Qh(a,1)}
function ve(){ve=Xo;ue=yl(qp);te=yl('<\/span')}
function Oe(){Oe=Xo;Ne=yl('xmlns');Me=yl('<html')}
function L(){L=Xo;J=(Zn(),Zn(),Yn);K=new W(J)}
function Nh(){Nh=Xo;Lh=[];Mh=[];Oh(new Dh,Lh,Mh)}
function kh(){kh=Xo;Error.stackTraceLimit=128}
function Wf(a){this.b=Ih(Kj,dp,-1,a,1);this.c=0}
function Ah(a,b,c,d){a.b=xl(a.b,0,b)+d+wl(a.b,c)}
function Il(a,b){Al(a.length,b);return Dl(a,0,b)}
function Pg(a,b){!a&&(a=[]);a[a.length]=b;return a}
function Ug(a,b){a.length>=b&&a.splice(0,b);return a}
function og(a,b){var c;return c=a,Wh(c)?c.eQ(b):c===b}
function qg(a){var b;return b=a,Wh(b)?b.hC():Dg(b)}
function P(a,b){L();return new T(new W(a),new W(b))}
function Em(a,b,c){return !b?Gm(a,c):Fm(a,b,c,~~Dg(b))}
function $c(a,b){var c;c=a.f;Wd(a,b.b,b.c);b.b=c;b.c=0}
function jl(a,b,c){this.b=Tp;this.e=a;this.c=b;this.d=c}
function pn(a,b){throw new _k('Index: '+a+', Size: '+b)}
function ee(){ee=Xo;ce=new Pc(Dp);de=new Pc(' \t\r\n')}
function jf(){jf=Xo;pe();ve();df();hf=new Pc('<\n\r')}
function vf(){vf=Xo;pe();ve();df();he();uf=new Pc('<c\n\r')}
function df(){df=Xo;bf=new Pc(' >\r\n\t');cf=new Pc(Dp)}
function ud(){ud=Xo;fd();sd=yl('\n\r{');td=yl(' \t,')}
function Bd(){Bd=Xo;zd=yl(Ap);yd=yl(Bp);Se();Ad=new xd}
function Pl(){if(Kl==256){Jl=Ll;Ll={};Kl=0}++Kl}
function bb(a){if(!ab){ab=true;Wo();Ro(Vo,di,a);cb(a)}}
function Fg(a){$wnd.setTimeout(function(){throw a},0)}
function Ig(){return Gg(function(){tg!=0&&(tg=0);wg=-1},10)}
function ho(a,b){return Xh(a)===Xh(b)||a!=null&&og(a,b)}
function Fo(a,b){return Xh(a)===Xh(b)||a!=null&&og(a,b)}
function ul(c,a,b){b=Cl(b);return c.replace(RegExp(a,fq),b)}
function nl(a,b){if(!Uh(b,1)){return false}return String(a)==b}
function Sh(a,b){if(a!=null&&!Rh(a,b)){throw new Vk}return a}
function tn(a){if(a.b>=a.c.J()){throw new po}return a.c.T(a.b++)}
function fm(a){_k.call(this,'String index out of range: '+a)}
function Lo(a,b){var c;this.b=b;this.c=gk((c=a.b,c.source),fq)}
function zb(a,b){xb();this.b=rb(new ub,a);this.c=b;this.d=true}
function yn(a,b,c){(b<0||b>a.c)&&pn(b,a.c);Nn(a.b,b,0,c);++a.c}
function Ih(a,b,c,d,e){var f;f=Hh(e,d);Jh(a,b,c,f);return f}
function Mk(a,b,c){var d;d=new Kk;d.e=a+b;Rk(c)&&Sk(c,d);return d}
function Jh(a,b,c,d){Nh();Ph(d,Lh,Mh);d.cZ=a;d.cM=b;d.qI=c;return d}
function ol(a,b,c,d){var e;for(e=0;e<b;++e){c[d++]=a.charCodeAt(e)}}
function Bg(a,b,c){var d;d=zg();try{return xg(a,b,c)}finally{Cg(d)}}
function Gm(a,b){var c;c=a.c;a.c=b;if(!a.d){a.d=true;++a.e}return c}
function Gh(a,b){var c,d;c=a;d=Hh(0,b);Jh(c.cZ,c.cM,c.qI,d);return d}
function Vf(a){for(;a.c>0;a.c--){if(a.b[a.c-1]>32){break}}}
function rg(a){return a.toString?a.toString():'[JavaScriptObject]'}
function Yh(a){return ~~Math.max(Math.min(a,2147483647),-2147483648)}
function Cg(a){a&&Ng((Lg(),Kg));--tg;if(a){if(wg!=-1){Hg(wg);wg=-1}}}
function pf(){pf=Xo;me();_e();Ke();Oe();Rc();of=new Pc('<x\n\r')}
function _e(){_e=Xo;$e=yl('<![if');Ze=yl(Ep);Ye=yl('<![endif]>')}
function me(){me=Xo;le=yl('<!--[if');ke=yl(Ep);je=yl('<![endif]-->')}
function Ph(a,b,c){Nh();for(var d=0,e=b.length;d<e;++d){a[b[d]]=c[d]}}
function On(a,b,c,d){Array.prototype.splice.apply(a,[b,c].concat(d))}
function Fh(a,b){var c,d;c=a;d=c.slice(0,b);Jh(c.cZ,c.cM,c.qI,d);return d}
function Ok(a,b){var c;c=new Kk;c.e=a+b;Rk(0)&&Sk(0,c);c.c=2;return c}
function Pk(a,b){var c;c=new Kk;c.e=pp+a;Rk(b)&&Sk(b,c);c.c=1;return c}
function Dn(a,b){var c;c=(mn(b,a.c),a.b[b]);Mn(a.b,b,1);--a.c;return c}
function yl(a){var b,c;c=a.length;b=Ih(Kj,dp,-1,c,1);ol(a,c,b,0);return b}
function Th(a){if(a!=null&&(a.tM==Xo||Qh(a,1))){throw new Vk}return a}
function gb(a,b,c){var d;d=hb(a,b,c);if(d>3*c){throw new Xk}else{return d}}
function Cn(a,b,c){for(;c<a.c;++c){if(Fo(b,a.b[c])){return c}}return -1}
function $b(a,b){var c,d;c=Ub(a,Gb,b,yp);d=Ub(a,Jb,c,zp);return d==null?b:d}
function Al(a,b){if(b<0){throw new fm(b)}if(b>a){throw new fm(b)}}
function Bo(a){var b;b=a.b.c;if(b>0){return Bn(a.b,b-1)}else{throw new fo}}
function Co(a){var b;b=a.b.c;if(b>0){return Dn(a.b,b-1)}else{throw new fo}}
function Zj(a){if(Uh(a,24)){return a}return a==null?new ig(null):Xj(a)}
function io(){this.b=[];this.f={};this.d=false;this.c=null;this.e=0}
function ig(a){gg();dg.call(this);this.b=pp;this.c=a;this.b=pp;Uj().u(this)}
function R(a,b){return U(a.c.b,b.c.b)&&(L(),U(Sh(a.b.b,25),Sh(b.b.b,25)))}
function zm(a,b){return b==null?a.c:Uh(b,1)?Bm(a,Sh(b,1)):Am(a,b,~~qg(b))}
function ym(a,b){return b==null?a.d:Uh(b,1)?Dm(a,Sh(b,1)):Cm(a,b,~~qg(b))}
function Sm(a){var b;b=new Gn;a.d&&zn(b,new $m(a));xm(a,b);wm(a,b);this.b=new un(b)}
function Nk(a,b,c,d){var e;e=new Kk;e.e=a+b;Rk(c)&&Sk(c,e);e.c=d?8:0;return e}
function Hm(e,a,b){var c,d=e.f;a=Rp+a;a in d?(c=d[a]):++e.e;d[a]=b;return c}
function Oh(a,b,c){var d=0,e;for(var f in a){if(e=a[f]){b[d]=f;c[d]=e;++d}}}
function Ch(a,b,c){var d=$wnd.setTimeout(function(){a();c!=null&&yg(c)},b);return d}
function G(a,b){var c,d;d=new bm(a.length*b);for(c=0;c<b;c++){zh(d.b,a)}return d.b.b}
function Fd(a,b){var c;for(c=a.i;c<a.k;c++){if(a.f[c]==b){a.i=c;return true}}return false}
function Kd(a,b){var c;for(c=a.i;c<a.k;c++){if(Oc(b,a.f[c])){a.i=c;return true}}return false}
function Gd(a,b,c){var d;for(d=a.i;d<c;d++){if(a.f[d]==b){a.i=d;return true}}return false}
function km(a,b){var c;while(a.N()){c=a.O();if(b==null?c==null:og(b,c)){return a}}return null}
function Ng(a){var b,c;if(a.c){c=null;do{b=a.c;a.c=null;c=Qg(b,c)}while(a.c);a.c=c}}
function Mg(a){var b,c;if(a.b){c=null;do{b=a.b;a.b=null;c=Qg(b,c)}while(a.b);a.b=c}}
function Pb(a){var b,c;b=a.b>1?' start="'+a.b+up:pp;c=a.c;return tp+c.b+b+c.c+'><li>'}
function se(a){if(!Zd(a)){return false}if(a.i==a.h){return false}a.h=a.i;return true}
function Ho(a,b,c){Sl(b,xl(a.b,a.d,a.e.index));zh(b.b,c);a.d=a.c.lastIndex;return a}
function U(a,b){if(a==null||b==null){throw new Yk('No nulls permitted')}return og(a,b)}
function mb(a,b){var c,d,e;e=ql(a,Gl(32),b);d=ql(a,Gl(62),b);c=e<d&&e!=-1?e:d;return xl(a,b,c)}
function rb(a,b){var c,d;d=ul(b,'&#39;',"'");a.b=new cm(d);c=true;while(c){c=tb(a)}return a.b.b.b}
function X(a){var b,c,d;d=new Gn;for(c=new un(a);c.b<c.c.J();){b=Sh(tn(c),25);An(d,b)}return d}
function Lk(a,b,c,d){var e;e=new Kk;e.e=a+b;Rk(c!=0?-c:0)&&Sk(c!=0?-c:0,e);e.c=4;e.b=d;return e}
function An(a,b){var c,d;c=b.K();d=c.length;if(d==0){return false}On(a.b,a.c,0,c);a.c+=d;return true}
function Xe(a){if(!Td(a,Ve)){return false}if(!Hd(a,Ue)){return false}Yd(a,a.i+Ue.length);return true}
function Te(a){if(!Td(a,Re)){return false}a.i+=2;if(!Hd(a,Qe)){return false}Yd(a,a.i+2);return true}
function Yj(a){var b;if(Uh(a,13)){b=Sh(a,13);if(b.c!==(gg(),fg)){return b.c===fg?null:b.c}}return a}
function Ud(a,b,c){var d,e,f;for(e=0,f=c.length;e<f;++e){d=c[e];if(Sd(a,b,d)){return true}}return false}
function ib(a,b,c){if(a.b.b.length>0&&a.b.b.charCodeAt(0)==b){Ah(a.b,0,1,pp);return c}else{return 0}}
function hb(a,b,c){var d;d=0;while(a.b.b.length>0&&a.b.b.charCodeAt(0)==b){Ah(a.b,0,1,pp);d+=c}return d}
function Wb(a){var b,c;b=new un(a.b);while(b.b<b.c.J()){c=Sh(tn(b),11);if(Xb(c)){return false}}return true}
function Xj(b){var c=b.__gwt$exception;if(!c){c=new ig(b);try{b.__gwt$exception=c}catch(a){}}return c}
function Ag(c){return function(){try{return Bg(c,this,arguments);var b}catch(a){throw a}}}
function Uj(){switch(Tj){case 0:case 5:return new gh;case 4:case 9:return new vh;}return new Yg}
function Dk(a,b,c){c&&(a=a.replace(new RegExp('\\.\\*',fq),'[\\s\\S]*'));return new RegExp(a,b)}
function Ef(){Ef=Xo;df();Bd();Df=new Ee(false);ee();Bf=new nd;Cf=new be(Lp);Af=new Pc('<lsovwxp')}
function Xc(){Xc=Xo;Vc=Jh(Mj,_o,12,[new qf,new Ff,new wf,new kf]);Wc=Jh(Mj,_o,12,[new qf,new Pf,new kf])}
function M(a){var b;b=yb(Sh(a.c.b,1));return P(b.c.b,X(new Sn(Jh(Nj,_o,0,[Sh(a.b.b,25),Sh(b.b.b,25)]))))}
function xm(e,a){var b=e.f;for(var c in b){if(c.charCodeAt(0)==58){var d=new dn(e,c.substring(1));a.G(d)}}}
function Pd(a,b){var c;c=b;for(;c>=0;c--){if(a.f[c]==62){return false}if(a.f[c]==60){a.i=c;return true}}return false}
function Zd(a){var b,c;for(c=a.i;c<a.k;c++){b=a.f[c];if(b!=32&&b!=9&&b!=13&&b!=10){a.i=c;return true}}return false}
function Ol(a){Ml();var b=Rp+a;var c=Ll[b];if(c!=null){return c}c=Jl[b];c==null&&(c=Nl(a));Pl();return Ll[b]=c}
function Yb(a){var b,c;b=new Tl;c=new Lo(Bb,a);while(c.e=ek(c.c,c.b),!!c.e){Ho(c,b,xp)}Sl(b,wl(c.b,c.d));return b.b.b}
function Fn(a,b){var c;b.length<a.c&&(b=Gh(b,a.c));for(c=0;c<a.c;++c){Kh(b,c,a.b[c])}b.length>a.c&&Kh(b,a.c,null);return b}
function re(a,b){var c;c=0;while(a.length>b+c&&null!=String.fromCharCode(a[b+c]).match(/[A-Z\d]/i)){++c}return c}
function ag(a){var b,c,d;c=Ih(Oj,_o,23,a.length,0);for(d=0,b=a.length;d<b;++d){if(!a[d]){throw new dl}c[d]=a[d]}}
function Pc(a){var b;this.b=Ih(Qj,bp,-1,256,2);for(b=0;b<a.length;b++){a.charCodeAt(b)<256&&(this.b[a.charCodeAt(b)]=true)}}
function $d(a){this.j=Jh(Rj,cp,2,[]);this.f=Ih(Kj,dp,-1,a.length,1);ol(a,a.length,this.f,0);this.k=a.length;this.h=this.i=0}
function Ke(){Ke=Xo;Ge=yl('<meta');He=yl('name=');Je=yl('ProgId');Fe=yl('Generator');Ie=yl('Originator')}
function Of(){Of=Xo;df();We();Nf=new Ee(true);ee();Kf=new qd;Lf=new be('class');Mf=new be(Lp);Jf=new Pc('<lscovwxp')}
function Lb(a,b){var c;if(Wb(b)){Kh(a.b,a.c++,b)}else{c=new un(b.b);while(c.b<c.c.J()){zn(a,new Hc(Sh(tn(c),11).b))}}}
function lh(a,b){var c;c=fh(a,b);if(c.length==0){return (new Yg).A(b)}else{c[0].indexOf('anonymous@@')==0&&(c=Ug(c,1));return c}}
function Td(a,b){var c,d;c=b.length-1;if((d=a.i+c)>=a.k){return false}do{if(b[c--]!=a.f[d--]){return false}}while(c>=0);return true}
function Ik(a){if(a>=48&&a<58){return a-48}if(a>=97&&a<97){return a-97+10}if(a>=65&&a<65){return a-65+10}return -1}
function jb(a,b,c,d){if(a.b.b.length>1&&a.b.b.charCodeAt(0)==b&&a.b.b.charCodeAt(1)==c){Ah(a.b,0,2,pp);return d}else{return 0}}
function Ob(a,b){var c,d,e;e=new Lo(Db,b);e.e=ek(e.c,e.b);if(e.e){d=Jo(e,e.e[1]==null?2:1);c=al(d);return c==0?1:c}else{return a}}
function Md(a,b,c){var d,e,f,g;for(g=a.i;g<c;g++){for(e=0,f=b.length;e<f;++e){d=b[e];if(d==a.f[g]){a.i=g;return true}}}return false}
function Cm(h,a,b){var c=h.b[b];if(c){for(var d=0,e=c.length;d<e;++d){var f=c[d];var g=f.P();if(h.M(a,g)){return true}}}return false}
function Am(h,a,b){var c=h.b[b];if(c){for(var d=0,e=c.length;d<e;++d){var f=c[d];var g=f.P();if(h.M(a,g)){return f.Q()}}}return null}
function wm(h,a){var b=h.b;for(var c in b){var d=parseInt(c,10);if(c==d){var e=b[d];for(var f=0,g=e.length;f<g;++f){a.G(e[f])}}}}
function fh(a,b){var c,d,e,f;e=Vh(b)?Th(b):null;f=e&&e.stack?e.stack.split('\n'):[];for(c=0,d=f.length;c<d;c++){f[c]=a.v(f[c])}return f}
function gwtOnLoad(b,c,d,e){$moduleName=c;$moduleBase=d;Tj=e;if(b)try{np(Wj)()}catch(a){b(c)}else{np(Wj)()}}
function Oo(a,b){var c,d;this.b=(c=false,d=pp,(b&1)!=0&&(d+='m'),(b&2)!=0&&(d+=Fp),(b&32)!=0&&(c=true),Dk(a,d,c))}
function md(a,b,c){var d;if(!Td(b,kd)){return false}d=b.h;if(!Pd(b,d)){return false}b.i=d;return Nd(b)&&gd(a,b,c,d,b.e,b.d,b.b)}
function Pe(a,b){if(!Td(a,Ne)){return false}if(!Od(a)){return false}if(!Sd(a,a.m,Me)){return false}if(!Nd(a)){return false}Yd(a,a.b);Vf(b);return true}
function Sd(a,b,c){var d,e;e=b;d=c.length-1;if((e+=d)>=a.k){return false}do{if(c[d--]!=a.f[e--]){return false}}while(d>=0);return true}
function Jd(a,b,c,d){var e,f,g;g=a.k-d+1;for(f=a.i;f<g;f++){for(e=0;e<d;e++){if(b[c+e]!=a.f[f+e]){break}}if(e==d){a.i=f;return true}}return false}
function Id(a,b,c){var d,e,f,g;d=b.length;g=c-b.length+1;for(f=a.i;f<g;f++){for(e=0;e<d;e++){if(b[e]!=a.f[f+e]){break}}if(e==d){a.i=f;return true}}return false}
function lc(a,b){var c;if(b.c==(Dc(),vc)||b.c==Bc){if(Io(new Lo(hc,a))||Io(new Lo(dc,a))){c=fb(a);if(c==b.b+1){return true}}}return false}
function zg(){var a;if(tg!=0){a=(new Date).getTime();if(a-vg>2000){vg=a;wg=Ig()}}if(tg++==0){Mg((Lg(),Kg));return true}return false}
function Dl(a,b,c){var d=pp;for(var e=b;e<c;){var f=Math.min(e+10000,c);d+=String.fromCharCode.apply(null,a.slice(e,f));e=f}return d}
function Cl(a){var b;b=0;while(0<=(b=a.indexOf('\\',b))){a.charCodeAt(b+1)==36?(a=xl(a,0,b)+'$'+wl(a,++b)):(a=xl(a,0,b)+wl(a,++b))}return a}
function tb(a){var b,c,d,e;c=a.b.b.b.indexOf('mso-number-format:');if(c<0){return false}d=c+18;b=sb(a,d);e=d-18;e>-1&&$l(a.b,e,b);return true}
function jn(a,b){var c,d;for(c=0,d=a.b.length;c<d;++c){if(b==null?(mn(c,a.b.length),a.b[c])==null:og(b,(mn(c,a.b.length),a.b[c]))){return c}}return -1}
function Vj(){switch(Tj){case 4:case 9:return new zk;case 1:case 6:return new nk;case 3:case 8:return new vk;case 2:case 7:return new rk;}return new jk}
function zl(c){if(c.length==0||c[0]>hq&&c[c.length-1]>hq){return c}var a=c.replace(/^([\u0000-\u0020]*)/,pp);var b=a.replace(/[\u0000-\u0020]*$/,pp);return b}
function ne(a){if(!Td(a,le)){return false}Vd(a,le.length);if(!Hd(a,ke)){return false}Vd(a,ke.length);if(!Hd(a,je)){return false}Yd(a,a.i+je.length);return true}
function Hh(a,b){var c=new Array(b);if(a==3){for(var d=0;d<b;++d){c[d]={l:0,m:0,h:0}}}else if(a>0&&a<3){var e=a==1?0:false;for(var d=0;d<b;++d){c[d]=e}}return c}
function tl(d,a,b){var c;if(a<256){c=bl(a);c='\\x'+'00'.substring(c.length)+c}else{c=String.fromCharCode(a)}return d.replace(RegExp(c,fq),String.fromCharCode(b))}
function Sk(a,b){var c;b.d=a;if(a==2){c=String.prototype}else{if(a>0){var d=Qk(b);if(d){c=d.prototype}else{d=_j[a]=function(){};d.cZ=b;return}}else{return}}c.cZ=b}
function Gl(a){var b,c;if(a>=65536){b=55296+(~~(a-65536)>>10&1023)&65535;c=56320+(a-65536&1023)&65535;return Fl(b)+Fl(c)}else{return String.fromCharCode(a&65535)}}
function So(a){var b,c,d,e,f;f=vl(a,'\\.',0);e=$wnd;b=0;for(c=f.length-1;b<c;b++){if(!nl(f[b],'client')){e[f[b]]||(e[f[b]]={});e=Uo(e,f[b])}}d=Uo(e,f[b]);return d}
function sb(a,b){var c,d,e,f,g,h;e=b;f=b-18>-1;d=false;g=0;while(f){c=Zl(a.b,e);c==34&&g!=92&&(d=!d);(h=c==59&&!d,e==a.b.b.b.length-1||h)&&(f=false);++e;g=c}return e}
function pe(){pe=Xo;oe=new bd(Jh(Pj,_o,1,['font','span','b',Fp,'u','sub','sup','em','strong','samp','acronym','cite','code','dfn','kbd','tt','s','ins','del','var']))}
function fl(){fl=Xo;el=Jh(Kj,dp,-1,[48,49,50,51,52,53,54,55,56,57,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122])}
function Ce(){Ce=Xo;Ae=yl('<link');Be=yl('rel=');ye=Jh(Rj,cp,2,[yl(Gp),yl(Hp),yl(Ip),yl(Jp),yl(Kp)]);ze=Jh(Rj,cp,2,[yl(Gp),yl(Hp),yl(Ip),yl(Jp),yl(Kp),yl('stylesheet')])}
function bl(a){var b,c,d;b=Ih(Kj,dp,-1,8,1);c=(fl(),el);d=7;if(a>=0){while(a>15){b[d--]=c[a&15];a>>=4}}else{while(d>0){b[d--]=c[a&15];a>>=4}}b[d]=c[a&15];return Dl(b,d,8)}
function yb(a){var b,c,d;c='Content before importing MS-Word lists:\r\n'+a;d=Qb(wb,a);b='Content after importing MS-Word lists:\r\n'+d;return P(d,new Sn(Jh(Pj,_o,1,[c,b])))}
function lm(a){var b,c,d,e;d=new Tl;b=null;zh(d.b,Up);c=a.I();while(c.N()){b!=null?(zh(d.b,b),d):(b=iq);e=c.O();zh(d.b,e===a?'(this Collection)':pp+e)}zh(d.b,Vp);return d.b.b}
function Ub(a,b,c,d){var e,f,g,h;f=new Lo(b,c);f.e=ek(f.c,f.b);if(f.e){e=f.e[1];h=f.e[2];g=fk(f.c,f.b,tp+d+e+"><li style='list-style: none;'><"+d+h+wp);return $b(a,g)}return c}
function af(a,b){var c,d;if(!Td(a,$e)){return false}if(!Hd(a,Ze)){return false}c=a.i+Ze.length;if(!Hd(a,Ye)){return false}d=a.i;Uf(b,a.f,c,d-c);Yd(a,a.i+Ye.length);return true}
function ae(a,b,c){if(!Td(b,a.b)){return false}if(!Pd(b,b.h)){return false}if(!Rd(b,b.h-1)){return false}b.i=b.h+a.b.length-1;if(!Nd(b)){return false}Vf(c);b.h=b.i=b.b;return true}
function Yc(a,b){Xc();var c,d,e,f,g;c=new $d(a);e=new Wf(a.length);g=b==1?Wc:Vc;d=g.length-1;for(f=0;f<d;f++){Zc(c,e,g[f]);$c(c,e)}while(Zc(c,e,g[d])){$c(c,e)}return Il(e.b,e.c)}
function hg(a){var b;if(a.d==null){b=a.c===fg?null:a.c;a.e=b==null?Np:Vh(b)?kg(Th(b)):Uh(b,1)?Op:pg(b).e;a.b=a.b+Mp+(Vh(b)?jg(Th(b)):b+pp);a.d=Pp+a.e+') '+(Vh(b)?Sg(Th(b)):pp)+a.b}}
function Sb(a,b){var c,d,e;e=new am;for(c=0;c<a;c++){d=Sh(Co(b.b),6).c;zh(e.b,'<\/');Yl(e,d.b);e.b.b+=wp;(b.b.b.c==0?(ic(),gc):Sh(Bo(b.b),6))!=(ic(),gc)&&(zh(e.b,vp),e)}return e.b.b}
function Wg(b){var c=pp;try{for(var d in b){if(d!='name'&&d!='message'&&d!='toString'){try{var e=d!='__gwt$exception'?b[d]:'<skipped>';c+='\n '+d+Mp+e}catch(a){}}}}catch(a){}return c}
function dk(a){return $stats({moduleName:$moduleName,sessionId:$sessionId,subSystem:'startup',evtGroup:'moduleStartup',millis:(new Date).getTime(),type:'onModuleLoadStart',className:a})}
function Rg(a){var b,c,d;d=pp;a=zl(a);b=a.indexOf(Pp);c=a.indexOf('function')==0?8:0;if(b==-1){b=pl(a,Gl(64));c=a.indexOf('function ')==0?9:0}b!=-1&&(d=zl(xl(a,c,b)));return d.length>0?d:Qp}
function Qb(a,b){var c,d,e;c=(d=new Lo(new Oo('<\/?u[0-9]:p>',33),b),fk(d.c,d.b,pp));c=Rb(a,c);c=Ko(new Lo(Ib,c));c=(e=new Lo(new Oo('style *?=[\'"](;?)[\'"]',32),c),fk(e.c,e.b,pp));return c}
function Qg(b,c){var d,e,f,g;for(e=0,f=b.length;e<f;e++){g=b[e];try{g[1]?g[0].U()&&(c=Pg(c,g)):g[0].U()}catch(a){a=Zj(a);if(Uh(a,24)){d=a;Fg(Uh(d,13)?Sh(d,13).s():d)}else throw Yj(a)}}return c}
function Tb(a,b,c){var d,e;if(b>0){for(d=0;d<b;d++){Do(c.b,a)}return G(Pb(a),b)}else{if(nl(a.c.b,(c.b.b.c==0?(ic(),gc):Sh(Bo(c.b),6)).c.b)){return '<li>'}else{e=Sb(1,c)+Pb(a);Do(c.b,a);return e}}}
function _b(a,b,c,d,e){var f,g,h;h=b;g=new am;if(b>=c){zh(g.b,vp);Yl(g,Sb(b-c,a))}f=a.b.b.c==0?(ic(),gc):Sh(Bo(a.b),6);if(b==c&&f.c!=e.c){Yl(g,Sb(b,a));h=0}Yl(g,Tb(e,c-h,a));zh(g.b,d);return g.b.b}
function De(a,b){if(!Td(b,Ae)){return false}Xd(b,b.h+Ae.length);if(!Od(b)){return false}if(!Id(b,Be,b.l)){return false}if(!Nd(b)){return false}if(!Ud(b,b.e,a.b)){return false}Yd(b,b.l+1);return true}
function fe(a,b){var c,d;c=a.f;d=a.h;if(c[d+1]!=58){return false}if(!Oc(ce,c[d])){return false}if(!Oc(de,c[d-1])){return false}if(!Od(a)){return false}if(!Nd(a)){return false}Yd(a,a.b);Vf(b);return true}
function Zb(a){var b,c,d,e,f,g;e=a;if(a.indexOf(qp)==0){c=a.indexOf(sp);if(c>0){d=pl(a,Gl(62))+1;b=xl(a,d,c);g=new No('^(?:&nbsp;|\\s)*$');f=new Lo(g,b);f.e=ek(f.c,f.b);!!f.e&&(e=wl(a,c+7))}}return e}
function Nl(a){var b,c,d,e;b=0;d=a.length;e=d-4;c=0;while(c<e){b=a.charCodeAt(c+3)+31*(a.charCodeAt(c+2)+31*(a.charCodeAt(c+1)+31*(a.charCodeAt(c)+31*b)))|0;c+=4}while(c<d){b=b*31+ml(a,c++)}return b|0}
function Fm(j,a,b,c){var d=j.b[c];if(d){for(var e=0,f=d.length;e<f;++e){var g=d[e];var h=g.P();if(j.M(a,h)){var i=g.Q();g.R(b);return i}}}else{d=j.b[c]=[]}var g=new ko(a,b);d.push(g);++j.e;return null}
function nb(a){var b,c,d,e;c=new No('(class=)([^>[ \\t\\n\\x0B\\f\\r]]*)');b=new Lo(c,a);e=new Tl;while(b.e=ek(b.c,b.b),!!b.e){d=b.e[2];d=d.toLowerCase();Ho(b,e,b.e[1]+d)}Sl(e,wl(b.b,b.d));return e.b.b}
function Kh(a,b,c){if(c!=null){if(a.qI>0&&!Rh(c,a.qI)){throw new Gk}else if(a.qI==-1&&(c.tM==Xo||Qh(c,1))){throw new Gk}else if(a.qI<-1&&!(c.tM!=Xo&&!Qh(c,1))&&!Rh(c,-a.qI)){throw new Gk}}return a[b]=c}
function ic(){ic=Xo;cc=new No('([\xB7\xA7\u2022\u2043\u25A1o-]|\xD8|&middot;|<img[^>]*>)');hc=new No('[A-Z]+');dc=new No('[a-z]+');fc=new No('X?(?:IX|IV|V?I{0,3})');ec=new No('x?(?:ix|iv|v?i{0,3})');gc=new jc}
function ak(a,b,c){var d=_j[a];if(d&&!d.cZ){_=d.prototype}else{!d&&(d=_j[a]=function(){});_=d.prototype=b<0?{}:bk(b);_.cM=c}for(var e=3;e<arguments.length;++e){arguments[e].prototype=_}if(d.cZ){_.cZ=d.cZ;d.cZ=null}}
function Od(a){for(a.m=a.i;a.m>=0;a.m--){if(a.f[a.m]==62){return false}if(a.f[a.m]==60){break}}if(a.m<0){return false}for(a.l=a.i;a.l<a.k;a.l++){if(a.f[a.l]==60){return false}if(a.f[a.l]==62){return true}}return false}
function fb(a){var b,c,d,e,f;f=a.toLowerCase();if(f.length==0){return 1}else if(f.length==1){c=f.charCodeAt(0);e=c+1-97}else{e=0;for(d=0;d<f.length;d++){c=ml(f,f.length-1-d);b=fb(String.fromCharCode(c))*Yh(Math.pow(26,d));e+=b}}return e}
function Le(a){var b,c;if(!Td(a,Ge)){return false}if(!Fd(a,62)){return false}b=a.i;Xd(a,a.h+Ge.length);if(!Id(a,He,b)){return false}c=a.i+He.length;a.f[c]==34&&++c;if(Sd(a,c,Je)||Sd(a,c,Fe)||Sd(a,c,Ie)){a.h=a.i=b+1;return true}return false}
function ff(a){var b,c;if((a.i>=a.k?0:a.f[a.i])!=64){return false}b=a.h;a.i+=1;c=a.f[b+1];if(!(null!=String.fromCharCode(c).match(/[A-Z]/i))&&c!=95){return false}if(!Fd(a,123)){return false}if(!Fd(a,125)){return false}Yd(a,a.i+1);return true}
function wd(a,b,c){var d,e,f,g;e=c;a.i=b;if(!Gd(a,46,c)){return}do{a.i+=1}while(Gd(a,46,c));d=a.i;Md(a,td,c)&&(e=a.i);if(e==d){return}f=a.j;g=f.length;a.j=Ih(Rj,cp,2,g+1,0);g!=0&&gm(f,0,a.j,0,g);a.j[g]=Ih(Kj,dp,-1,e-d,1);gm(a.f,d,a.j[g],0,e-d)}
function Xb(a){var b,c,d,e,f,g,h;c=a.b;g=new Lo(Hb,c);g.e=ek(g.c,g.b);if(g.e){f=g.e[2];h=new Lo(Eb,f);h.e=ek(h.c,h.b);if(h.e){e=h.e[1];b=h.e[2];d=new Lo(new No('^\\d\\.'),e);d.e=ek(d.c,d.b);if(!!d.e&&f.indexOf(e+b)!=-1){return true}}}return false}
function ob(b,c,d){var e,f,g;try{g=b?(Xc(),Uc):1;e=Yc(d,g);e=pb(e);b&&!c&&(e=nb(e));return L(),L(),new T(new W(e),K)}catch(a){a=Zj(a);if(Uh(a,20)){f=a;return L(),P(pp,new Sn(Jh(Pj,_o,1,['Failed to clean MS Office HTML.\n'+f.r()])))}else throw Yj(a)}}
function Dd(a,b){var c,d,e,f,g;if(!Td(a,zd)){return false}g=a.i;if(!Hd(a,yd)){return false}c=a.i+yd.length;d=b.c;Uf(b,zd,0,zd.length);e=a.k;Wd(a,a.f,a.i);Yd(a,g+zd.length);f=Cd(a,b);Wd(a,a.f,e);if(f){Uf(b,yd,0,yd.length);a.h=a.i=c}else{b.c=d;a.h=a.i=g}return f}
function bd(a){var b,c,d,e,f,g,h;this.b=Ih(Sj,_o,3,128,0);for(c=0,d=a.length;c<d;++c){b=a[c];g=yl(b);e=g[0];e>=128&&(e=0);if(this.b[e]==null){this.b[e]=Jh(Rj,cp,2,[g])}else{h=this.b[e];f=h.length;this.b[e]=Ih(Rj,cp,2,f+1,0);gm(h,0,this.b[e],0,f);this.b[e][f]=g}}}
function Sc(a,b){var c,d,e,f;if(!Tc(a,Qc)){return false}d=a.i;c=a.h+Qc.length;a.i=c;a.h=a.i=c;e=yl('<img ');Uf(b,e,0,e.length);f=yl('o:title="');if(!Id(a,f,d)){return true}Uf(b,a.f,c,a.i-c);Xd(a,a.i+f.length);if(!Gd(a,34,d)){return true}Xd(a,a.i+1);Yd(a,a.i);return true}
function Vb(a){var b,c,d,e;e=new Gn;d=null;for(c=0;c<a.c;c++){b=(mn(c,a.c),Sh(a.b[c],10));if(Uh(b,8)){if(!Io(new Lo(Fb,Sh(b,8).b))||c+1>=a.c||!Uh((mn(c+1,a.c),a.b[c+1]),11)||!d){if(d){Lb(e,d);d=null}Kh(e.b,e.c++,b)}}else{!d&&(d=new Kc);Jc(d,Sh(b,11))}}!!d&&Lb(e,d);return e}
function ie(a,b){var c,d;if(a.j.length==0){return false}if(!Td(a,ge)){return false}if(!Od(a)){return false}if(!Nd(a)){return false}c=a.d-a.e;for(d=0;d<a.j.length;d++){if(a.j[d].length==c){if(Sd(a,a.e,a.j[d])){break}}}if(d==a.j.length){return false}Yd(a,a.b);Vf(b);return true}
function Cd(a,b){var c,d,e,f;d=false;f=32;c=a.i>=a.k?0:a.f[a.i];while(c!=0){e=false;switch(c){case 64:e=ff(a);break;case 47:e=Te(a);}!e&&(f==10||f==13)&&(e=vd(Ad,a,b));if(e){d=true;f=b.c==0?0:b.b[b.c-1];a.i=a.h;c=a.i>=a.k?0:a.f[a.i]}else{Tf(b,f=c);c=(a.i=++a.h)>=a.k?0:a.f[a.i]}}return d}
function Mb(a,b,c,d,e){var f,g,h,i,j;i=tl(zl(e),10,32);i.lastIndexOf(sp)!=-1&&i.lastIndexOf(sp)==i.length-sp.length&&(i=xl(i,0,i.length-7));while(i.indexOf(tp)==0){h=pl(i,Gl(62));i=wl(i,h+1)}g=pl(i,Gl(60));i=wl(i,g);i=Zb(i);f=new Lo(Cb,i);i=fk(f.c,f.b,pp);j=new kc('-',(ic(),gc));Yl(c,_b(a,b,d,i,j))}
function mh(a,b){var c,d,e,f,g,h,i,j,k,l;l=Ih(Oj,_o,23,b.length,0);for(f=0,g=l.length;f<g;f++){k=vl(b[f],Sp,0);i=-1;c=-1;e=Tp;if(k.length==2&&k[1]!=null){j=k[1];h=rl(j,Gl(58));d=sl(j,Gl(58),h-1);e=xl(j,0,d);if(h!=-1&&d!=-1){i=Tg(xl(j,d+1,h));c=Tg(wl(j,h+1))}}l[f]=new jl(k[0],e+op+c,a.C(i<0?-1:i))}ag(l)}
function al(a){var b,c,d,e,f;if(a==null){throw new hl(Np)}d=a.length;e=d>0&&(a.charCodeAt(0)==45||a.charCodeAt(0)==43)?1:0;for(b=e;b<d;b++){if(Ik(a.charCodeAt(b))==-1){throw new hl(gq+a+up)}}f=parseInt(a,10);c=f<-2147483648;if(isNaN(f)){throw new hl(gq+a+up)}else if(c||f>2147483647){throw new hl(gq+a+up)}return f}
function we(a,b){var c,d,e,f;if(!Td(a,ue)){return false}f=a.h+ue.length;for(;f<a.k;f++){c=a.f[f];if(c==62){break}if(c!=32&&c!=10&&c!=9&&c!=13){return false}}e=a.i=f+1;if(!Hd(a,te)){return false}d=a.i;a.i=e;if(Id(a,ue,d)){return false}Xd(a,d+te.length);if(!Fd(a,62)){return false}Uf(b,a.f,e,d-e);Yd(a,a.i+1);return true}
function fd(){fd=Xo;ed=new bd(Jh(Pj,_o,1,['font-color','horiz-align','language','list-image-','mso-','page:','separator-image','tab-stops','tab-interval','text-underline','text-effect','text-line-through','table-border-color-dark','table-border-color-light','vert-align','vnd.ms-excel.']));dd=new bd(Jh(Pj,_o,1,['mso-list']))}
function Zc(a,b,c){var d,e,f,g,h,i,j;j=a.k;e=a.f;a.h=a.i=0;f=32;d=c.p();h=0;i=0;g=false;while(i<j){for(;h<j;h++){f=e[h];if(f<256&&d[f]){break}}if(h>=j){gm(e,i,b.b,b.c,j-i);b.c+=j-i;break}(f==10||f==13)&&++h;h!=i&&(gm(e,i,b.b,b.c,h-i),b.c+=h-i);if(h==j){break}a.i=a.h=h;if(c.q(a,b,f)){g=true;i=h=a.i=a.h}else{i=h;f!=10&&f!=13&&++h}}return g}
function gd(a,b,c,d,e,f,g){var h,i,j,k,l,m;l=d;m=e;k=c.c;b.i=e;i=false;j=false;while(m<f){if(!Zd(b)||b.i>=f){break}h=a.o(b);if(h){i=true;m!=l&&Uf(c,b.f,l,m-l);if(Gd(b,59,f)){l=m=b.i+=1}else{l=f;break}}else{j=true;if(Gd(b,59,f)){m=b.i+=1}else{break}}}if(j&&!i){return false}if(j&&i){g!=l&&Uf(c,b.f,l,g-l)}else{c.c=k;Vf(c)}b.h=b.i=g;return true}
function Dc(){Dc=Xo;xc=new Ec('NO_TYPE',pp,pp);Ac=new Ec('UNORDERED',zp,pp);zc=new Ec('SQUARE',zp,' type="square"');uc=new Ec('CIRCLE',zp,' type="circle"');yc=new Ec('NUMERIC',yp,pp);Cc=new Ec('UPPER_ROMAN',yp,' type="I"');wc=new Ec('LOWER_ROMAN',yp,' type="i"');Bc=new Ec('UPPER_ALPHA',yp,' type="A"');vc=new Ec('LOWER_ALPHA',yp,' type="a"');tc=Jh(Lj,_o,7,[xc,Ac,zc,uc,yc,Cc,wc,Bc,vc])}
function vd(a,b,c){var d,e,f,g,h,i,j,k,l;i=b.i;if(b.f[b.i+-1]!=10&&b.f[b.i+-1]!=13){return false}d=b.i>=b.k?0:b.f[b.i];if(d==123||d==125){return false}f=b.i;if(!Ld(b,sd)){return false}e=b.i;if((b.i>=b.k?0:b.f[b.i])!=123){if(!Zd(b)){return false}if((b.i>=b.k?0:b.f[b.i])!=123){return false}}l=b.i+1;if(!Fd(b,125)){return false}j=b.i;k=j+1;g=c.c;h=gd(a,b,c,i,l,j,k);h&&c.c<=g&&wd(b,f,e);return h}
function qe(a){var b,c,d,e,f,g;d=a.h+1;b=a.f[d];if(b>127){return false}g=oe.b[b];if(g==null){return false}f=re(a.f,d);for(c=0;c<g.length;c++){if(Sd(a,d,g[c])&&f==g[c].length){break}}if(c==g.length){return false}e=g[c];a.i=d+e.length;if(!Fd(a,62)){return false}d=a.i+1;if(a.f[d++]!=60||a.f[d++]!=47){return false}if(!Sd(a,d,e)){return false}a.i=d+e.length;if(!Fd(a,62)){return false}Yd(a,a.i+1);return true}
function ef(a,b){var c,d,e,f,g,h;f=a.h;if(a.f[f+2]!=58||a.f[f]!=60){return false}if(!Oc(cf,a.f[f+1])){return false}h=f+1;a.i=f+3;if(!Kd(a,bf)){return false}g=a.i-h;if(!Fd(a,62)){return false}if(Qd(a,a.i-1)==47){Yd(a,a.i+1);return true}e=a.i+1;while(Jd(a,a.f,h,g)){d=a.i-1;c=a.f[d];if(c==60){return false}if(c==47&&Qd(a,--d)==60){if(!Fd(a,62)){return false}Uf(b,a.f,e,d-e);Yd(a,a.i+1);return true}++a.i}return false}
function vl(l,a,b){var c=new RegExp(a,fq);var d=[];var e=0;var f=l;var g=null;while(true){var h=c.exec(f);if(h==null||f==pp||e==b-1&&b>0){d[e]=f;break}else{d[e]=f.substring(0,h.index);f=f.substring(h.index+h[0].length,f.length);c.lastIndex=0;if(g==f){d[e]=f.substring(0,1);f=f.substring(1)}g=f;e++}}if(b==0&&l.length>0){var i=d.length;while(i>0&&d[i-1]==pp){--i}i<d.length&&d.splice(i,d.length-i)}var j=Bl(d.length);for(var k=0;k<d.length;++k){j[k]=d[k]}return j}
function Nd(a){var b,c;for(b=a.i;b<a.k;b++){if(a.f[b]==62){return false}if(a.f[b]==61){break}}if(b==a.k){return false}a.c=++b;c=a.f[b];if(c==34||c==39){a.e=++b;for(;b<a.k;b++){if(a.f[b]==62){return false}if(a.f[b]==c){break}}if(b==a.k){return false}a.d=b;a.b=b+1;a.i=a.e;return true}else{a.e=a.c;for(;b<a.k;b++){if(a.f[b]==62){break}if(a.f[b]==32){break}if(a.f[b]==9){break}if(a.f[b]==13){break}if(a.f[b]==10){break}}if(b==a.k){return false}a.d=a.b=b;return true}}
function kb(b){var c,d,e,f,g,h,i;d=0;c=new Ul(b.toLowerCase());e=false;try{d=(f=(g=0,g+=gb(c,109,1000),g+=jb(c,99,109,900),g+=ib(c,100,500),g+=jb(c,99,100,400),g),f=(h=f,h+=gb(c,99,100),h+=jb(c,120,99,90),h+=ib(c,108,50),h+=jb(c,120,108,40),h),f=(i=f,i+=gb(c,120,10),i+=jb(c,105,120,9),i+=ib(c,118,5),i+=jb(c,105,118,4),i),f+=gb(c,105,1),f)}catch(a){a=Zj(a);if(Uh(a,21)){e=true}else throw Yj(a)}if(e||c.b.b.length>0){throw new Yk(b+' is not a parsable roman numeral')}return d}
function Wj(){var a,b,c;ck()&&dk('com.google.gwt.useragent.client.UserAgentAsserter');a=Sh(Vj(),15);b=a.D();c=a.F();nl(b,c)||($wnd.alert('ERROR: Possible problem with your *.gwt.xml module file.\nThe compile time user.agent value ('+b+') does not match the runtime user.agent value ('+c+'). Expect more errors.\n'),undefined);ck()&&dk('com.google.gwt.user.client.DocumentModeAsserter');hk();ck()&&dk('com.ephox.keurig.client.Keurig');Wo();new db;$wnd.gwtInited&&$wnd.gwtInited()}
function pb(a){var b,c,d,e,f,g,h,i,j,k,l,m,n,o,q;d=new cm(a);b=d.b.b.length;while(b>-1){b=sl(d.b.b,'<p',b);c=ql(d.b.b,'<\/p>',b);if(b>-1&&c>-1){q=xl(d.b.b,b,c);m=q.indexOf(qp);if(m>-1){l=pl(q,Gl(62));if(l+1==m){f=ql(q,Gl(62),m);n=xl(q,m,f+1);e=n.indexOf(rp);if(e>-1){h=q.lastIndexOf(sp);if(7+h==q.length){i=xl(q,0,pl(q,Gl(62))+1);k=i.indexOf(rp);if(k>-1){j=mb(q,k);o=mb(n,e);if(!nl(j,o)){g=q.length-7;_l(d,g+b,q.length+b,pp);_l(d,m+b,m+n.length+b,pp);_l(d,k+b,k+j.length+b,o)}}}}}}}--b}return d.b.b}
function Rb(a,b){var c,d,e,f,g,h,i,j,k,l,m,n,o,q,r,s,t,u,v;g=Nb(b);v=Vb(g);s=new nc;u=new am;for(f=new un(v);f.b<f.c.J();){e=Sh(tn(f),10);if(Uh(e,8)){Yl(u,Sh(e,8).b)}else{h=new un(Sh(e,9).b);j=0;k=new am;i=(ic(),gc);while(h.b<h.c.J()){r=Sh(tn(h),11);q=new Lo(Hb,r.b);q.e=ek(q.c,q.b);if(q.e){c=q.e[1];m=Ob(j,c);t=q.e[2];n=new Lo(Eb,t);n.e=ek(n.c,n.b);if(n.e){d=n.e[1];l=Yb(n.e[2]);o=new kc(d,i);i=o;Yl(k,_b(s,j,m,l,o))}else{Mb(s,j,k,m,t)}j=m}}zh(k.b,vp);Yl(k,Sb(j,s));Yl(u,$b(a,k.b.b))}}return u.b.b}
function gm(a,b,c,d,e){var f,g,h,i,j,k,l,m,n;if(a==null||c==null){throw new dl}m=pg(a);i=pg(c);if((m.c&4)==0||(i.c&4)==0){throw new Hk('Must be array types')}l=m.b;g=i.b;if(!((l.c&1)!=0?l==g:(g.c&1)==0)){throw new Hk('Array types must match')}n=a.length;j=c.length;if(b<0||d<0||e<0||b+e>n||d+e>j){throw new $k}if(((l.c&1)==0||(l.c&4)!=0)&&m!=i){k=Sh(a,22);f=Sh(c,22);if(Xh(a)===Xh(c)&&b<d){b+=e;for(h=d+e;h-->d;){Kh(f,h,k[--b])}}else{for(h=d+e;d<h;){Kh(f,d++,k[b++])}}}else{Array.prototype.splice.apply(c,[d,e].concat(a.slice(b,b+e)))}}
function Nb(a){var b,c,d,e,f,g,h,i,j;e=new Gn;h=new Lo(Hb,a);f=0;while(h.e=ek(h.c,h.b),!!h.e){j=h.e[1];b=new Lo(Db,j);b.e=ek(b.c,b.b);if(b.e){i=!h.e||h.e.length<1?-1:h.e.index;if(i>f){d=new Hc(xl(a,f,i));Kh(e.b,e.c++,d)}g=new Mc(xl(a,!h.e||h.e.length<1?-1:h.e.index,!h.e||h.e.length<1?-1:h.e.index+h.e[0].length));Kh(e.b,e.c++,g)}else{c=(!h.e||h.e.length<1?-1:h.e.index)>f?f:!h.e||h.e.length<1?-1:h.e.index;d=new Hc(xl(a,c,!h.e||h.e.length<1?-1:h.e.index+h.e[0].length));Kh(e.b,e.c++,d)}f=!h.e||h.e.length<1?-1:h.e.index+h.e[0].length}if(f<a.length){d=new Hc(wl(a,f));Kh(e.b,e.c++,d)}return e}
function cb(h){var e=(Wo(),So('com.ephox.keurig.WordCleaner'));var f,g=h;$wnd.com.ephox.keurig.WordCleaner=np(function(){var a,b=this,c=arguments;c.length==1&&g.n(c[0])?(a=c[0]):c.length==0&&(a=new Z);b.g=a;a['__gwtex_wrap']=b;return b});f=$wnd.com.ephox.keurig.WordCleaner.prototype=new Object;$wnd.com.ephox.keurig.WordCleaner.cleanDocument=np(function(a,b){var c,d;return c=new zb(a,b),d=M(ob(c.c,c.d,c.b)),Sh(d.c.b,1)});$wnd.com.ephox.keurig.WordCleaner.yury=np(function(a,b){var c;return c=b?(Xc(),Uc):1,Yc(a,c)});if(e)for(p in e)$wnd.com.ephox.keurig.WordCleaner[p]===undefined&&($wnd.com.ephox.keurig.WordCleaner[p]=e[p])}
function kc(a,b){ic();var c,d,e,f,g,h;f=new Lo(cc,a);f.e=ek(f.c,f.b);if(f.e){g=f.e[1];this.c=nl(g,'\xA7')?(Dc(),zc):nl(g,'o')?(Dc(),uc):(Dc(),Ac)}else{e=new Lo(new No('\\(?(\\d+|[a-zA-Z]+)(?:\\)|\\.)?'),a);e.e=ek(e.c,e.b);if(e.e){c=e.e[1];if(lc(c,b)){this.c=Io(new Lo(hc,c))?(Dc(),Bc):(Dc(),vc);this.b=fb(c)}else{d=new Lo(ec,c);d.e=ek(d.c,d.b);if(!!d.e&&d.e[0].length!=0){this.c=(Dc(),wc);this.b=kb(c)}else{h=new Lo(fc,c);h.e=ek(h.c,h.b);if(!!h.e&&h.e[0].length!=0){this.c=(Dc(),Cc);this.b=kb(c)}else{if(Io(new Lo(dc,c))){this.c=(Dc(),vc);this.b=fb(c)}else if(Io(new Lo(hc,c))){this.c=(Dc(),Bc);this.b=fb(c)}else{this.c=(Dc(),yc);this.b=al(c)}}}}}else{this.c=(Dc(),Ac)}}}
function Kb(){Kb=Xo;Ib=new Oo('mso\\-list:.*?([;"\'])',32);Db=new Oo('style=["\'].*?mso\\-list:(?:([0-9]+)|.*?level([0-9]+)).*?["\']',32);Gb=new No('<ol([^>]*)><li><ol([^>]*)>');Jb=new No('<ul([^>]*)><li><ul([^>]*)>');Eb=new Oo('^[ \\t\\n\\x0B\\f\\r]*(?:<[^>]*>)*?(?:<span[^>]*>[ \\t\\n\\x0B\\f\\r]*){0,3}(?:&nbsp;|\\s)*(?:<\/span[^>]*>[ \\t\\n\\x0B\\f\\r]*)?([\xB7\xA7\u2022\u2043\u25A1o-]|\xD8|&middot;|<img[^>]*>|\\(?(?:\\d+|[a-zA-z]+)(?:\\)|\\.)?)(?:&nbsp;|\\s)*(?:<span[^>]*>[ \\t\\n\\x0B\\f\\r]*)?(?:&nbsp;|\\s)*(?:<\/span[^>]*>[ \\t\\n\\x0B\\f\\r]*){0,3}(.*?)$',32);Hb=new Oo('<p([^>]*)>(.*?)<\/p>[ \\t\\n\\x0B\\f\\r]*',32);Fb=new No('<p[^>]*>(?:<[^>]*>|[ \\t\\n\\x0B\\f\\r])*&nbsp;(?:<[^>]*>|[ \\t\\n\\x0B\\f\\r])*<\/p>');Cb=new No('^(?:<\/[^>]+>)*');Bb=new Oo('<a\\sname="OLE_LINK\\d">(.*?)<\/a>',32)}
function hk(){var a,b,c;b=$doc.compatMode;a=Jh(Pj,_o,1,[Xp]);for(c=0;c<a.length;c++){if(nl(a[c],b)){return}}a.length==1&&nl(Xp,a[0])&&nl('BackCompat',b)?"GWT no longer supports Quirks Mode (document.compatMode=' BackCompat').<br>Make sure your application's host HTML page has a Standards Mode (document.compatMode=' CSS1Compat') doctype,<br>e.g. by using &lt;!doctype html&gt; at the start of your application's HTML page.<br><br>To continue using this unsupported rendering mode and risk layout problems, suppress this message by adding<br>the following line to your*.gwt.xml module file:<br>&nbsp;&nbsp;&lt;extend-configuration-property name=\"document.compatMode\" value=\""+b+'"/&gt;':"Your *.gwt.xml module configuration prohibits the use of the current doucment rendering mode (document.compatMode=' "+b+"').<br>Modify your application's host HTML page doctype, or update your custom 'document.compatMode' configuration property settings."}
var pp='',hq=' ',up='"',xp='$1',Pp='(',Wp=')',iq=', ',Rp=':',Mp=': ',tp='<',vp='<\/li>',sp='<\/span>',Bp='<\/style>',qp='<span',Ap='<style',Cp='=',wp='>',op='@',Sp='@@',Xp='CSS1Compat',Hp='Edit-Time-Data',Gp='File-List',gq='For input string: "',Ip='Ole-Object-Data',Jp='Original-File',Kp='Preview',Op='String',Tp='Unknown',Up='[',lq='[Ljava.lang.',Vp=']',Ep=']>',Qp='anonymous',sq='com.ephox.functional.data.immutable.',qq='com.ephox.keurig.client.',rq='com.ephox.tord.guts.',uq='com.ephox.tord.lists.',wq='com.ephox.tord.lists.data.',tq='com.ephox.tord.wordhtmlfilter.',kq='com.google.gwt.core.client.',nq='com.google.gwt.core.client.impl.',mq='com.google.gwt.useragent.client.',rp='dir=',fq='g',dq='gecko',Yp='gecko1_8',Fp='i',aq='ie10',cq='ie8',bq='ie9',jq='java.lang.',pq='java.util.',vq='java.util.regex.',Lp='lang',_p='msie',Np='null',yp='ol',oq='org.timepedia.exporter.client.',Dp='ovwxp',$p='safari',zp='ul',eq='unknown',Zp='webkit';var _,_j={},cp={3:1,16:1,22:1},dp={2:1,16:1},jp={26:1},lp={27:1},ap={4:1},mp={16:1,25:1},$o={},bp={16:1},hp={16:1,20:1,21:1,24:1},ep={12:1},fp={16:1,20:1,24:1},gp={15:1},_o={16:1,22:1},kp={28:1},ip={17:1};ak(1,-1,$o,A);_.eQ=function B(a){return this===a};_.gC=function C(){return this.cZ};_.hC=function D(){return Dg(this)};_.tS=function F(){return this.cZ.e+op+bl(this.hC())};_.toString=function(){return this.tS()};_.tM=Xo;ak(4,1,{});ak(5,1,ap);_.eQ=function N(a){return Uh(a,4)&&R(this,Sh(a,4))};_.hC=function O(){return 42};_.tS=function Q(){return 'value: '+this.c.b+', log: '+Sh(this.b.b,25)};var J,K;ak(8,5,ap,T);ak(10,4,{},W);ak(13,1,{5:1},Z);ak(14,1,{},db);_.n=function eb(a){return a!=null&&Uh(a,5)};var ab=false;ak(16,1,{});_.c=false;_.d=false;ak(17,1,{},ub);ak(18,16,{},zb);var wb;ak(19,1,{},ac);var Bb,Cb,Db,Eb,Fb,Gb,Hb,Ib,Jb;ak(20,1,{6:1},jc,kc);_.b=0;var cc,dc,ec,fc,gc,hc;ak(21,1,{},nc);ak(23,1,{16:1,18:1,19:1});_.eQ=function qc(a){return this===a};_.hC=function rc(){return Dg(this)};_.tS=function sc(){return this.d};ak(22,23,{7:1,16:1,18:1,19:1},Ec);var tc,uc,vc,wc,xc,yc,zc,Ac,Bc,Cc;ak(24,1,{8:1,10:1},Hc);ak(25,1,{9:1,10:1},Kc);ak(26,1,{10:1,11:1},Mc);ak(27,1,{},Pc);var Qc;var Uc=0,Vc,Wc;ak(30,1,{},bd);ak(31,1,{});_.o=function hd(a){var b,c,d;c=a.i>=a.k?0:a.f[a.i];d=ad(dd,c);if(d!=null&&Ud(a,a.i,d)){return false}b=ad(ed,c);return b!=null&&Ud(a,a.i,b)};var dd,ed;ak(32,31,{},nd);var kd;ak(33,32,{},qd);_.o=function pd(a){var b,c;b=a.i>=a.k?0:a.f[a.i];c=ad((fd(),dd),b);return c==null||!Ud(a,a.i,c)};ak(34,31,{},xd);var sd,td;var yd,zd,Ad;ak(36,1,{},$d);_.b=0;_.c=0;_.d=0;_.e=0;_.h=0;_.i=0;_.k=0;_.l=0;_.m=0;ak(37,1,{},be);var ce,de;var ge;var je,ke,le;var oe;var te,ue;ak(44,1,{},Ee);var ye,ze,Ae,Be;var Fe,Ge,He,Ie,Je;var Me,Ne;var Qe,Re;var Ue,Ve;var Ye,Ze,$e;var bf,cf;ak(52,1,ep,kf);_.p=function lf(){return hf.b};_.q=function mf(a,b,c){switch(c){case 60:if(qe(a)){return true}a.i=a.h;if(we(a,b)){return true}a.i=a.h;return ef(a,b);case 13:case 10:return se(a);}return false};var hf;ak(53,1,ep,qf);_.p=function rf(){return of.b};_.q=function sf(a,b,c){switch(c){case 60:if(Sc(a,b)){return true}a.i=a.h;if(ne(a)){return true}a.i=a.h;if(af(a,b)){return true}a.i=a.h;return Le(a);case 120:return Pe(a,b);case 13:case 10:return se(a);}return false};var of;ak(54,1,ep,wf);_.p=function xf(){return uf.b};_.q=function yf(a,b,c){switch(c){case 60:if(qe(a)){return true}a.i=a.h;if(we(a,b)){return true}a.i=a.h;return ef(a,b);case 13:case 10:return se(a);case 99:return ie(a,b);}return false};var uf;ak(55,1,ep,Ff);_.p=function Gf(){return Af.b};_.q=function Hf(a,b,c){switch(c){case 60:if(ef(a,b)){return true}a.i=a.h;if(Dd(a,b)){return true}a.i=a.h;if(De(Df,a)){return true}a.i=a.h;return false;case 111:case 118:case 119:case 120:case 112:return fe(a,b);case 115:return md(Bf,a,b);case 108:return ae(Cf,a,b);}return false};var Af,Bf,Cf,Df;ak(56,1,ep,Pf);_.p=function Qf(){return Jf.b};_.q=function Rf(a,b,c){switch(c){case 60:if(ef(a,b)){return true}a.i=a.h;if(Xe(a)){return true}a.i=a.h;if(De(Nf,a)){return true}a.i=a.h;return false;case 115:return md(Kf,a,b);case 99:return ae(Lf,a,b);case 108:return ae(Mf,a,b);case 111:case 118:case 119:case 120:case 112:return fe(a,b);}return false};var Jf,Kf,Lf,Mf,Nf;ak(57,1,{},Wf);_.tS=function Xf(){return Il(this.b,this.c)};_.c=0;ak(63,1,{16:1,24:1});_.r=function bg(){return this.f};_.tS=function cg(){var a,b;a=this.cZ.e;b=this.r();return b!=null?a+Mp+b:a};ak(62,63,fp);ak(61,62,fp);ak(60,61,{13:1,16:1,20:1,24:1},ig);_.r=function lg(){hg(this);return this.d};_.s=function mg(){return this.c===fg?null:this.c};var fg;ak(67,1,{});var tg=0,ug=0,vg=0,wg=-1;ak(69,67,{},Og);var Kg;ak(72,1,{},Yg);_.t=function Zg(){var a={};var b=[];var c=arguments.callee.caller.caller;while(c){var d=this.v(c.toString());b.push(d);var e=Rp+d;var f=a[e];if(f){var g,h;for(g=0,h=f.length;g<h;g++){if(f[g]===c){return b}}}(f||(a[e]=[])).push(c);c=c.caller}return b};_.u=function $g(a){var b,c,d,e;d=this.A(a.c===(gg(),fg)?null:a.c);e=Ih(Oj,_o,23,d.length,0);for(b=0,c=e.length;b<c;b++){e[b]=new jl(d[b],null,-1)}ag(e)};_.v=function _g(a){return Rg(a)};_.w=function ah(a){var b,c,d,e;d=Uj().t();e=Ih(Oj,_o,23,d.length,0);for(b=0,c=e.length;b<c;b++){e[b]=new jl(d[b],null,-1)}ag(e)};_.A=function bh(a){return []};ak(74,72,{},gh);_.t=function hh(){return Ug(this.A(Xg()),this.B())};_.A=function ih(a){return fh(this,a)};_.B=function jh(){return 2};ak(73,74,{});_.t=function nh(){var a;a=Ug(lh(this,Xg()),3);a.length==0&&(a=Ug((new Yg).t(),1));return a};_.u=function oh(a){var b;b=lh(this,a.c===(gg(),fg)?null:a.c);mh(this,b)};_.v=function ph(a){var b,c,d,e;if(a.length==0){return Qp}e=zl(a);e.indexOf('at ')==0&&(e=wl(e,3));c=e.indexOf(Up);c!=-1&&(e=zl(xl(e,0,c))+zl(wl(e,e.indexOf(Vp,c)+1)));c=e.indexOf(Pp);if(c==-1){c=e.indexOf(op);if(c==-1){d=e;e=pp}else{d=zl(wl(e,c+1));e=zl(xl(e,0,c))}}else{b=e.indexOf(Wp,c);d=xl(e,c+1,b);e=zl(xl(e,0,c))}c=pl(e,Gl(46));c!=-1&&(e=wl(e,c+1));return (e.length>0?e:Qp)+Sp+d};_.w=function qh(a){var b;b=Uj().t();mh(this,b)};_.A=function rh(a){return lh(this,a)};_.C=function sh(a){return a};_.B=function th(){return 3};ak(75,73,{},vh);_.C=function wh(a){return -1};ak(76,1,{});ak(77,76,{},Bh);_.b=pp;ak(81,1,{},Dh);_.qI=0;var Lh,Mh;var Tj=-1;ak(95,1,gp,jk);_.D=function kk(){return Yp};_.F=function lk(){var b=navigator.userAgent.toLowerCase();var c=function(a){return parseInt(a[1])*1000+parseInt(a[2])};if(function(){return b.indexOf(Zp)!=-1}())return $p;if(function(){return b.indexOf(_p)!=-1&&$doc.documentMode>=10}())return aq;if(function(){return b.indexOf(_p)!=-1&&$doc.documentMode>=9}())return bq;if(function(){return b.indexOf(_p)!=-1&&$doc.documentMode>=8}())return cq;if(function(){return b.indexOf(dq)!=-1}())return Yp;return eq};ak(96,1,gp,nk);_.D=function ok(){return aq};_.F=function pk(){var b=navigator.userAgent.toLowerCase();var c=function(a){return parseInt(a[1])*1000+parseInt(a[2])};if(function(){return b.indexOf(Zp)!=-1}())return $p;if(function(){return b.indexOf(_p)!=-1&&$doc.documentMode>=10}())return aq;if(function(){return b.indexOf(_p)!=-1&&$doc.documentMode>=9}())return bq;if(function(){return b.indexOf(_p)!=-1&&$doc.documentMode>=8}())return cq;if(function(){return b.indexOf(dq)!=-1}())return Yp;return eq};ak(97,1,gp,rk);_.D=function sk(){return cq};_.F=function tk(){var b=navigator.userAgent.toLowerCase();var c=function(a){return parseInt(a[1])*1000+parseInt(a[2])};if(function(){return b.indexOf(Zp)!=-1}())return $p;if(function(){return b.indexOf(_p)!=-1&&$doc.documentMode>=10}())return aq;if(function(){return b.indexOf(_p)!=-1&&$doc.documentMode>=9}())return bq;if(function(){return b.indexOf(_p)!=-1&&$doc.documentMode>=8}())return cq;if(function(){return b.indexOf(dq)!=-1}())return Yp;return eq};ak(98,1,gp,vk);_.D=function wk(){return bq};_.F=function xk(){var b=navigator.userAgent.toLowerCase();var c=function(a){return parseInt(a[1])*1000+parseInt(a[2])};if(function(){return b.indexOf(Zp)!=-1}())return $p;if(function(){return b.indexOf(_p)!=-1&&$doc.documentMode>=10}())return aq;if(function(){return b.indexOf(_p)!=-1&&$doc.documentMode>=9}())return bq;if(function(){return b.indexOf(_p)!=-1&&$doc.documentMode>=8}())return cq;if(function(){return b.indexOf(dq)!=-1}())return Yp;return eq};ak(99,1,gp,zk);_.D=function Ak(){return $p};_.F=function Bk(){var b=navigator.userAgent.toLowerCase();var c=function(a){return parseInt(a[1])*1000+parseInt(a[2])};if(function(){return b.indexOf(Zp)!=-1}())return $p;if(function(){return b.indexOf(_p)!=-1&&$doc.documentMode>=10}())return aq;if(function(){return b.indexOf(_p)!=-1&&$doc.documentMode>=9}())return bq;if(function(){return b.indexOf(_p)!=-1&&$doc.documentMode>=8}())return cq;if(function(){return b.indexOf(dq)!=-1}())return Yp;return eq};ak(100,1,{});_.tS=function Ek(){return rg(this.b)};ak(101,61,fp,Gk,Hk);ak(103,1,{},Kk);_.tS=function Tk(){return ((this.c&2)!=0?'interface ':(this.c&1)!=0?pp:'class ')+this.e};_.c=0;_.d=0;ak(104,61,fp,Vk);ak(105,61,hp,Xk,Yk);ak(106,61,fp,$k,_k);ak(110,61,fp,dl);var el;ak(112,105,hp,hl);ak(113,1,{16:1,23:1},jl);_.tS=function kl(){return this.b+'.'+this.e+Pp+(this.c!=null?this.c:'Unknown Source')+(this.d>=0?Rp+this.d:pp)+Wp};_.d=0;_=String.prototype;_.cM={1:1,16:1,17:1,18:1};_.eQ=function El(a){return nl(this,a)};_.hC=function Hl(){return Ol(this)};_.tS=_.toString;var Jl,Kl=0,Ll;ak(115,1,ip,Tl,Ul);_.tS=function Vl(){return this.b.b};ak(116,1,ip,am,bm,cm);_.tS=function dm(){return this.b.b};ak(117,106,fp,fm);ak(119,61,fp,im);ak(120,1,{});_.G=function mm(a){throw new im('Add not supported on this collection')};_.H=function nm(a){var b;b=km(this.I(),a);return !!b};_.K=function om(){return this.L(Ih(Nj,_o,0,this.J(),0))};_.L=function pm(a){var b,c,d;d=this.J();a.length<d&&(a=Gh(a,d));c=this.I();for(b=0;b<d;++b){Kh(a,b,c.O())}a.length>d&&Kh(a,d,null);return a};_.tS=function qm(){return lm(this)};ak(122,1,jp);_.eQ=function tm(a){var b,c,d,e,f;if(a===this){return true}if(!Uh(a,26)){return false}e=Sh(a,26);if(this.e!=e.e){return false}for(c=new Sm((new Nm(e)).b);sn(c.b);){b=Sh(tn(c.b),27);d=b.P();f=b.Q();if(!(d==null?this.d:Uh(d,1)?Dm(this,Sh(d,1)):Cm(this,d,~~qg(d)))){return false}if(!Fo(f,d==null?this.c:Uh(d,1)?Bm(this,Sh(d,1)):Am(this,d,~~qg(d)))){return false}}return true};_.hC=function um(){var a,b,c;c=0;for(b=new Sm((new Nm(this)).b);sn(b.b);){a=Sh(tn(b.b),27);c+=a.hC();c=~~c}return c};_.tS=function vm(){var a,b,c,d;d='{';a=false;for(c=new Sm((new Nm(this)).b);sn(c.b);){b=Sh(tn(c.b),27);a?(d+=iq):(a=true);d+=pp+b.P();d+=Cp;d+=pp+b.Q()}return d+'}'};ak(121,122,jp);_.M=function Im(a,b){return Xh(a)===Xh(b)||a!=null&&og(a,b)};_.d=false;_.e=0;ak(124,120,kp);_.eQ=function Lm(a){var b,c,d;if(a===this){return true}if(!Uh(a,28)){return false}c=Sh(a,28);if(c.b.e!=this.J()){return false}for(b=new Sm(c.b);sn(b.b);){d=Sh(tn(b.b),27);if(!this.H(d)){return false}}return true};_.hC=function Mm(){var a,b,c;a=0;for(b=this.I();b.N();){c=b.O();if(c!=null){a+=qg(c);a=~~a}}return a};ak(123,124,kp,Nm);_.H=function Om(a){var b,c,d;if(Uh(a,27)){b=Sh(a,27);c=b.P();if(ym(this.b,c)){d=zm(this.b,c);return ho(b.Q(),d)}}return false};_.I=function Pm(){return new Sm(this.b)};_.J=function Qm(){return this.b.e};ak(125,1,{},Sm);_.N=function Tm(){return sn(this.b)};_.O=function Um(){return Sh(tn(this.b),27)};ak(127,1,lp);_.eQ=function Xm(a){var b;if(Uh(a,27)){b=Sh(a,27);if(Fo(this.P(),b.P())&&Fo(this.Q(),b.Q())){return true}}return false};_.hC=function Ym(){var a,b;a=0;b=0;this.P()!=null&&(a=qg(this.P()));this.Q()!=null&&(b=qg(this.Q()));return a^b};_.tS=function Zm(){return this.P()+Cp+this.Q()};ak(126,127,lp,$m);_.P=function _m(){return null};_.Q=function an(){return this.b.c};_.R=function bn(a){return Gm(this.b,a)};ak(128,127,lp,dn);_.P=function en(){return this.b};_.Q=function fn(){return Bm(this.c,this.b)};_.R=function gn(a){return Hm(this.c,this.b,a)};ak(129,120,{25:1});_.S=function kn(a,b){throw new im('Add not supported on this list')};_.G=function ln(a){this.S(this.J(),a);return true};_.eQ=function nn(a){var b,c,d,e,f;if(a===this){return true}if(!Uh(a,25)){return false}f=Sh(a,25);if(this.J()!=f.J()){return false}d=this.I();e=f.I();while(d.b<d.c.J()){b=tn(d);c=tn(e);if(!(b==null?c==null:og(b,c))){return false}}return true};_.hC=function on(){var a,b,c;b=1;a=this.I();while(a.b<a.c.J()){c=tn(a);b=31*b+(c==null?0:qg(c));b=~~b}return b};_.I=function qn(){return new un(this)};ak(130,1,{},un);_.N=function vn(){return sn(this)};_.O=function wn(){return tn(this)};_.b=0;ak(131,129,mp,Gn);_.S=function Hn(a,b){yn(this,a,b)};_.G=function In(a){return zn(this,a)};_.H=function Jn(a){return Cn(this,a,0)!=-1};_.T=function Kn(a){return Bn(this,a)};_.J=function Ln(){return this.c};_.K=function Pn(){return En(this)};_.L=function Qn(a){return Fn(this,a)};_.c=0;ak(132,129,mp,Sn);_.H=function Tn(a){return jn(this,a)!=-1};_.T=function Un(a){return mn(a,this.b.length),this.b[a]};_.J=function Vn(){return this.b.length};_.K=function Wn(){return Eh(this.b)};_.L=function Xn(a){var b,c;c=this.b.length;a.length<c&&(a=Gh(a,c));for(b=0;b<c;++b){Kh(a,b,this.b[b])}a.length>c&&Kh(a,c,null);return a};var Yn;ak(134,129,mp,_n);_.H=function ao(a){return false};_.T=function bo(a){throw new $k};_.J=function co(){return 0};ak(135,61,fp,fo);ak(136,121,{16:1,26:1},io);ak(137,127,lp,ko);_.P=function lo(){return this.b};_.Q=function mo(){return this.c};_.R=function no(a){var b;b=this.c;this.c=a;return b};ak(138,61,fp,po);ak(140,129,mp);_.S=function so(a,b){yn(this.b,a,b)};_.G=function to(a){return zn(this.b,a)};_.H=function uo(a){return Cn(this.b,a,0)!=-1};_.T=function vo(a){return Bn(this.b,a)};_.I=function wo(){return new un(this.b)};_.J=function xo(){return this.b.c};_.K=function yo(){return En(this.b)};_.L=function zo(a){return Fn(this.b,a)};_.tS=function Ao(){return lm(this.b)};ak(139,140,mp,Eo);ak(142,1,{},Lo);_.b=null;_.d=0;ak(143,100,{},No,Oo);ak(145,1,{});ak(144,145,{},To);var Vo;var np=Eg();var cj=Mk(jq,'Object',1),Gi=Mk(kq,'Scheduler',67),Fi=Mk(kq,'JavaScriptObject$',64),Nj=Lk(lq,'Object;',150,cj),Jj=Pk('boolean',' Z'),Qj=Lk(pp,'[Z',152,Jj),jj=Mk(jq,'Throwable',63),Zi=Mk(jq,'Exception',62),dj=Mk(jq,'RuntimeException',61),ej=Mk(jq,'StackTraceElement',113),Oj=Lk(lq,'StackTraceElement;',153,ej),Oi=Mk('com.google.gwt.lang.','SeedUtil',88),Yi=Mk(jq,'Enum',23),Zh=Pk('char',' C'),Kj=Lk(pp,'[C',154,Zh),Xi=Mk(jq,'Class',103),ij=Mk(jq,Op,2),Pj=Lk(lq,'String;',151,ij),Wi=Mk(jq,'ClassCastException',104),Ei=Mk(kq,'JavaScriptException',60),gj=Mk(jq,'StringBuilder',116),Vi=Mk(jq,'ArrayStoreException',101),Pi=Mk(mq,'UserAgentImplGecko1_8',95),Ti=Mk(mq,'UserAgentImplSafari',99),Qi=Mk(mq,'UserAgentImplIe10',96),Si=Mk(mq,'UserAgentImplIe9',98),Ri=Mk(mq,'UserAgentImplIe8',97),aj=Mk(jq,'NullPointerException',110),$i=Mk(jq,'IllegalArgumentException',105),Ni=Mk(nq,'StringBufferImpl',76),Ij=Mk(oq,'ExporterBaseImpl',145),Hj=Mk(oq,'ExporterBaseActual',144),Li=Mk(nq,'StackTraceCreator$Collector',72),Ki=Mk(nq,'StackTraceCreator$CollectorMoz',74),Ji=Mk(nq,'StackTraceCreator$CollectorChrome',73),Ii=Mk(nq,'StackTraceCreator$CollectorChromeNoSourceMap',75),Mi=Mk(nq,'StringBufferImplAppend',77),Hi=Mk(nq,'SchedulerImpl',69),uj=Mk(pq,'AbstractMap',122),qj=Mk(pq,'AbstractHashMap',121),lj=Mk(pq,'AbstractCollection',120),vj=Mk(pq,'AbstractSet',124),nj=Mk(pq,'AbstractHashMap$EntrySet',123),mj=Mk(pq,'AbstractHashMap$EntrySetIterator',125),tj=Mk(pq,'AbstractMapEntry',127),oj=Mk(pq,'AbstractHashMap$MapEntryNull',126),pj=Mk(pq,'AbstractHashMap$MapEntryString',128),Aj=Mk(pq,'HashMap',136),ci=Mk(qq,'WordCleaner_ExporterImpl',14),di=Mk(qq,'WordCleaner',13),Bj=Mk(pq,'MapEntryImpl',137),fj=Mk(jq,'StringBuffer',115),sj=Mk(pq,'AbstractList',129),wj=Mk(pq,'ArrayList',131),rj=Mk(pq,'AbstractList$IteratorImpl',130),ei=Mk(rq,'OfficeImportFunction',16),gi=Mk(rq,'WordImportFunction',18),ai=Mk(sq,'Logged',5),_h=Mk(sq,'Logged$6',8),$h=Mk('com.ephox.functional.closures.','Thunk',4),xi=Ok(tq,'ReplacementRuleSet'),Mj=Lk('[Lcom.ephox.tord.wordhtmlfilter.','ReplacementRuleSet;',155,xi),kj=Mk(jq,'UnsupportedOperationException',119),zi=Mk(tq,'StepOne',53),Bi=Mk(tq,'StepTwoFilterStyles',55),Ai=Mk(tq,'StepThree',54),yi=Mk(tq,'StepLast',52),Ci=Mk(tq,'StepTwoRemoveStyles',56),Rj=Lk(pp,'[[C',156,Kj),ui=Mk(tq,'ReadBuffer',36),Di=Mk(tq,'WriteBuffer',57),fi=Mk(rq,'Scrub',17),hi=Mk(uq,'ListImporter',19),Ui=Mk('com.googlecode.gwtx.java.util.impl.client.','PatternImpl',100),Gj=Mk(vq,'Pattern',143),Fj=Mk(vq,'Matcher',142),yj=Mk(pq,'Collections$EmptyList',134),bi=Mk('com.ephox.functional.factory.','Thunks$1',10),xj=Mk(pq,'Arrays$ArrayList',132),oi=Mk(tq,'CharMap',27),wi=Mk(tq,'RemoveLink',44),qi=Mk(tq,'ModifySingleStyle',31),si=Mk(tq,'ModifyStyleAttribute',32),vi=Mk(tq,'RemoveAttributeByName',37),ri=Mk(tq,'ModifyStyleAttributeOnlyUsingMustKeepList',33),_i=Mk(jq,'IndexOutOfBoundsException',106),Cj=Mk(pq,'NoSuchElementException',138),hj=Mk(jq,'StringIndexOutOfBoundsException',117),Sj=Lk(pp,'[[[C',157,Rj),pi=Mk(tq,'IndexedStrings',30),ti=Mk(tq,'ModifyStyleDefinition',34),ii=Mk(uq,'ListInfoStack',21),ji=Mk(uq,'ListInfo',20),ni=Mk(wq,'ListItemData',26),li=Mk(wq,'ContentData',24),mi=Mk(wq,'ListAggregationData',25),bj=Mk(jq,'NumberFormatException',112),ki=Nk(uq,'ListTagAndType',22,Fc),Lj=Lk('[Lcom.ephox.tord.lists.','ListTagAndType;',158,ki),Ej=Mk(pq,'Vector',140),Dj=Mk(pq,'Stack',139),zj=Mk(pq,'EmptyStackException',135);if (com_ephox_keurig_Keurig) com_ephox_keurig_Keurig.onScriptLoad(gwtOnLoad);})();