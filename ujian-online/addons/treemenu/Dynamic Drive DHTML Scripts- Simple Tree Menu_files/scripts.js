//** DD Custom Split Menu && Scroll Top Top function
// Modified version of Anylink CSS Menu: http://www.dynamicdrive.com/dynamicindex1/anylinkcss.htm

if (typeof dd_domreadycheck=="undefined") //global variable to detect if DOM is ready
	var dd_domreadycheck=false

var ddsplitmenu={

menusmap: {},
preloadimages: [],
effects: {delayhide: 200, shadow:{enabled:false, opacity:0.3, depth: [5, 5]}, fade:{enabled:true, duration:300}}, //customize menu effects

dimensions: {},
ismobile:navigator.userAgent.match(/(iPad)|(iPhone)|(iPod)|(android)|(webOS)/i) != null, //boolean check for popular mobile browsers

getoffset:function(what, offsettype){
	return (what.offsetParent)? what[offsettype]+this.getoffset(what.offsetParent, offsettype) : what[offsettype]
},

getoffsetof:function(el){
	el._offsets={left:this.getoffset(el, "offsetLeft"), top:this.getoffset(el, "offsetTop"), h: el.offsetHeight}
	el.nextSibling._offsets=el._offsets
},

getdimensions:function(menu){
	this.dimensions={anchorw:menu.anchorobj.offsetWidth, anchorh:menu.anchorobj.offsetHeight,
		docwidth:(window.innerWidth ||this.standardbody.clientWidth)-20,
		docheight:(window.innerHeight ||this.standardbody.clientHeight)-15,
		docscrollx:window.pageXOffset || this.standardbody.scrollLeft,
		docscrolly:window.pageYOffset || this.standardbody.scrollTop
	}
	if (!this.dimensions.dropmenuw){
		this.dimensions.dropmenuw=menu.dropmenu.offsetWidth
		this.dimensions.dropmenuh=menu.dropmenu.offsetHeight
	}
},

isContained:function(m, e){
	var e=window.event || e
	var c=e.relatedTarget || ((e.type=="mouseover")? e.fromElement : e.toElement)
	while (c && c!=m)try {c=c.parentNode} catch(e){c=m}
	if (c==m)
		return true
	else
		return false
},

setopacity:function(el, value){
	el.style.opacity=value
	if (typeof el.style.opacity!="string"){ //if it's not a string (ie: number instead), it means property not supported
		el.style.MozOpacity=value
		if (document.all && typeof el.style.filter=="string"){
			el.style.filter="progid:DXImageTransform.Microsoft.alpha(opacity="+ value*100 +")"
		}
	}
},

showmenu:function(menuid){
	var menu=ddsplitmenu.menusmap[menuid]
	clearTimeout(menu.hidetimer)
	this.getoffsetof(menu.anchorobj.previousSibling)
	this.getdimensions(menu)
	var posx=menu.anchorobj._offsets.left + (menu.orientation=="lr"? this.dimensions.anchorw : 0) //base x pos
	var posy=menu.anchorobj._offsets.top+this.dimensions.anchorh - (menu.orientation=="lr"? this.dimensions.anchorh : 0)//base y pos
	if (posx+this.dimensions.dropmenuw>this.dimensions.docscrollx+this.dimensions.docwidth){ //drop left instead?
		posx=posx-this.dimensions.dropmenuw + (menu.orientation=="lr"? -this.dimensions.anchorw : this.dimensions.anchorw)
	}
	if (posy+this.dimensions.dropmenuh>this.dimensions.docscrolly+this.dimensions.docheight){  //drop up instead?
		posy=Math.max(posy-this.dimensions.dropmenuh - (menu.orientation=="lr"? -this.dimensions.anchorh : this.dimensions.anchorh), this.dimensions.docscrolly) //position above anchor or window's top edge
	}
	if (this.effects.fade.enabled){
		this.setopacity(menu.dropmenu, 0) //set opacity to 0 so menu appears hidden initially
	}
	menu.dropmenu.setcss({left:posx+'px', top:posy+'px', visibility:'visible'})
	if (this.effects.fade.enabled){
		clearInterval(menu.animatetimer)
		menu.curanimatedegree=0
		menu.starttime=new Date().getTime() //get time just before animation is run
		menu.animatetimer=setInterval(function(){ddsplitmenu.revealmenu(menuid)}, 20)
	}
},

revealmenu:function(menuid){
	var menu=ddsplitmenu.menusmap[menuid]
	var elapsed=new Date().getTime()-menu.starttime //get time animation has run
	if (elapsed<this.effects.fade.duration){
		this.setopacity(menu.dropmenu, menu.curanimatedegree)
	}
	else{
		clearInterval(menu.animatetimer)
		this.setopacity(menu.dropmenu, 1)
		menu.dropmenu.style.filter=""
	}
	menu.curanimatedegree=(1-Math.cos((elapsed/this.effects.fade.duration)*Math.PI)) / 2
},

setcss:function(param){
	for (prop in param){
		this.style[prop]=param[prop]
	}
},

setcssclass:function(el, targetclass, action){
	var needle=new RegExp("(^|\\s+)"+targetclass+"($|\\s+)", "ig")
	if (action=="check")
		return needle.test(el.className)
	else if (action=="remove")
		el.className=el.className.replace(needle, "")
	else if (action=="add" && !needle.test(el.className))
		el.className+=" "+targetclass
},

hidemenu:function(menuid){
	var menu=ddsplitmenu.menusmap[menuid]
	clearInterval(menu.animatetimer)
	menu.dropmenu.setcss({visibility:'hidden', left:0, top:0})
},

getElementsByClass:function(targetclass){
	if (document.querySelectorAll)
		return document.querySelectorAll("."+targetclass)
	else{
		var classnameRE=new RegExp("(^|\\s+)"+targetclass+"($|\\s+)", "i") //regular expression to screen for classname
		var pieces=[]
		var alltags=document.all? document.all : document.getElementsByTagName("*")
		for (var i=0; i<alltags.length; i++){
			if (typeof alltags[i].className=="string" && alltags[i].className.search(classnameRE)!=-1)
				pieces[pieces.length]=alltags[i]
		}
		return pieces
	}
},

addEvent:function(targetarr, functionref, tasktype){
	if (targetarr.length>0){
		var target=targetarr.shift()
		if (target.addEventListener)
			target.addEventListener(tasktype, functionref, false)
		else if (target.attachEvent)
			target.attachEvent('on'+tasktype, function(){return functionref.call(target, window.event)})
		this.addEvent(targetarr, functionref, tasktype)
	}
},

domready:function(functionref){ //based on code from the jQuery library
	if (dd_domreadycheck){
		functionref()
		return
	}
	// Mozilla, Opera and webkit nightlies currently support this event
	if (document.addEventListener) {
		// Use the handy event callback
		document.addEventListener("DOMContentLoaded", function(){
			document.removeEventListener("DOMContentLoaded", arguments.callee, false )
			functionref();
			dd_domreadycheck=true
		}, false )
	}
	else if (document.attachEvent){
		// If IE and not an iframe
		// continually check to see if the document is ready
		if ( document.documentElement.doScroll && window == window.top) (function(){
			if (dd_domreadycheck) return
			try{
				// If IE is used, use the trick by Diego Perini
				// http://javascript.nwbox.com/IEContentLoaded/
				document.documentElement.doScroll("left")
			}catch(error){
				setTimeout( arguments.callee, 0)
				return;
			}
			//and execute any waiting functions
			functionref();
			dd_domreadycheck=true
		})();
	}
	if (document.attachEvent && parent.length>0) //account for page being in IFRAME, in which above doesn't fire in IE
		this.addEvent([window], function(){functionref()}, "load");
},

addState:function(anchorobj, state){
	if (anchorobj.getAttribute('data-image')){
		var imgobj=(anchorobj.tagName=="IMG")? anchorobj : anchorobj.getElementsByTagName('img')[0]
		if (imgobj){
			imgobj.src=(state=="add")? anchorobj.getAttribute('data-overimage') : anchorobj.getAttribute('data-image')
		}
	}
	else
		ddsplitmenu.setcssclass(anchorobj, "selectedanchor", state)
},


setupmenu:function(targetclass, anchorobj, pos){
	this.standardbody=(document.compatMode=="CSS1Compat")? document.documentElement : document.body
	var relattr=anchorobj.getAttribute("rel")
	var dropmenuid=relattr.replace(/\[(\w+)\]/, '')
	var menu=this.menusmap[targetclass+pos]={
		id: targetclass+pos,
		anchorobj: anchorobj,	
		dropmenu: document.getElementById(dropmenuid),
		revealtype: (relattr.length!=dropmenuid.length && RegExp.$1=="click") || ddsplitmenu.ismobile? "click" : "mouseover",
		orientation: anchorobj.getAttribute("rev")=="lr"? "lr" : "ud"
	}
	menu.anchorobj._internalID=targetclass+pos
	menu.anchorobj._isanchor=true
	menu.dropmenu._internalID=targetclass+pos
	document.body.appendChild(menu.dropmenu) //move drop down div to end of page
	menu.dropmenu.setcss=this.setcss
	this.addEvent([menu.anchorobj, menu.dropmenu], function(e){ //MOUSEOVER event for anchor, dropmenu
		var menu=ddsplitmenu.menusmap[this._internalID]
		if (this._isanchor && menu.revealtype=="mouseover" && !ddsplitmenu.isContained(this, e)){ //event for anchor
			ddsplitmenu.showmenu(menu.id)
			ddsplitmenu.addState(this, "add")
		}
		else if (typeof this._isanchor=="undefined"){ //event for drop down menu and shadow
			clearTimeout(menu.hidetimer)
		}
	}, "mouseover")
	this.addEvent([menu.anchorobj, menu.dropmenu], function(e){ //MOUSEOUT event for anchor, dropmenu
		if (!ddsplitmenu.isContained(this, e)){
			var menu=ddsplitmenu.menusmap[this._internalID]
			menu.hidetimer=setTimeout(function(){
				ddsplitmenu.addState(menu.anchorobj, "remove")
				ddsplitmenu.hidemenu(menu.id)
			}, ddsplitmenu.effects.delayhide)
		}
	}, "mouseout")
	this.addEvent([menu.anchorobj, menu.dropmenu], function(e){ //CLICK event for anchor, dropmenu
		var menu=ddsplitmenu.menusmap[this._internalID]
		if ( this._isanchor && menu.revealtype=="click"){
			if (menu.dropmenu.style.visibility=="visible")
				ddsplitmenu.hidemenu(menu.id)
			else{
				ddsplitmenu.addState(this, "add")
				ddsplitmenu.showmenu(menu.id)
			}
			if (e.preventDefault)
				e.preventDefault()
			return false
		}
		else
			menu.hidetimer=setTimeout(function(){ddsplitmenu.hidemenu(menu.id)}, 10)
	}, "click")
},

init:function(targetclass){
	this.domready(function(){ddsplitmenu.trueinit(targetclass)})
},

trueinit:function(targetclass){
	var anchors=this.getElementsByClass(targetclass)
	var preloadimages=this.preloadimages
	for (var i=0; i<anchors.length; i++){
		if (anchors[i].getAttribute('data-image')){ //preload anchor image?
			preloadimages[preloadimages.length]=new Image()
			preloadimages[preloadimages.length-1].src=anchors[i].getAttribute('data-image')
		}
		if (anchors[i].getAttribute('data-overimage')){ //preload anchor image?
			preloadimages[preloadimages.length]=new Image()
			preloadimages[preloadimages.length-1].src=anchors[i].getAttribute('data-overimage')
		}
		this.setupmenu(targetclass, anchors[i], i)
	}
}

}



function dd_scrolltotop(duration){
	duration = duration || 500
	var rootel = (document.compatMode =="BackCompat")? document.body : document.documentElement
	if (rootel.scrollTop == 0) // in some browsers such as chrome, use document.body instead of document.documentElement
		rootel = document.body
	var curscrolltop = rootel.scrollTop, scrolltimer, elapsedtime, starttime = new Date().getTime(), animatedegree = 0
	var totaldis = curscrolltop
	clearTimeout(scrolltimer)
	function jumptop(){
		elapsedtime = new Date().getTime() - starttime
		if (elapsedtime < duration){
			rootel.scrollTop = totaldis - (totaldis * (1-Math.cos((elapsedtime/duration)*Math.PI)) / 2)
			scrolltimer = setTimeout(function(){jumptop()}, 10)
		}
	}
	jumptop()
}