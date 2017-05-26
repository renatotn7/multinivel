;(function(){var e=jQuery,f="jQuery.pause",d=1,b=e.fn.animate,a={};function c(){return new Date().getTime()}e.fn.animate=function(k,h,j,i){var g=e.speed(h,j,i);g.complete=g.old;return this.each(function(){if(!this[f]){this[f]=d++}var l=e.extend({},g);b.apply(e(this),[k,e.extend({},l)]);a[this[f]]={run:true,prop:k,opt:l,start:c(),done:0}})};e.fn.pause=function(){return this.each(function(){if(!this[f]){this[f]=d++}var g=a[this[f]];if(g&&g.run){g.done+=c()-g.start;if(g.done>g.opt.duration){delete a[this[f]]}else{e(this).stop();g.run=false}}})};e.fn.resume=function(){return this.each(function(){if(!this[f]){this[f]=d++}var g=a[this[f]];if(g&&!g.run){g.opt.duration-=g.done;g.done=0;g.run=true;g.start=c();b.apply(e(this),[g.prop,e.extend({},g.opt)])}})}})();

;(function($, window, undefined) {
	$.fn.roller = function (settings) {

		this.each(function () {
			var self = $(this);
			var content = self.children().detach();
			var wrapperBox = $('<div class="roller-wrapper-box"></div>');
			var animateBox = $('<div class="roller-animate-box"></div>');
			var contentBox = $('<div class="roller-content-box"></div>');
			contentBox.append(content);
			wrapperBox.append(animateBox);
			animateBox.append(contentBox.clone(true, true));
			self.append(wrapperBox);
			startAnimate(animateBox, $('body').width(), contentBox, settings.speed);
			animateBox.bind('mouseenter', mouseEnter);
			animateBox.bind('mouseleave', mouseLeave);
		});

		function startAnimate(animateBox, totalWidth, contentBox, speed) {
			var width = animateBox.width();
			var time = (width/speed) * 1000;
			var left = '-' + width + 'px';
			var intervalTime = (((width - totalWidth)/speed) * 1000) - 100; // 100ms como margem de seguranÃ§a
			if (intervalTime < 1) return;
			var cloneTimer;

			var animation = function () {
				cloneTimer = setTimeout(function () {
					animateBox.data('primeiro', animateBox.children());
					animateBox.append(contentBox.clone(true, true));
				}, intervalTime);
				animateBox.stop().animate({ 'marginLeft': left }, time, 'linear', function () {
					var primeiro = animateBox.data('primeiro');
					if (primeiro) primeiro.remove();
					animateBox.css('marginLeft', '0');
					animation(); // chamada recursiva
				});
			};
			animation();
		}

		function mouseEnter() {
			$(this).pause();
		}

		function mouseLeave() {
			$(this).resume();
		}

	};
}(jQuery, window));