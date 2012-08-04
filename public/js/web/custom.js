/*
	Template Name: goGreen
	Template URI: http://themeforest.net/user/unitedthemes
	Author: UnitedThemes
	Author URI: http://www.unitedthemes.com
*/


/* ------------------------------------------------
   Nivo Slider
------------------------------------------------ */
$(window).load(function() {
	/*********************
	Nivo Slider index.html
	*********************/
	$('#slider ul').nivoSlider({
		effect:'random', 		//Specify sets like: 'fold,fade,sliceDown'
		animSpeed:400, 			//Slide transition speed
		pauseTime:3000,
		startSlide:0, 			//Set starting Slide (0 index)
		directionNav:true, 		//Next & Prev
		directionNavHide:true, 	//Only show on hover
		controlNav:false, 		//1,2,3...
		keyboardNav:true, 		//Use left & right arrows
		pauseOnHover:true, 		//Stop animation while hovering
	});
});


/* ------------------------------------------------
	main custom javascripts
------------------------------------------------ */

$(document).ready(function(){

	/*********************
	quicksand
	*********************/
	$clientsHolder = $('ul.portfolio');
	$clientsClone = $clientsHolder.clone();

	$('.filter_portfolio a').click(function(e) {
		e.preventDefault();

		$filterClass = $(this).attr('class');

		$('.filter_portfolio li').removeClass('active');
		$(this).parent().addClass('active');

		if($filterClass == 'all'){
			$filters = $clientsClone.find('li');
		} else {
			$filters = $clientsClone.find('li[data-type~='+ $filterClass +']');
		}

	   $clientsHolder.quicksand( $filters, {
			duration: 1000,
			easing: 'easeInOutQuint'
		}, function(){
			 $("a[data-pretty^='prettyPhoto']").prettyPhoto({animationSpeed:'fast',theme:'light_square',slideshow:5000});
			 	$('.zoom img').each(function() {
                $(this).hover(
                    function() {
                        $(this).stop().animate({ opacity: 0.5 }, 'slow');
                    },
                   function() {
                       $(this).stop().animate({ opacity: 1.0 }, 'slow');
                   })
     			});
		});

	});

	/*********************
	(hover effect)
	*********************/
	$('.zoom img, #footer ul.social li').each(function() {
                $(this).hover(
                    function() {
                        $(this).stop().animate({ opacity: 0.5 }, 'slow');
                    },
                   function() {
                       $(this).stop().animate({ opacity: 1.0 }, 'slow');
                   })
     });


	/*********************
	Pretty Photo
	*********************/
	$("a[data-pretty^='prettyPhoto']").prettyPhoto({animationSpeed:'fast',theme:'light_square',autoplay_slideshow: true,slideshow:5000});
	$("a[data-pretty='flickr']").prettyPhoto({animationSpeed:'fast',theme:'light_square',slideshow:5000});

	/*********************
	tabs and accordion
	*********************/
	$(".tabs").tabs(".panes > div", {effect: 'fade'});

	$("#accordion").tabs("#accordion div.pane", {tabs: 'h3', effect: 'slide', initialIndex: null});

	/*********************
	Contact Form
	*********************/
	$('form.validateform').submit(function(){

		var f = $(this).find('.cform li'),
		ferror = false,
		emailExp = /^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i;

		f.children('input').each(function(){ // run all inputs

		    var i = $(this); // current input
		    var rule = i.attr('data-rule');

		    if( rule != undefined ){
			var ierror=false; // error flag for current input
			var pos = rule.indexOf( ':', 0 );
			if( pos >= 0 ){
			    var exp = rule.substr( pos+1, rule.length );
			    rule = rule.substr(0, pos);
			}else{
			    rule = rule.substr( pos+1, rule.length );
			}

			switch( rule ){
			    case 'required':
				if( i.val()=='' ){ ferror=ierror=true; }
				break;

			    case 'maxlen':
				if( i.val().length<parseInt(exp) ){ ferror=ierror=true; }
				break;

			    case 'email':
				if( !emailExp.test(i.val()) ){ ferror=ierror=true; }
				break;


			    case 'checked':
				if( !i.attr('checked') ){ ferror=ierror=true; }
				break;

			    case 'regexp':
				exp = new RegExp(exp);
				if( !exp.test(i.val()) ){ ferror=ierror=true; }
				break;
			  }
			  i.next('.valmsg').html( ( ierror ? (i.attr('data-msg') != undefined ? i.attr('data-msg') : 'wrong Input') : '' ) ).show('blind');
		    }
		});
		if( ferror ) return false; // do not run contact.php
			else var str = $(this).serialize();

			   $.ajax({
			   type: "POST",
			   url: "contact.php",
			   data: str,
			   success: function(msg){

			$("#errormessage").ajaxComplete(function(event, request, settings){

			if(msg == 'OK')
			{
				$('#errormessage').hide();
				$("#sendmessage").show('blind');

			}
			else
			{
				$('#errormessage').show('blind');
				result = msg;
			}

			$(this).html(result);});}});
				return false;
	});

	/*********************
	VideoJS
	*********************/
	VideoJS.setupAllWhenReady();

	/*********************
	flickr plugin
	*********************/
	 $('#flickr').jflickrfeed({
			limit: 8,
			qstrings: {
				id: '60616902@N03'
			},
			itemTemplate: '<li>'+
							'<a data-pretty="flickr[gallery]" href="{{image}}" title="{{title}}">' +
								'<img src="{{image_s}}" alt="{{title}}" />' +
							'</a>' +
						  '</li>'
		}, function(data) {
			$('#flickr a').prettyPhoto();
	});

	$(".scrollable").scrollable({ vertical: true, mousewheel: true });
	/*********************
	twitter plugin
	*********************/
    $(".tweet").tweet({
        join_text: "auto",
        username: "b2ba_uk",
        count: 4,
        auto_join_text_default: "we said,",
        auto_join_text_ed: "we",
        auto_join_text_ing: "we were",
        auto_join_text_reply: "we replied",
        auto_join_text_url: "we were checking out",
        loading_text: "loading tweets..."
	});

	$('.[href=#top]').click(function(){
        $('html, body').animate({scrollTop:0}, 1000);
        return false;
    });

	/*********************
	Animation for all forms
	*********************/

	$('input').each(function() {
		 $("label[for="+$(this).attr("id")+"]").css( "position", "absolute" ).css("z-index", "2");
        if ( $(this).val() != '') {
			 $("label[for="+$(this).attr("id")+"]").hide('slide', 'fast');
		 }
	});

	$('textarea').each(function() {
		$("label[for="+$(this).attr("id")+"]").css( "position", "absolute" ).css("z-index", "2");
		if( $(this).val() != ''){
			 $("label[for="+$(this).attr("id")+"]").hide('slide', 'fast');
		 }
	});

	$('input').focus(function(){ 
	    if( $(this).val() == "" ){
	        $("label[for="+$(this).attr("id")+"]").hide('slide', 'fast');
	    }
	});
	
	$('input').blur(function(){
        if( $(this).val() == "" ){
		    $("label[for="+$(this).attr("id")+"]").show('slide', 'fast');
		}
	});

	$('textarea').focus(function(){
	    if( $(this).val() == "" ){
            $("label[for="+$(this).attr("id")+"]").hide('slide', 'fast');
        }
    });
    
    $('textarea').blur(function(){
        if( $(this).val() == "" ){
	        $("label[for="+$(this).attr("id")+"]").show('slide', 'fast');
        }
	});

});



/*!
 * jQuery Tools v1.2.5 - The missing UI library for the Web
 *
 * overlay/overlay.js
 * overlay/overlay.apple.js
 * scrollable/scrollable.js
 * scrollable/scrollable.autoscroll.js
 * scrollable/scrollable.navigator.js
 * tabs/tabs.js
 * tabs/tabs.slideshow.js
 * tooltip/tooltip.js
 * tooltip/tooltip.dynamic.js
 *
 * NO COPYRIGHTS OR LICENSES. DO WHAT YOU LIKE.
 *
 * http://flowplayer.org/tools/
 *
 */
(function(a){a.tools=a.tools||{version:"v1.2.5"},a.tools.overlay={addEffect:function(a,b,d){c[a]=[b,d]},conf:{close:null,closeOnClick:!0,closeOnEsc:!0,closeSpeed:"fast",effect:"default",fixed:!a.browser.msie||a.browser.version>6,left:"center",load:!1,mask:null,oneInstance:!0,speed:"normal",target:null,top:"10%"}};var b=[],c={};a.tools.overlay.addEffect("default",function(b,c){var d=this.getConf(),e=a(window);d.fixed||(b.top+=e.scrollTop(),b.left+=e.scrollLeft()),b.position=d.fixed?"fixed":"absolute",this.getOverlay().css(b).fadeIn(d.speed,c)},function(a){this.getOverlay().fadeOut(this.getConf().closeSpeed,a)});function d(d,e){var f=this,g=d.add(f),h=a(window),i,j,k,l=a.tools.expose&&(e.mask||e.expose),m=Math.random().toString().slice(10);l&&(typeof l=="string"&&(l={color:l}),l.closeOnClick=l.closeOnEsc=!1);var n=e.target||d.attr("rel");j=n?a(n):null||d;if(!j.length)throw"Could not find Overlay: "+n;d&&d.index(j)==-1&&d.click(function(a){f.load(a);return a.preventDefault()}),a.extend(f,{load:function(d){if(f.isOpened())return f;var i=c[e.effect];if(!i)throw"Overlay: cannot find effect : \""+e.effect+"\"";e.oneInstance&&a.each(b,function(){this.close(d)}),d=d||a.Event(),d.type="onBeforeLoad",g.trigger(d);if(d.isDefaultPrevented())return f;k=!0,l&&a(j).expose(l);var n=e.top,o=e.left,p=j.outerWidth({margin:!0}),q=j.outerHeight({margin:!0});typeof n=="string"&&(n=n=="center"?Math.max((h.height()-q)/2,0):parseInt(n,10)/100*h.height()),o=="center"&&(o=Math.max((h.width()-p)/2,0)),i[0].call(f,{top:n,left:o},function(){k&&(d.type="onLoad",g.trigger(d))}),l&&e.closeOnClick&&a.mask.getMask().one("click",f.close),e.closeOnClick&&a(document).bind("click."+m,function(b){a(b.target).parents(j).length||f.close(b)}),e.closeOnEsc&&a(document).bind("keydown."+m,function(a){a.keyCode==27&&f.close(a)});return f},close:function(b){if(!f.isOpened())return f;b=b||a.Event(),b.type="onBeforeClose",g.trigger(b);if(!b.isDefaultPrevented()){k=!1,c[e.effect][1].call(f,function(){b.type="onClose",g.trigger(b)}),a(document).unbind("click."+m).unbind("keydown."+m),l&&a.mask.close();return f}},getOverlay:function(){return j},getTrigger:function(){return d},getClosers:function(){return i},isOpened:function(){return k},getConf:function(){return e}}),a.each("onBeforeLoad,onStart,onLoad,onBeforeClose,onClose".split(","),function(b,c){a.isFunction(e[c])&&a(f).bind(c,e[c]),f[c]=function(b){b&&a(f).bind(c,b);return f}}),i=j.find(e.close||".close"),!i.length&&!e.close&&(i=a("<a class=\"close\"></a>"),j.prepend(i)),i.click(function(a){f.close(a)}),e.load&&f.load()}a.fn.overlay=function(c){var e=this.data("overlay");if(e)return e;a.isFunction(c)&&(c={onBeforeLoad:c}),c=a.extend(!0,{},a.tools.overlay.conf,c),this.each(function(){e=new d(a(this),c),b.push(e),a(this).data("overlay",e)});return c.api?e:this}})(jQuery);
(function(a){var b=a.tools.overlay,c=a(window);a.extend(b.conf,{start:{top:null,left:null},fadeInSpeed:"fast",zIndex:9999});function d(a){var b=a.offset();return{top:b.top+a.height()/2,left:b.left+a.width()/2}}var e=function(b,e){var f=this.getOverlay(),g=this.getConf(),h=this.getTrigger(),i=this,j=f.outerWidth({margin:!0}),k=f.data("img"),l=g.fixed?"fixed":"absolute";if(!k){var m=f.css("backgroundImage");if(!m)throw"background-image CSS property not set for overlay";m=m.slice(m.indexOf("(")+1,m.indexOf(")")).replace(/\"/g,""),f.css("backgroundImage","none"),k=a("<img src=\""+m+"\"/>"),k.css({border:0,display:"none"}).width(j),a("body").append(k),f.data("img",k)}var n=g.start.top||Math.round(c.height()/2),o=g.start.left||Math.round(c.width()/2);if(h){var p=d(h);n=p.top,o=p.left}g.fixed?(n-=c.scrollTop(),o-=c.scrollLeft()):(b.top+=c.scrollTop(),b.left+=c.scrollLeft()),k.css({position:"absolute",top:n,left:o,width:0,zIndex:g.zIndex}).show(),b.position=l,f.css(b),k.animate({top:f.css("top"),left:f.css("left"),width:j},g.speed,function(){f.css("zIndex",g.zIndex+1).fadeIn(g.fadeInSpeed,function(){i.isOpened()&&!a(this).index(f)?e.call():f.hide()})}).css("position",l)},f=function(b){var e=this.getOverlay().hide(),f=this.getConf(),g=this.getTrigger(),h=e.data("img"),i={top:f.start.top,left:f.start.left,width:0};g&&a.extend(i,d(g)),f.fixed&&h.css({position:"absolute"}).animate({top:"+="+c.scrollTop(),left:"+="+c.scrollLeft()},0),h.animate(i,f.closeSpeed,b)};b.addEffect("apple",e,f)})(jQuery);
(function(a){a.tools=a.tools||{version:"v1.2.5"},a.tools.scrollable={conf:{activeClass:"active",circular:!1,clonedClass:"cloned",disabledClass:"disabled",easing:"swing",initialIndex:0,item:null,items:".items",keyboard:!0,mousewheel:!1,next:".next",prev:".prev",speed:400,vertical:!1,touch:!0,wheelSpeed:0}};function b(a,b){var c=parseInt(a.css(b),10);if(c)return c;var d=a[0].currentStyle;return d&&d.width&&parseInt(d.width,10)}function c(b,c){var d=a(c);return d.length<2?d:b.parent().find(c)}var d;function e(b,e){var f=this,g=b.add(f),h=b.children(),i=0,j=e.vertical;d||(d=f),h.length>1&&(h=a(e.items,b)),a.extend(f,{getConf:function(){return e},getIndex:function(){return i},getSize:function(){return f.getItems().size()},getNaviButtons:function(){return m.add(n)},getRoot:function(){return b},getItemWrap:function(){return h},getItems:function(){return h.children(e.item).not("."+e.clonedClass)},move:function(a,b){return f.seekTo(i+a,b)},next:function(a){return f.move(1,a)},prev:function(a){return f.move(-1,a)},begin:function(a){return f.seekTo(0,a)},end:function(a){return f.seekTo(f.getSize()-1,a)},focus:function(){d=f;return f},addItem:function(b){b=a(b),e.circular?(h.children("."+e.clonedClass+":last").before(b),h.children("."+e.clonedClass+":first").replaceWith(b.clone().addClass(e.clonedClass))):h.append(b),g.trigger("onAddItem",[b]);return f},seekTo:function(b,c,k){b.jquery||(b*=1);if(e.circular&&b===0&&i==-1&&c!==0)return f;if(!e.circular&&b<0||b>f.getSize()||b<-1)return f;var l=b;b.jquery?b=f.getItems().index(b):l=f.getItems().eq(b);var m=a.Event("onBeforeSeek");if(!k){g.trigger(m,[b,c]);if(m.isDefaultPrevented()||!l.length)return f}var n=j?{top:-l.position().top}:{left:-l.position().left};i=b,d=f,c===undefined&&(c=e.speed),h.animate(n,c,e.easing,k||function(){g.trigger("onSeek",[b])});return f}}),a.each(["onBeforeSeek","onSeek","onAddItem"],function(b,c){a.isFunction(e[c])&&a(f).bind(c,e[c]),f[c]=function(b){b&&a(f).bind(c,b);return f}});if(e.circular){var k=f.getItems().slice(-1).clone().prependTo(h),l=f.getItems().eq(1).clone().appendTo(h);k.add(l).addClass(e.clonedClass),f.onBeforeSeek(function(a,b,c){if(!a.isDefaultPrevented()){if(b==-1){f.seekTo(k,c,function(){f.end(0)});return a.preventDefault()}b==f.getSize()&&f.seekTo(l,c,function(){f.begin(0)})}}),f.seekTo(0,0,function(){})}var m=c(b,e.prev).click(function(){f.prev()}),n=c(b,e.next).click(function(){f.next()});!e.circular&&f.getSize()>1&&(f.onBeforeSeek(function(a,b){setTimeout(function(){a.isDefaultPrevented()||(m.toggleClass(e.disabledClass,b<=0),n.toggleClass(e.disabledClass,b>=f.getSize()-1))},1)}),e.initialIndex||m.addClass(e.disabledClass)),e.mousewheel&&a.fn.mousewheel&&b.mousewheel(function(a,b){if(e.mousewheel){f.move(b<0?1:-1,e.wheelSpeed||50);return!1}});if(e.touch){var o={};h[0].ontouchstart=function(a){var b=a.touches[0];o.x=b.clientX,o.y=b.clientY},h[0].ontouchmove=function(a){if(a.touches.length==1&&!h.is(":animated")){var b=a.touches[0],c=o.x-b.clientX,d=o.y-b.clientY;f[j&&d>0||!j&&c>0?"next":"prev"](),a.preventDefault()}}}e.keyboard&&a(document).bind("keydown.scrollable",function(b){if(e.keyboard&&!b.altKey&&!b.ctrlKey&&!a(b.target).is(":input")){if(e.keyboard!="static"&&d!=f)return;var c=b.keyCode;if(j&&(c==38||c==40)){f.move(c==38?-1:1);return b.preventDefault()}if(!j&&(c==37||c==39)){f.move(c==37?-1:1);return b.preventDefault()}}}),e.initialIndex&&f.seekTo(e.initialIndex,0,function(){})}a.fn.scrollable=function(b){var c=this.data("scrollable");if(c)return c;b=a.extend({},a.tools.scrollable.conf,b),this.each(function(){c=new e(a(this),b),a(this).data("scrollable",c)});return b.api?c:this}})(jQuery);
(function(a){var b=a.tools.scrollable;b.autoscroll={conf:{autoplay:!0,interval:3e3,autopause:!0}},a.fn.autoscroll=function(c){typeof c=="number"&&(c={interval:c});var d=a.extend({},b.autoscroll.conf,c),e;this.each(function(){var b=a(this).data("scrollable");b&&(e=b);var c,f=!0;b.play=function(){c||(f=!1,c=setInterval(function(){b.next()},d.interval))},b.pause=function(){c=clearInterval(c)},b.stop=function(){b.pause(),f=!0},d.autopause&&b.getRoot().add(b.getNaviButtons()).hover(b.pause,b.play),d.autoplay&&b.play()});return d.api?e:this}})(jQuery);
(function(a){var b=a.tools.scrollable;b.navigator={conf:{navi:".navi",naviItem:null,activeClass:"active",indexed:!1,idPrefix:null,history:!1}};function c(b,c){var d=a(c);return d.length<2?d:b.parent().find(c)}a.fn.navigator=function(d){typeof d=="string"&&(d={navi:d}),d=a.extend({},b.navigator.conf,d);var e;this.each(function(){var b=a(this).data("scrollable"),f=d.navi.jquery?d.navi:c(b.getRoot(),d.navi),g=b.getNaviButtons(),h=d.activeClass,i=d.history&&a.fn.history;b&&(e=b),b.getNaviButtons=function(){return g.add(f)};function j(a,c,d){b.seekTo(c);if(i)location.hash&&(location.hash=a.attr("href").replace("#",""));else return d.preventDefault()}function k(){return f.find(d.naviItem||"> *")}function l(b){var c=a("<"+(d.naviItem||"a")+"/>").click(function(c){j(a(this),b,c)}).attr("href","#"+b);b===0&&c.addClass(h),d.indexed&&c.text(b+1),d.idPrefix&&c.attr("id",d.idPrefix+b);return c.appendTo(f)}k().length?k().each(function(b){a(this).click(function(c){j(a(this),b,c)})}):a.each(b.getItems(),function(a){l(a)}),b.onBeforeSeek(function(a,b){setTimeout(function(){if(!a.isDefaultPrevented()){var c=k().eq(b);!a.isDefaultPrevented()&&c.length&&k().removeClass(h).eq(b).addClass(h)}},1)});function m(a,b){var c=k().eq(b.replace("#",""));c.length||(c=k().filter("[href="+b+"]")),c.click()}b.onAddItem(function(a,c){c=l(b.getItems().index(c)),i&&c.history(m)}),i&&k().history(m)});return d.api?e:this}})(jQuery);
(function(a){a.tools=a.tools||{version:"v1.2.5"},a.tools.tabs={conf:{tabs:"a",current:"current",onBeforeClick:null,onClick:null,effect:"default",initialIndex:0,event:"click",rotate:!1,history:!1},addEffect:function(a,c){b[a]=c}};var b={"default":function(a,b){this.getPanes().hide().eq(a).show(),b.call()},fade:function(a,b){var c=this.getConf(),d=c.fadeOutSpeed,e=this.getPanes();d?e.fadeOut(d):e.hide(),e.eq(a).fadeIn(c.fadeInSpeed,b)},slide:function(a,b){this.getPanes().slideUp(200),this.getPanes().eq(a).slideDown(400,b)},ajax:function(a,b){this.getPanes().eq(0).load(this.getTabs().eq(a).attr("href"),b)}},c;a.tools.tabs.addEffect("horizontal",function(b,d){c||(c=this.getPanes().eq(0).width()),this.getCurrentPane().animate({width:0},function(){a(this).hide()}),this.getPanes().eq(b).animate({width:c},function(){a(this).show(),d.call()})});function d(c,d,e){var f=this,g=c.add(this),h=c.find(e.tabs),i=d.jquery?d:c.children(d),j;h.length||(h=c.children()),i.length||(i=c.parent().find(d)),i.length||(i=a(d)),a.extend(this,{click:function(c,d){var i=h.eq(c);typeof c=="string"&&c.replace("#","")&&(i=h.filter("[href*="+c.replace("#","")+"]"),c=Math.max(h.index(i),0));if(e.rotate){var k=h.length-1;if(c<0)return f.click(k,d);if(c>k)return f.click(0,d)}if(!i.length){if(j>=0)return f;c=e.initialIndex,i=h.eq(c)}if(c===j)return f;d=d||a.Event(),d.type="onBeforeClick",g.trigger(d,[c]);if(!d.isDefaultPrevented()){b[e.effect].call(f,c,function(){d.type="onClick",g.trigger(d,[c])}),j=c,h.removeClass(e.current),i.addClass(e.current);return f}},getConf:function(){return e},getTabs:function(){return h},getPanes:function(){return i},getCurrentPane:function(){return i.eq(j)},getCurrentTab:function(){return h.eq(j)},getIndex:function(){return j},next:function(){return f.click(j+1)},prev:function(){return f.click(j-1)},destroy:function(){h.unbind(e.event).removeClass(e.current),i.find("a[href^=#]").unbind("click.T");return f}}),a.each("onBeforeClick,onClick".split(","),function(b,c){a.isFunction(e[c])&&a(f).bind(c,e[c]),f[c]=function(b){b&&a(f).bind(c,b);return f}}),e.history&&a.fn.history&&(a.tools.history.init(h),e.event="history"),h.each(function(b){a(this).bind(e.event,function(a){f.click(b,a);return a.preventDefault()})}),i.find("a[href^=#]").bind("click.T",function(b){f.click(a(this).attr("href"),b)}),location.hash&&e.tabs=="a"&&c.find("[href="+location.hash+"]").length?f.click(location.hash):(e.initialIndex===0||e.initialIndex>0)&&f.click(e.initialIndex)}a.fn.tabs=function(b,c){var e=this.data("tabs");e&&(e.destroy(),this.removeData("tabs")),a.isFunction(c)&&(c={onBeforeClick:c}),c=a.extend({},a.tools.tabs.conf,c),this.each(function(){e=new d(a(this),b,c),a(this).data("tabs",e)});return c.api?e:this}})(jQuery);
(function(a){var b;b=a.tools.tabs.slideshow={conf:{next:".forward",prev:".backward",disabledClass:"disabled",autoplay:!1,autopause:!0,interval:3e3,clickable:!0,api:!1}};function c(b,c){var d=this,e=b.add(this),f=b.data("tabs"),g,h=!0;function i(c){var d=a(c);return d.length<2?d:b.parent().find(c)}var j=i(c.next).click(function(){f.next()}),k=i(c.prev).click(function(){f.prev()});a.extend(d,{getTabs:function(){return f},getConf:function(){return c},play:function(){if(g)return d;var b=a.Event("onBeforePlay");e.trigger(b);if(b.isDefaultPrevented())return d;g=setInterval(f.next,c.interval),h=!1,e.trigger("onPlay");return d},pause:function(){if(!g)return d;var b=a.Event("onBeforePause");e.trigger(b);if(b.isDefaultPrevented())return d;g=clearInterval(g),e.trigger("onPause");return d},stop:function(){d.pause(),h=!0}}),a.each("onBeforePlay,onPlay,onBeforePause,onPause".split(","),function(b,e){a.isFunction(c[e])&&a(d).bind(e,c[e]),d[e]=function(b){return a(d).bind(e,b)}}),c.autopause&&f.getTabs().add(j).add(k).add(f.getPanes()).hover(d.pause,function(){h||d.play()}),c.autoplay&&d.play(),c.clickable&&f.getPanes().click(function(){f.next()});if(!f.getConf().rotate){var l=c.disabledClass;f.getIndex()||k.addClass(l),f.onBeforeClick(function(a,b){k.toggleClass(l,!b),j.toggleClass(l,b==f.getTabs().length-1)})}}a.fn.slideshow=function(d){var e=this.data("slideshow");if(e)return e;d=a.extend({},b.conf,d),this.each(function(){e=new c(a(this),d),a(this).data("slideshow",e)});return d.api?e:this}})(jQuery);
(function(a){a.tools=a.tools||{version:"v1.2.5"},a.tools.tooltip={conf:{effect:"toggle",fadeOutSpeed:"fast",predelay:0,delay:30,opacity:1,tip:0,position:["top","center"],offset:[0,0],relative:!1,cancelDefault:!0,events:{def:"mouseenter,mouseleave",input:"focus,blur",widget:"focus mouseenter,blur mouseleave",tooltip:"mouseenter,mouseleave"},layout:"<div/>",tipClass:"tooltip"},addEffect:function(a,c,d){b[a]=[c,d]}};var b={toggle:[function(a){var b=this.getConf(),c=this.getTip(),d=b.opacity;d<1&&c.css({opacity:d}),c.show(),a.call()},function(a){this.getTip().hide(),a.call()}],fade:[function(a){var b=this.getConf();this.getTip().fadeTo(b.fadeInSpeed,b.opacity,a)},function(a){this.getTip().fadeOut(this.getConf().fadeOutSpeed,a)}]};function c(b,c,d){var e=d.relative?b.position().top:b.offset().top,f=d.relative?b.position().left:b.offset().left,g=d.position[0];e-=c.outerHeight()-d.offset[0],f+=b.outerWidth()+d.offset[1],/iPad/i.test(navigator.userAgent)&&(e-=a(window).scrollTop());var h=c.outerHeight()+b.outerHeight();g=="center"&&(e+=h/2),g=="bottom"&&(e+=h),g=d.position[1];var i=c.outerWidth()+b.outerWidth();g=="center"&&(f-=i/2),g=="left"&&(f-=i);return{top:e,left:f}}function d(d,e){var f=this,g=d.add(f),h,i=0,j=0,k=d.attr("title"),l=d.attr("data-tooltip"),m=b[e.effect],n,o=d.is(":input"),p=o&&d.is(":checkbox, :radio, select, :button, :submit"),q=d.attr("type"),r=e.events[q]||e.events[o?p?"widget":"input":"def"];if(!m)throw"Nonexistent effect \""+e.effect+"\"";r=r.split(/,\s*/);if(r.length!=2)throw"Tooltip: bad events configuration for "+q;d.bind(r[0],function(a){clearTimeout(i),e.predelay?j=setTimeout(function(){f.show(a)},e.predelay):f.show(a)}).bind(r[1],function(a){clearTimeout(j),e.delay?i=setTimeout(function(){f.hide(a)},e.delay):f.hide(a)}),k&&e.cancelDefault&&(d.removeAttr("title"),d.data("title",k)),a.extend(f,{show:function(b){if(!h){l?h=a(l):e.tip?h=a(e.tip).eq(0):k?h=a(e.layout).addClass(e.tipClass).appendTo(document.body).hide().append(k):(h=d.next(),h.length||(h=d.parent().next()));if(!h.length)throw"Cannot find tooltip for "+d}if(f.isShown())return f;h.stop(!0,!0);var o=c(d,h,e);e.tip&&h.html(d.data("title")),b=b||a.Event(),b.type="onBeforeShow",g.trigger(b,[o]);if(b.isDefaultPrevented())return f;o=c(d,h,e),h.css({position:"absolute",top:o.top,left:o.left}),n=!0,m[0].call(f,function(){b.type="onShow",n="full",g.trigger(b)});var p=e.events.tooltip.split(/,\s*/);h.data("__set")||(h.bind(p[0],function(){clearTimeout(i),clearTimeout(j)}),p[1]&&!d.is("input:not(:checkbox, :radio), textarea")&&h.bind(p[1],function(a){a.relatedTarget!=d[0]&&d.trigger(r[1].split(" ")[0])}),h.data("__set",!0));return f},hide:function(c){if(!h||!f.isShown())return f;c=c||a.Event(),c.type="onBeforeHide",g.trigger(c);if(!c.isDefaultPrevented()){n=!1,b[e.effect][1].call(f,function(){c.type="onHide",g.trigger(c)});return f}},isShown:function(a){return a?n=="full":n},getConf:function(){return e},getTip:function(){return h},getTrigger:function(){return d}}),a.each("onHide,onBeforeShow,onShow,onBeforeHide".split(","),function(b,c){a.isFunction(e[c])&&a(f).bind(c,e[c]),f[c]=function(b){b&&a(f).bind(c,b);return f}})}a.fn.tooltip=function(b){var c=this.data("tooltip");if(c)return c;b=a.extend(!0,{},a.tools.tooltip.conf,b),typeof b.position=="string"&&(b.position=b.position.split(/,?\s/)),this.each(function(){c=new d(a(this),b),a(this).data("tooltip",c)});return b.api?c:this}})(jQuery);
(function(a){var b=a.tools.tooltip;b.dynamic={conf:{classNames:"top right bottom left"}};function c(b){var c=a(window),d=c.width()+c.scrollLeft(),e=c.height()+c.scrollTop();return[b.offset().top<=c.scrollTop(),d<=b.offset().left+b.width(),e<=b.offset().top+b.height(),c.scrollLeft()>=b.offset().left]}function d(a){var b=a.length;while(b--)if(a[b])return!1;return!0}a.fn.dynamic=function(e){typeof e=="number"&&(e={speed:e}),e=a.extend({},b.dynamic.conf,e);var f=e.classNames.split(/\s/),g;this.each(function(){var b=a(this).tooltip().onBeforeShow(function(b,h){var i=this.getTip(),j=this.getConf();g||(g=[j.position[0],j.position[1],j.offset[0],j.offset[1],a.extend({},j)]),a.extend(j,g[4]),j.position=[g[0],g[1]],j.offset=[g[2],g[3]],i.css({visibility:"hidden",position:"absolute",top:h.top,left:h.left}).show();var k=c(i);if(!d(k)){k[2]&&(a.extend(j,e.top),j.position[0]="top",i.addClass(f[0])),k[3]&&(a.extend(j,e.right),j.position[1]="right",i.addClass(f[1])),k[0]&&(a.extend(j,e.bottom),j.position[0]="bottom",i.addClass(f[2])),k[1]&&(a.extend(j,e.left),j.position[1]="left",i.addClass(f[3]));if(k[0]||k[2])j.offset[0]*=-1;if(k[1]||k[3])j.offset[1]*=-1}i.css({visibility:"visible"}).hide()});b.onBeforeShow(function(){var a=this.getConf(),b=this.getTip();setTimeout(function(){a.position=[g[0],g[1]],a.offset=[g[2],g[3]]},0)}),b.onHide(function(){var a=this.getTip();a.removeClass(e.classNames)}),ret=b});return e.api?ret:this}})(jQuery);

jQuery.easing.jswing=jQuery.easing.swing;jQuery.extend(jQuery.easing,{def:"easeOutQuad",swing:function(e,f,a,h,g){return jQuery.easing[jQuery.easing.def](e,f,a,h,g);},easeInQuad:function(e,f,a,h,g){return h*(f/=g)*f+a;},easeOutQuad:function(e,f,a,h,g){return -h*(f/=g)*(f-2)+a;},easeInOutQuad:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f+a;}return -h/2*((--f)*(f-2)-1)+a;},easeInCubic:function(e,f,a,h,g){return h*(f/=g)*f*f+a;},easeOutCubic:function(e,f,a,h,g){return h*((f=f/g-1)*f*f+1)+a;},easeInOutCubic:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f+a;}return h/2*((f-=2)*f*f+2)+a;},easeInQuart:function(e,f,a,h,g){return h*(f/=g)*f*f*f+a;},easeOutQuart:function(e,f,a,h,g){return -h*((f=f/g-1)*f*f*f-1)+a;},easeInOutQuart:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f*f+a;}return -h/2*((f-=2)*f*f*f-2)+a;},easeInQuint:function(e,f,a,h,g){return h*(f/=g)*f*f*f*f+a;},easeOutQuint:function(e,f,a,h,g){return h*((f=f/g-1)*f*f*f*f+1)+a;},easeInOutQuint:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f*f*f+a;}return h/2*((f-=2)*f*f*f*f+2)+a;},easeInSine:function(e,f,a,h,g){return -h*Math.cos(f/g*(Math.PI/2))+h+a;},easeOutSine:function(e,f,a,h,g){return h*Math.sin(f/g*(Math.PI/2))+a;},easeInOutSine:function(e,f,a,h,g){return -h/2*(Math.cos(Math.PI*f/g)-1)+a;},easeInExpo:function(e,f,a,h,g){return(f==0)?a:h*Math.pow(2,10*(f/g-1))+a;},easeOutExpo:function(e,f,a,h,g){return(f==g)?a+h:h*(-Math.pow(2,-10*f/g)+1)+a;},easeInOutExpo:function(e,f,a,h,g){if(f==0){return a;}if(f==g){return a+h;}if((f/=g/2)<1){return h/2*Math.pow(2,10*(f-1))+a;}return h/2*(-Math.pow(2,-10*--f)+2)+a;},easeInCirc:function(e,f,a,h,g){return -h*(Math.sqrt(1-(f/=g)*f)-1)+a;},easeOutCirc:function(e,f,a,h,g){return h*Math.sqrt(1-(f=f/g-1)*f)+a;},easeInOutCirc:function(e,f,a,h,g){if((f/=g/2)<1){return -h/2*(Math.sqrt(1-f*f)-1)+a;}return h/2*(Math.sqrt(1-(f-=2)*f)+1)+a;},easeInElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e;}if((h/=k)==1){return e+l;}if(!j){j=k*0.3;}if(g<Math.abs(l)){g=l;var i=j/4;}else{var i=j/(2*Math.PI)*Math.asin(l/g);}return -(g*Math.pow(2,10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j))+e;},easeOutElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e;}if((h/=k)==1){return e+l;}if(!j){j=k*0.3;}if(g<Math.abs(l)){g=l;var i=j/4;}else{var i=j/(2*Math.PI)*Math.asin(l/g);}return g*Math.pow(2,-10*h)*Math.sin((h*k-i)*(2*Math.PI)/j)+l+e;},easeInOutElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e;}if((h/=k/2)==2){return e+l;}if(!j){j=k*(0.3*1.5);}if(g<Math.abs(l)){g=l;var i=j/4;}else{var i=j/(2*Math.PI)*Math.asin(l/g);}if(h<1){return -0.5*(g*Math.pow(2,10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j))+e;}return g*Math.pow(2,-10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j)*0.5+l+e;},easeInBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158;}return i*(f/=h)*f*((g+1)*f-g)+a;},easeOutBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158;}return i*((f=f/h-1)*f*((g+1)*f+g)+1)+a;},easeInOutBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158;}if((f/=h/2)<1){return i/2*(f*f*(((g*=(1.525))+1)*f-g))+a;}return i/2*((f-=2)*f*(((g*=(1.525))+1)*f+g)+2)+a;},easeInBounce:function(e,f,a,h,g){return h-jQuery.easing.easeOutBounce(e,g-f,0,h,g)+a;},easeOutBounce:function(e,f,a,h,g){if((f/=g)<(1/2.75)){return h*(7.5625*f*f)+a;}else{if(f<(2/2.75)){return h*(7.5625*(f-=(1.5/2.75))*f+0.75)+a;}else{if(f<(2.5/2.75)){return h*(7.5625*(f-=(2.25/2.75))*f+0.9375)+a;}else{return h*(7.5625*(f-=(2.625/2.75))*f+0.984375)+a;}}}},easeInOutBounce:function(e,f,a,h,g){if(f<g/2){return jQuery.easing.easeInBounce(e,f*2,0,h,g)*0.5+a;}return jQuery.easing.easeOutBounce(e,f*2-g,0,h,g)*0.5+h*0.5+a;}});(function(b){function c(f){var e=["transform","WebkitTransform","MozTransform"];var g;while(g=e.shift()){if(typeof f.style[g]!="undefined"){return g;}}return"transform";}var a=b.fn.css;b.fn.css=function(e){if(typeof b.props.transform=="undefined"&&(e=="transform"||(typeof e=="object"&&typeof e.transform!="undefined"))){b.props.transform=c(this.get(0));}if(e=="transform"){e=b.props.transform;}return a.apply(this,arguments);};})(jQuery);(function(e){var c="deg";e.fn.rotate=function(h){var g=e(this).css("transform")||"none";if(typeof h=="undefined"){if(g){var f=g.match(/rotate\(([^)]+)\)/);if(f&&f[1]){return f[1];}}return 0;}var f=h.toString().match(/^(-?\d+(\.\d+)?)(.+)?$/);if(f){if(f[3]){c=f[3];}e(this).css("transform",g.replace(/none|rotate\([^)]*\)/,"")+"rotate("+f[1]+c+")");}};e.fn.scale=function(j,i,g){var h=e(this).css("transform");if(typeof j=="undefined"){if(h){var f=h.match(/scale\(([^)]+)\)/);if(f&&f[1]){return f[1];}}return 1;}e(this).css("transform",h.replace(/none|scale\([^)]*\)/,"")+"scale("+j+")");};var b=e.fx.prototype.cur;e.fx.prototype.cur=function(){if(this.prop=="rotate"){return parseFloat(e(this.elem).rotate());}else{if(this.prop=="scale"){return parseFloat(e(this.elem).scale());}}return b.apply(this,arguments);};e.fx.step.rotate=function(f){e(f.elem).rotate(f.now+c);};e.fx.step.scale=function(f){e(f.elem).scale(f.now);};var a=e.fn.animate;e.fn.animate=function(g){if(typeof g.rotate!="undefined"){var f=g.rotate.toString().match(/^(([+-]=)?(-?\d+(\.\d+)?))(.+)?$/);if(f&&f[5]){c=f[5];}g.rotate=f[1];}return a.apply(this,arguments);};})(jQuery);(function(a){a.fn.quicksand=function(f,b){var e={duration:750,easing:"swing",attribute:"data-id",adjustHeight:"auto",useScaling:true,enhancement:function(g){},selector:"> *",dx:0,dy:0};a.extend(e,b);if(a.browser.msie||(typeof(a.fn.scale)=="undefined")){e.useScaling=false;}var c;if(typeof(arguments[1])=="function"){var c=arguments[1];}else{if(typeof(arguments[2]=="function")){var c=arguments[2];}}return this.each(function(u){var z;var x=[];var j=a(f).clone();var y=a(this);var h=a(this).css("height");var o;var k=false;var l=a(y).offset();var m=[];var w=a(this).find(e.selector);if(a.browser.msie&&a.browser.version.substr(0,1)<7){y.html("").append(j);return;}var n=0;var r=function(){if(!n){n=1;$toDelete=y.find("> *");y.prepend(s.find("> *"));$toDelete.remove();if(k){y.css("height",o);}e.enhancement(y);if(typeof c=="function"){c.call(this);}}};var v=y.offsetParent();var t=v.offset();if(v.css("position")=="relative"){if(v.get(0).nodeName.toLowerCase()=="body"){}else{t.top+=(parseFloat(v.css("border-top-width"))||0);t.left+=(parseFloat(v.css("border-left-width"))||0);}}else{t.top-=(parseFloat(v.css("border-top-width"))||0);t.left-=(parseFloat(v.css("border-left-width"))||0);t.top-=(parseFloat(v.css("margin-top"))||0);t.left-=(parseFloat(v.css("margin-left"))||0);}if(isNaN(t.left)){t.left=0;}if(isNaN(t.top)){t.top=0;}t.left-=e.dx;t.top-=e.dy;y.css("height",a(this).height());w.each(function(A){m[A]=a(this).offset();});a(this).stop();var q=0;var p=0;w.each(function(A){a(this).stop();var B=a(this).get(0);if(B.style.position=="absolute"){q=-e.dx;p=-e.dy;}else{q=e.dx;p=e.dy;}B.style.position="absolute";B.style.margin="0";B.style.top=(m[A].top-parseFloat(B.style.marginTop)-t.top+p)+"px";B.style.left=(m[A].left-parseFloat(B.style.marginLeft)-t.left+q)+"px";});var s=a(y).clone();var g=s.get(0);g.innerHTML="";g.setAttribute("id","");g.style.height="auto";g.style.width=y.width()+"px";s.append(j);s.insertBefore(y);s.css("opacity",0);g.style.zIndex=-1;g.style.margin="0";g.style.position="absolute";g.style.top=l.top-t.top+"px";g.style.left=l.left-t.left+"px";if(e.adjustHeight==="dynamic"){y.animate({height:s.height()},e.duration,e.easing);}else{if(e.adjustHeight==="auto"){o=s.height();if(parseFloat(h)<parseFloat(o)){y.css("height",o);}else{k=true;}}}w.each(function(A){var B=[];if(typeof(e.attribute)=="function"){z=e.attribute(a(this));j.each(function(){if(e.attribute(this)==z){B=a(this);return false;}});}else{B=j.filter("["+e.attribute+"="+a(this).attr(e.attribute)+"]");}if(B.length){if(!e.useScaling){x.push({element:a(this),animation:{top:B.offset().top-t.top,left:B.offset().left-t.left,opacity:1}});}else{x.push({element:a(this),animation:{top:B.offset().top-t.top,left:B.offset().left-t.left,opacity:1,scale:"1.0"}});}}else{if(!e.useScaling){x.push({element:a(this),animation:{opacity:"0.0"}});}else{x.push({element:a(this),animation:{opacity:"0.0",scale:"0.0"}});}}});j.each(function(C){var B=[];var E=[];if(typeof(e.attribute)=="function"){z=e.attribute(a(this));w.each(function(){if(e.attribute(this)==z){B=a(this);return false;}});j.each(function(){if(e.attribute(this)==z){E=a(this);return false;}});}else{B=w.filter("["+e.attribute+"="+a(this).attr(e.attribute)+"]");E=j.filter("["+e.attribute+"="+a(this).attr(e.attribute)+"]");}var D;if(B.length===0){if(!e.useScaling){D={opacity:"1.0"};}else{D={opacity:"1.0",scale:"1.0"};}d=E.clone();var A=d.get(0);A.style.position="absolute";A.style.margin="0";A.style.top=E.offset().top-t.top+"px";A.style.left=E.offset().left-t.left+"px";d.css("opacity",0);if(e.useScaling){d.css("transform","scale(0.0)");}d.appendTo(y);x.push({element:a(d),animation:D});}});s.remove();e.enhancement(y);for(u=0;u<x.length;u++){x[u].element.animate(x[u].animation,e.duration,e.easing,r);}});};})(jQuery);
