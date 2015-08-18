(function($, undefined) {
    "use strict";
    var ver = '2.9999.6';
    if ($.support === undefined) {
        $.support = {
            opacity: !($.browser.msie)
        };
    }

    function debug(s) {
        if ($.fn.cycle.debug)
            log(s);
    }

    function log() {
        if (window.console && console.log)
            console.log('[cycle] ' + Array.prototype.join.call(arguments, ' '));
    }
    $.expr[':'].paused = function(el) {
        return el.cyclePause;
    };
    $.fn.cycle = function(options, arg2) {
        var o = {
            s: this.selector,
            c: this.context
        };
        if (this.length === 0 && options != 'stop') {
            if (!$.isReady && o.s) {
                log('DOM not ready, queuing slideshow');
                $(function() {
                    $(o.s, o.c).cycle(options, arg2);
                });
                return this;
            }
            log('terminating; zero elements found by selector' + ($.isReady ? '' : ' (DOM not ready)'));
            return this;
        }
        return this.each(function() {
            var opts = handleArguments(this, options, arg2);
            if (opts === false)
                return;
            opts.updateActivePagerLink = opts.updateActivePagerLink || $.fn.cycle.updateActivePagerLink;
            if (this.cycleTimeout)
                clearTimeout(this.cycleTimeout);
            this.cycleTimeout = this.cyclePause = 0;
            this.cycleStop = 0;
            var $cont = $(this);
            var $slides = opts.slideExpr ? $(opts.slideExpr, this) : $cont.children();
            var els = $slides.get();
            if (els.length < 2) {
                log('terminating; too few slides: ' + els.length);
                return;
            }
            var opts2 = buildOptions($cont, $slides, els, opts, o);
            if (opts2 === false)
                return;
            var startTime = opts2.continuous ? 10 : getTimeout(els[opts2.currSlide], els[opts2.nextSlide], opts2, !opts2.backwards);
            if (startTime) {
                startTime += (opts2.delay || 0);
                if (startTime < 10)
                    startTime = 10;
                debug('first timeout: ' + startTime);
                this.cycleTimeout = setTimeout(function() {
                    go(els, opts2, 0, !opts.backwards);
                }, startTime);
            }
        });
    };

    function triggerPause(cont, byHover, onPager) {
        var opts = $(cont).data('cycle.opts');
        var paused = !!cont.cyclePause;
        if (paused && opts.paused)
            opts.paused(cont, opts, byHover, onPager);
        else if (!paused && opts.resumed)
            opts.resumed(cont, opts, byHover, onPager);
    }

    function handleArguments(cont, options, arg2) {
        if (cont.cycleStop === undefined)
            cont.cycleStop = 0;
        if (options === undefined || options === null)
            options = {};
        if (options.constructor == String) {
            switch (options) {
                case 'destroy':
                case 'stop':
                    var opts = $(cont).data('cycle.opts');
                    if (!opts)
                        return false;
                    cont.cycleStop++;
                    if (cont.cycleTimeout)
                        clearTimeout(cont.cycleTimeout);
                    cont.cycleTimeout = 0;
                    if (opts.elements)
                        $(opts.elements).stop();
                    $(cont).removeData('cycle.opts');
                    if (options == 'destroy')
                        destroy(cont, opts);
                    return false;
                case 'toggle':
                    cont.cyclePause = (cont.cyclePause === 1) ? 0 : 1;
                    checkInstantResume(cont.cyclePause, arg2, cont);
                    triggerPause(cont);
                    return false;
                case 'pause':
                    cont.cyclePause = 1;
                    triggerPause(cont);
                    return false;
                case 'resume':
                    cont.cyclePause = 0;
                    checkInstantResume(false, arg2, cont);
                    triggerPause(cont);
                    return false;
                case 'prev':
                case 'next':
                    opts = $(cont).data('cycle.opts');
                    if (!opts) {
                        log('options not found, "prev/next" ignored');
                        return false;
                    }
                    $.fn.cycle[options](opts);
                    return false;
                default:
                    options = {
                        fx: options
                    };
            }
            return options;
        } else if (options.constructor == Number) {
            var num = options;
            options = $(cont).data('cycle.opts');
            if (!options) {
                log('options not found, can not advance slide');
                return false;
            }
            if (num < 0 || num >= options.elements.length) {
                log('invalid slide index: ' + num);
                return false;
            }
            options.nextSlide = num;
            if (cont.cycleTimeout) {
                clearTimeout(cont.cycleTimeout);
                cont.cycleTimeout = 0;
            }
            if (typeof arg2 == 'string')
                options.oneTimeFx = arg2;
            go(options.elements, options, 1, num >= options.currSlide);
            return false;
        }
        return options;

        function checkInstantResume(isPaused, arg2, cont) {
            if (!isPaused && arg2 === true) {
                var options = $(cont).data('cycle.opts');
                if (!options) {
                    log('options not found, can not resume');
                    return false;
                }
                if (cont.cycleTimeout) {
                    clearTimeout(cont.cycleTimeout);
                    cont.cycleTimeout = 0;
                }
                go(options.elements, options, 1, !options.backwards);
            }
        }
    }

    function removeFilter(el, opts) {
        if (!$.support.opacity && opts.cleartype && el.style.filter) {
            try {
                el.style.removeAttribute('filter');
            } catch (smother) {}
        }
    }

    function destroy(cont, opts) {
        if (opts.next)
            $(opts.next).unbind(opts.prevNextEvent);
        if (opts.prev)
            $(opts.prev).unbind(opts.prevNextEvent);
        if (opts.pager || opts.pagerAnchorBuilder)
            $.each(opts.pagerAnchors || [], function() {
                this.unbind().remove();
            });
        opts.pagerAnchors = null;
        $(cont).unbind('mouseenter.cycle mouseleave.cycle');
        if (opts.destroy)
            opts.destroy(opts);
    }

    function buildOptions($cont, $slides, els, options, o) {
        var startingSlideSpecified;
        var opts = $.extend({}, $.fn.cycle.defaults, options || {}, $.metadata ? $cont.metadata() : $.meta ? $cont.data() : {});
        var meta = $.isFunction($cont.data) ? $cont.data(opts.metaAttr) : null;
        if (meta)
            opts = $.extend(opts, meta);
        if (opts.autostop)
            opts.countdown = opts.autostopCount || els.length;
        var cont = $cont[0];
        $cont.data('cycle.opts', opts);
        opts.$cont = $cont;
        opts.stopCount = cont.cycleStop;
        opts.elements = els;
        opts.before = opts.before ? [opts.before] : [];
        opts.after = opts.after ? [opts.after] : [];
        if (!$.support.opacity && opts.cleartype)
            opts.after.push(function() {
                removeFilter(this, opts);
            });
        if (opts.continuous)
            opts.after.push(function() {
                go(els, opts, 0, !opts.backwards);
            });
        saveOriginalOpts(opts);
        if (!$.support.opacity && opts.cleartype && !opts.cleartypeNoBg)
            clearTypeFix($slides);
        if ($cont.css('position') == 'static')
            $cont.css('position', 'relative');
        if (opts.width)
            $cont.width(opts.width);
        if (opts.height && opts.height != 'auto')
            $cont.height(opts.height);
        if (opts.startingSlide !== undefined) {
            opts.startingSlide = parseInt(opts.startingSlide, 10);
            if (opts.startingSlide >= els.length || opts.startSlide < 0)
                opts.startingSlide = 0;
            else
                startingSlideSpecified = true;
        } else if (opts.backwards)
            opts.startingSlide = els.length - 1;
        else
            opts.startingSlide = 0; if (opts.random) {
            opts.randomMap = [];
            for (var i = 0; i < els.length; i++)
                opts.randomMap.push(i);
            opts.randomMap.sort(function(a, b) {
                return Math.random() - 0.5;
            });
            if (startingSlideSpecified) {
                for (var cnt = 0; cnt < els.length; cnt++) {
                    if (opts.startingSlide == opts.randomMap[cnt]) {
                        opts.randomIndex = cnt;
                    }
                }
            } else {
                opts.randomIndex = 1;
                opts.startingSlide = opts.randomMap[1];
            }
        } else if (opts.startingSlide >= els.length)
            opts.startingSlide = 0;
        opts.currSlide = opts.startingSlide || 0;
        var first = opts.startingSlide;
        $slides.css({
            position: 'absolute',
            top: 0,
            left: 0
        }).hide().each(function(i) {
            var z;
            if (opts.backwards)
                z = first ? i <= first ? els.length + (i - first) : first - i : els.length - i;
            else
                z = first ? i >= first ? els.length - (i - first) : first - i : els.length - i;
            $(this).css('z-index', z);
        });
        $(els[first]).css('opacity', 1).show();
        removeFilter(els[first], opts);
        if (opts.fit) {
            if (!opts.aspect) {
                if (opts.width)
                    $slides.width(opts.width);
                if (opts.height && opts.height != 'auto')
                    $slides.height(opts.height);
            } else {
                $slides.each(function() {
                    var $slide = $(this);
                    var ratio = (opts.aspect === true) ? $slide.width() / $slide.height() : opts.aspect;
                    if (opts.width && $slide.width() != opts.width) {
                        $slide.width(opts.width);
                        $slide.height(opts.width / ratio);
                    }
                    if (opts.height && $slide.height() < opts.height) {
                        $slide.height(opts.height);
                        $slide.width(opts.height * ratio);
                    }
                });
            }
        }
        if (opts.center && ((!opts.fit) || opts.aspect)) {
            $slides.each(function() {
                var $slide = $(this);
                $slide.css({
                    "margin-left": opts.width ? ((opts.width - $slide.width()) / 2) + "px" : 0,
                    "margin-top": opts.height ? ((opts.height - $slide.height()) / 2) + "px" : 0
                });
            });
        }
        if (opts.center && !opts.fit && !opts.slideResize) {
            $slides.each(function() {
                var $slide = $(this);
                $slide.css({
                    "margin-left": opts.width ? ((opts.width - $slide.width()) / 2) + "px" : 0,
                    "margin-top": opts.height ? ((opts.height - $slide.height()) / 2) + "px" : 0
                });
            });
        }
        var reshape = (opts.containerResize || opts.containerResizeHeight) && !$cont.innerHeight();
        if (reshape) {
            var maxw = 0,
                maxh = 0;
            for (var j = 0; j < els.length; j++) {
                var $e = $(els[j]),
                    e = $e[0],
                    w = $e.outerWidth(),
                    h = $e.outerHeight();
                if (!w) w = e.offsetWidth || e.width || $e.attr('width');
                if (!h) h = e.offsetHeight || e.height || $e.attr('height');
                maxw = w > maxw ? w : maxw;
                maxh = h > maxh ? h : maxh;
            }
            if (opts.containerResize && maxw > 0 && maxh > 0)
                $cont.css({
                    width: maxw + 'px',
                    height: maxh + 'px'
                });
            if (opts.containerResizeHeight && maxh > 0)
                $cont.css({
                    height: maxh + 'px'
                });
        }
        var pauseFlag = false;
        if (opts.pause)
            $cont.bind('mouseenter.cycle', function() {
                pauseFlag = true;
                this.cyclePause++;
                triggerPause(cont, true);
            }).bind('mouseleave.cycle', function() {
                if (pauseFlag)
                    this.cyclePause--;
                triggerPause(cont, true);
            });
        if (supportMultiTransitions(opts) === false)
            return false;
        var requeue = false;
        options.requeueAttempts = options.requeueAttempts || 0;
        $slides.each(function() {
            var $el = $(this);
            this.cycleH = (opts.fit && opts.height) ? opts.height : ($el.height() || this.offsetHeight || this.height || $el.attr('height') || 0);
            this.cycleW = (opts.fit && opts.width) ? opts.width : ($el.width() || this.offsetWidth || this.width || $el.attr('width') || 0);
            if ($el.is('img')) {
                var loadingIE = ($.browser.msie && this.cycleW == 28 && this.cycleH == 30 && !this.complete);
                var loadingFF = ($.browser.mozilla && this.cycleW == 34 && this.cycleH == 19 && !this.complete);
                var loadingOp = ($.browser.opera && ((this.cycleW == 42 && this.cycleH == 19) || (this.cycleW == 37 && this.cycleH == 17)) && !this.complete);
                var loadingOther = (this.cycleH === 0 && this.cycleW === 0 && !this.complete);
                if (loadingIE || loadingFF || loadingOp || loadingOther) {
                    if (o.s && opts.requeueOnImageNotLoaded && ++options.requeueAttempts < 100) {
                        log(options.requeueAttempts, ' - img slide not loaded, requeuing slideshow: ', this.src, this.cycleW, this.cycleH);
                        setTimeout(function() {
                            $(o.s, o.c).cycle(options);
                        }, opts.requeueTimeout);
                        requeue = true;
                        return false;
                    } else {
                        log('could not determine size of image: ' + this.src, this.cycleW, this.cycleH);
                    }
                }
            }
            return true;
        });
        if (requeue)
            return false;
        opts.cssBefore = opts.cssBefore || {};
        opts.cssAfter = opts.cssAfter || {};
        opts.cssFirst = opts.cssFirst || {};
        opts.animIn = opts.animIn || {};
        opts.animOut = opts.animOut || {};
        $slides.not(':eq(' + first + ')').css(opts.cssBefore);
        $($slides[first]).css(opts.cssFirst);
        if (opts.timeout) {
            opts.timeout = parseInt(opts.timeout, 10);
            if (opts.speed.constructor == String)
                opts.speed = $.fx.speeds[opts.speed] || parseInt(opts.speed, 10);
            if (!opts.sync)
                opts.speed = opts.speed / 2;
            var buffer = opts.fx == 'none' ? 0 : opts.fx == 'shuffle' ? 500 : 250;
            while ((opts.timeout - opts.speed) < buffer)
                opts.timeout += opts.speed;
        }
        if (opts.easing)
            opts.easeIn = opts.easeOut = opts.easing;
        if (!opts.speedIn)
            opts.speedIn = opts.speed;
        if (!opts.speedOut)
            opts.speedOut = opts.speed;
        opts.slideCount = els.length;
        opts.currSlide = opts.lastSlide = first;
        if (opts.random) {
            if (++opts.randomIndex == els.length)
                opts.randomIndex = 0;
            opts.nextSlide = opts.randomMap[opts.randomIndex];
        } else if (opts.backwards)
            opts.nextSlide = opts.startingSlide === 0 ? (els.length - 1) : opts.startingSlide - 1;
        else
            opts.nextSlide = opts.startingSlide >= (els.length - 1) ? 0 : opts.startingSlide + 1; if (!opts.multiFx) {
            var init = $.fn.cycle.transitions[opts.fx];
            if ($.isFunction(init))
                init($cont, $slides, opts);
            else if (opts.fx != 'custom' && !opts.multiFx) {
                log('unknown transition: ' + opts.fx, '; slideshow terminating');
                return false;
            }
        }
        var e0 = $slides[first];
        if (!opts.skipInitializationCallbacks) {
            if (opts.before.length)
                opts.before[0].apply(e0, [e0, e0, opts, true]);
            if (opts.after.length)
                opts.after[0].apply(e0, [e0, e0, opts, true]);
        }
        if (opts.next)
            $(opts.next).bind(opts.prevNextEvent, function() {
                return advance(opts, 1);
            });
        if (opts.prev)
            $(opts.prev).bind(opts.prevNextEvent, function() {
                return advance(opts, 0);
            });
        if (opts.pager || opts.pagerAnchorBuilder)
            buildPager(els, opts);
        exposeAddSlide(opts, els);
        return opts;
    }

    function saveOriginalOpts(opts) {
        opts.original = {
            before: [],
            after: []
        };
        opts.original.cssBefore = $.extend({}, opts.cssBefore);
        opts.original.cssAfter = $.extend({}, opts.cssAfter);
        opts.original.animIn = $.extend({}, opts.animIn);
        opts.original.animOut = $.extend({}, opts.animOut);
        $.each(opts.before, function() {
            opts.original.before.push(this);
        });
        $.each(opts.after, function() {
            opts.original.after.push(this);
        });
    }

    function supportMultiTransitions(opts) {
        var i, tx, txs = $.fn.cycle.transitions;
        if (opts.fx.indexOf(',') > 0) {
            opts.multiFx = true;
            opts.fxs = opts.fx.replace(/\s*/g, '').split(',');
            for (i = 0; i < opts.fxs.length; i++) {
                var fx = opts.fxs[i];
                tx = txs[fx];
                if (!tx || !txs.hasOwnProperty(fx) || !$.isFunction(tx)) {
                    log('discarding unknown transition: ', fx);
                    opts.fxs.splice(i, 1);
                    i--;
                }
            }
            if (!opts.fxs.length) {
                log('No valid transitions named; slideshow terminating.');
                return false;
            }
        } else if (opts.fx == 'all') {
            opts.multiFx = true;
            opts.fxs = [];
            for (var p in txs) {
                if (txs.hasOwnProperty(p)) {
                    tx = txs[p];
                    if (txs.hasOwnProperty(p) && $.isFunction(tx))
                        opts.fxs.push(p);
                }
            }
        }
        if (opts.multiFx && opts.randomizeEffects) {
            var r1 = Math.floor(Math.random() * 20) + 30;
            for (i = 0; i < r1; i++) {
                var r2 = Math.floor(Math.random() * opts.fxs.length);
                opts.fxs.push(opts.fxs.splice(r2, 1)[0]);
            }
            debug('randomized fx sequence: ', opts.fxs);
        }
        return true;
    }

    function exposeAddSlide(opts, els) {
        opts.addSlide = function(newSlide, prepend) {
            var $s = $(newSlide),
                s = $s[0];
            if (!opts.autostopCount)
                opts.countdown++;
            els[prepend ? 'unshift' : 'push'](s);
            if (opts.els)
                opts.els[prepend ? 'unshift' : 'push'](s);
            opts.slideCount = els.length;
            if (opts.random) {
                opts.randomMap.push(opts.slideCount - 1);
                opts.randomMap.sort(function(a, b) {
                    return Math.random() - 0.5;
                });
            }
            $s.css('position', 'absolute');
            $s[prepend ? 'prependTo' : 'appendTo'](opts.$cont);
            if (prepend) {
                opts.currSlide++;
                opts.nextSlide++;
            }
            if (!$.support.opacity && opts.cleartype && !opts.cleartypeNoBg)
                clearTypeFix($s);
            if (opts.fit && opts.width)
                $s.width(opts.width);
            if (opts.fit && opts.height && opts.height != 'auto')
                $s.height(opts.height);
            s.cycleH = (opts.fit && opts.height) ? opts.height : $s.height();
            s.cycleW = (opts.fit && opts.width) ? opts.width : $s.width();
            $s.css(opts.cssBefore);
            if (opts.pager || opts.pagerAnchorBuilder)
                $.fn.cycle.createPagerAnchor(els.length - 1, s, $(opts.pager), els, opts);
            if ($.isFunction(opts.onAddSlide))
                opts.onAddSlide($s);
            else
                $s.hide();
        };
    }
    $.fn.cycle.resetState = function(opts, fx) {
        fx = fx || opts.fx;
        opts.before = [];
        opts.after = [];
        opts.cssBefore = $.extend({}, opts.original.cssBefore);
        opts.cssAfter = $.extend({}, opts.original.cssAfter);
        opts.animIn = $.extend({}, opts.original.animIn);
        opts.animOut = $.extend({}, opts.original.animOut);
        opts.fxFn = null;
        $.each(opts.original.before, function() {
            opts.before.push(this);
        });
        $.each(opts.original.after, function() {
            opts.after.push(this);
        });
        var init = $.fn.cycle.transitions[fx];
        if ($.isFunction(init))
            init(opts.$cont, $(opts.elements), opts);
    };

    function go(els, opts, manual, fwd) {
        var p = opts.$cont[0],
            curr = els[opts.currSlide],
            next = els[opts.nextSlide];
        if (manual && opts.busy && opts.manualTrump) {
            debug('manualTrump in go(), stopping active transition');
            $(els).stop(true, true);
            opts.busy = 0;
            clearTimeout(p.cycleTimeout);
        }
        if (opts.busy) {
            debug('transition active, ignoring new tx request');
            return;
        }
        if (p.cycleStop != opts.stopCount || p.cycleTimeout === 0 && !manual)
            return;
        if (!manual && !p.cyclePause && !opts.bounce && ((opts.autostop && (--opts.countdown <= 0)) || (opts.nowrap && !opts.random && opts.nextSlide < opts.currSlide))) {
            if (opts.end)
                opts.end(opts);
            return;
        }
        var changed = false;
        if ((manual || !p.cyclePause) && (opts.nextSlide != opts.currSlide)) {
            changed = true;
            var fx = opts.fx;
            curr.cycleH = curr.cycleH || $(curr).height();
            curr.cycleW = curr.cycleW || $(curr).width();
            next.cycleH = next.cycleH || $(next).height();
            next.cycleW = next.cycleW || $(next).width();
            if (opts.multiFx) {
                if (fwd && (opts.lastFx === undefined || ++opts.lastFx >= opts.fxs.length))
                    opts.lastFx = 0;
                else if (!fwd && (opts.lastFx === undefined || --opts.lastFx < 0))
                    opts.lastFx = opts.fxs.length - 1;
                fx = opts.fxs[opts.lastFx];
            }
            if (opts.oneTimeFx) {
                fx = opts.oneTimeFx;
                opts.oneTimeFx = null;
            }
            $.fn.cycle.resetState(opts, fx);
            if (opts.before.length)
                $.each(opts.before, function(i, o) {
                    if (p.cycleStop != opts.stopCount) return;
                    o.apply(next, [curr, next, opts, fwd]);
                });
            var after = function() {
                opts.busy = 0;
                $.each(opts.after, function(i, o) {
                    if (p.cycleStop != opts.stopCount) return;
                    o.apply(next, [curr, next, opts, fwd]);
                });
                if (!p.cycleStop) {
                    queueNext();
                }
            };
            debug('tx firing(' + fx + '); currSlide: ' + opts.currSlide + '; nextSlide: ' + opts.nextSlide);
            opts.busy = 1;
            if (opts.fxFn)
                opts.fxFn(curr, next, opts, after, fwd, manual && opts.fastOnEvent);
            else if ($.isFunction($.fn.cycle[opts.fx]))
                $.fn.cycle[opts.fx](curr, next, opts, after, fwd, manual && opts.fastOnEvent);
            else
                $.fn.cycle.custom(curr, next, opts, after, fwd, manual && opts.fastOnEvent);
        } else {
            queueNext();
        }
        if (changed || opts.nextSlide == opts.currSlide) {
            var roll;
            opts.lastSlide = opts.currSlide;
            if (opts.random) {
                opts.currSlide = opts.nextSlide;
                if (++opts.randomIndex == els.length) {
                    opts.randomIndex = 0;
                    opts.randomMap.sort(function(a, b) {
                        return Math.random() - 0.5;
                    });
                }
                opts.nextSlide = opts.randomMap[opts.randomIndex];
                if (opts.nextSlide == opts.currSlide)
                    opts.nextSlide = (opts.currSlide == opts.slideCount - 1) ? 0 : opts.currSlide + 1;
            } else if (opts.backwards) {
                roll = (opts.nextSlide - 1) < 0;
                if (roll && opts.bounce) {
                    opts.backwards = !opts.backwards;
                    opts.nextSlide = 1;
                    opts.currSlide = 0;
                } else {
                    opts.nextSlide = roll ? (els.length - 1) : opts.nextSlide - 1;
                    opts.currSlide = roll ? 0 : opts.nextSlide + 1;
                }
            } else {
                roll = (opts.nextSlide + 1) == els.length;
                if (roll && opts.bounce) {
                    opts.backwards = !opts.backwards;
                    opts.nextSlide = els.length - 2;
                    opts.currSlide = els.length - 1;
                } else {
                    opts.nextSlide = roll ? 0 : opts.nextSlide + 1;
                    opts.currSlide = roll ? els.length - 1 : opts.nextSlide - 1;
                }
            }
        }
        if (changed && opts.pager)
            opts.updateActivePagerLink(opts.pager, opts.currSlide, opts.activePagerClass);

        function queueNext() {
            var ms = 0,
                timeout = opts.timeout;
            if (opts.timeout && !opts.continuous) {
                ms = getTimeout(els[opts.currSlide], els[opts.nextSlide], opts, fwd);
                if (opts.fx == 'shuffle')
                    ms -= opts.speedOut;
            } else if (opts.continuous && p.cyclePause)
                ms = 10;
            if (ms > 0)
                p.cycleTimeout = setTimeout(function() {
                    go(els, opts, 0, !opts.backwards);
                }, ms);
        }
    }
    $.fn.cycle.updateActivePagerLink = function(pager, currSlide, clsName) {
        $(pager).each(function() {
            $(this).children().removeClass(clsName).eq(currSlide).addClass(clsName);
        });
    };

    function getTimeout(curr, next, opts, fwd) {
        if (opts.timeoutFn) {
            var t = opts.timeoutFn.call(curr, curr, next, opts, fwd);
            while (opts.fx != 'none' && (t - opts.speed) < 250)
                t += opts.speed;
            debug('calculated timeout: ' + t + '; speed: ' + opts.speed);
            if (t !== false)
                return t;
        }
        return opts.timeout;
    }
    $.fn.cycle.next = function(opts) {
        advance(opts, 1);
    };
    $.fn.cycle.prev = function(opts) {
        advance(opts, 0);
    };

    function advance(opts, moveForward) {
        var val = moveForward ? 1 : -1;
        var els = opts.elements;
        var p = opts.$cont[0],
            timeout = p.cycleTimeout;
        if (timeout) {
            clearTimeout(timeout);
            p.cycleTimeout = 0;
        }
        if (opts.random && val < 0) {
            opts.randomIndex--;
            if (--opts.randomIndex == -2)
                opts.randomIndex = els.length - 2;
            else if (opts.randomIndex == -1)
                opts.randomIndex = els.length - 1;
            opts.nextSlide = opts.randomMap[opts.randomIndex];
        } else if (opts.random) {
            opts.nextSlide = opts.randomMap[opts.randomIndex];
        } else {
            opts.nextSlide = opts.currSlide + val;
            if (opts.nextSlide < 0) {
                if (opts.nowrap) return false;
                opts.nextSlide = els.length - 1;
            } else if (opts.nextSlide >= els.length) {
                if (opts.nowrap) return false;
                opts.nextSlide = 0;
            }
        }
        var cb = opts.onPrevNextEvent || opts.prevNextClick;
        if ($.isFunction(cb))
            cb(val > 0, opts.nextSlide, els[opts.nextSlide]);
        go(els, opts, 1, moveForward);
        return false;
    }

    function buildPager(els, opts) {
        var $p = $(opts.pager);
        $.each(els, function(i, o) {
            $.fn.cycle.createPagerAnchor(i, o, $p, els, opts);
        });
        opts.updateActivePagerLink(opts.pager, opts.startingSlide, opts.activePagerClass);
    }
    $.fn.cycle.createPagerAnchor = function(i, el, $p, els, opts) {
        var a;
        if ($.isFunction(opts.pagerAnchorBuilder)) {
            a = opts.pagerAnchorBuilder(i, el);
            debug('pagerAnchorBuilder(' + i + ', el) returned: ' + a);
        } else
            a = '<a href="#">' + (i + 1) + '</a>'; if (!a)
            return;
        var $a = $(a);
        if ($a.parents('body').length === 0) {
            var arr = [];
            if ($p.length > 1) {
                $p.each(function() {
                    var $clone = $a.clone(true);
                    $(this).append($clone);
                    arr.push($clone[0]);
                });
                $a = $(arr);
            } else {
                $a.appendTo($p);
            }
        }
        opts.pagerAnchors = opts.pagerAnchors || [];
        opts.pagerAnchors.push($a);
        var pagerFn = function(e) {
            e.preventDefault();
            opts.nextSlide = i;
            var p = opts.$cont[0],
                timeout = p.cycleTimeout;
            if (timeout) {
                clearTimeout(timeout);
                p.cycleTimeout = 0;
            }
            var cb = opts.onPagerEvent || opts.pagerClick;
            if ($.isFunction(cb))
                cb(opts.nextSlide, els[opts.nextSlide]);
            go(els, opts, 1, opts.currSlide < i);
        };
        if (/mouseenter|mouseover/i.test(opts.pagerEvent)) {
            $a.hover(pagerFn, function() {});
        } else {
            $a.bind(opts.pagerEvent, pagerFn);
        }
        if (!/^click/.test(opts.pagerEvent) && !opts.allowPagerClickBubble)
            $a.bind('click.cycle', function() {
                return false;
            });
        var cont = opts.$cont[0];
        var pauseFlag = false;
        if (opts.pauseOnPagerHover) {
            $a.hover(function() {
                pauseFlag = true;
                cont.cyclePause++;
                triggerPause(cont, true, true);
            }, function() {
                if (pauseFlag)
                    cont.cyclePause--;
                triggerPause(cont, true, true);
            });
        }
    };
    $.fn.cycle.hopsFromLast = function(opts, fwd) {
        var hops, l = opts.lastSlide,
            c = opts.currSlide;
        if (fwd)
            hops = c > l ? c - l : opts.slideCount - l;
        else
            hops = c < l ? l - c : l + opts.slideCount - c;
        return hops;
    };

    function clearTypeFix($slides) {
        debug('applying clearType background-color hack');

        function hex(s) {
            s = parseInt(s, 10).toString(16);
            return s.length < 2 ? '0' + s : s;
        }

        function getBg(e) {
            for (; e && e.nodeName.toLowerCase() != 'html'; e = e.parentNode) {
                var v = $.css(e, 'background-color');
                if (v && v.indexOf('rgb') >= 0) {
                    var rgb = v.match(/\d+/g);
                    return '#' + hex(rgb[0]) + hex(rgb[1]) + hex(rgb[2]);
                }
                if (v && v != 'transparent')
                    return v;
            }
            return '#ffffff';
        }
        $slides.each(function() {
            $(this).css('background-color', getBg(this));
        });
    }
    $.fn.cycle.commonReset = function(curr, next, opts, w, h, rev) {
        $(opts.elements).not(curr).hide();
        if (typeof opts.cssBefore.opacity == 'undefined')
            opts.cssBefore.opacity = 1;
        opts.cssBefore.display = 'block';
        if (opts.slideResize && w !== false && next.cycleW > 0)
            opts.cssBefore.width = next.cycleW;
        if (opts.slideResize && h !== false && next.cycleH > 0)
            opts.cssBefore.height = next.cycleH;
        opts.cssAfter = opts.cssAfter || {};
        opts.cssAfter.display = 'none';
        $(curr).css('zIndex', opts.slideCount + (rev === true ? 1 : 0));
        $(next).css('zIndex', opts.slideCount + (rev === true ? 0 : 1));
    };
    $.fn.cycle.custom = function(curr, next, opts, cb, fwd, speedOverride) {
        var $l = $(curr),
            $n = $(next);
        var speedIn = opts.speedIn,
            speedOut = opts.speedOut,
            easeIn = opts.easeIn,
            easeOut = opts.easeOut;
        $n.css(opts.cssBefore);
        if (speedOverride) {
            if (typeof speedOverride == 'number')
                speedIn = speedOut = speedOverride;
            else
                speedIn = speedOut = 1;
            easeIn = easeOut = null;
        }
        var fn = function() {
            $n.animate(opts.animIn, speedIn, easeIn, function() {
                cb();
            });
        };
        $l.animate(opts.animOut, speedOut, easeOut, function() {
            $l.css(opts.cssAfter);
            if (!opts.sync)
                fn();
        });
        if (opts.sync) fn();
    };
    $.fn.cycle.transitions = {
        fade: function($cont, $slides, opts) {
            $slides.not(':eq(' + opts.currSlide + ')').css('opacity', 0);
            opts.before.push(function(curr, next, opts) {
                $.fn.cycle.commonReset(curr, next, opts);
                opts.cssBefore.opacity = 0;
            });
            opts.animIn = {
                opacity: 1
            };
            opts.animOut = {
                opacity: 0
            };
            opts.cssBefore = {
                top: 0,
                left: 0
            };
        }
    };
    $.fn.cycle.ver = function() {
        return ver;
    };
    $.fn.cycle.defaults = {
        activePagerClass: 'activeSlide',
        after: null,
        allowPagerClickBubble: false,
        animIn: null,
        animOut: null,
        aspect: false,
        autostop: 0,
        autostopCount: 0,
        backwards: false,
        before: null,
        center: null,
        cleartype: !$.support.opacity,
        cleartypeNoBg: false,
        containerResize: 1,
        containerResizeHeight: 0,
        continuous: 0,
        cssAfter: null,
        cssBefore: null,
        delay: 0,
        easeIn: null,
        easeOut: null,
        easing: null,
        end: null,
        fastOnEvent: 0,
        fit: 0,
        fx: 'fade',
        fxFn: null,
        height: 'auto',
        manualTrump: true,
        metaAttr: 'cycle',
        next: null,
        nowrap: 0,
        onPagerEvent: null,
        onPrevNextEvent: null,
        pager: null,
        pagerAnchorBuilder: null,
        pagerEvent: 'click.cycle',
        pause: 0,
        pauseOnPagerHover: 0,
        prev: null,
        prevNextEvent: 'click.cycle',
        random: 0,
        randomizeEffects: 1,
        requeueOnImageNotLoaded: true,
        requeueTimeout: 250,
        rev: 0,
        shuffle: null,
        skipInitializationCallbacks: false,
        slideExpr: null,
        slideResize: 1,
        speed: 1000,
        speedIn: null,
        speedOut: null,
        startingSlide: undefined,
        sync: 1,
        timeout: 4000,
        timeoutFn: null,
        updateActivePagerLink: null,
        width: null
    };
})(jQuery);
(function($) {
    "use strict";
    $.fn.cycle.transitions.none = function($cont, $slides, opts) {
        opts.fxFn = function(curr, next, opts, after) {
            $(next).show();
            $(curr).hide();
            after();
        };
    };
    $.fn.cycle.transitions.fadeout = function($cont, $slides, opts) {
        $slides.not(':eq(' + opts.currSlide + ')').css({
            display: 'block',
            'opacity': 1
        });
        opts.before.push(function(curr, next, opts, w, h, rev) {
            $(curr).css('zIndex', opts.slideCount + (rev !== true ? 1 : 0));
            $(next).css('zIndex', opts.slideCount + (rev !== true ? 0 : 1));
        });
        opts.animIn.opacity = 1;
        opts.animOut.opacity = 0;
        opts.cssBefore.opacity = 1;
        opts.cssBefore.display = 'block';
        opts.cssAfter.zIndex = 0;
    };
    $.fn.cycle.transitions.scrollUp = function($cont, $slides, opts) {
        $cont.css('overflow', 'hidden');
        opts.before.push($.fn.cycle.commonReset);
        var h = $cont.height();
        opts.cssBefore.top = h;
        opts.cssBefore.left = 0;
        opts.cssFirst.top = 0;
        opts.animIn.top = 0;
        opts.animOut.top = -h;
    };
    $.fn.cycle.transitions.scrollDown = function($cont, $slides, opts) {
        $cont.css('overflow', 'hidden');
        opts.before.push($.fn.cycle.commonReset);
        var h = $cont.height();
        opts.cssFirst.top = 0;
        opts.cssBefore.top = -h;
        opts.cssBefore.left = 0;
        opts.animIn.top = 0;
        opts.animOut.top = h;
    };
    $.fn.cycle.transitions.scrollLeft = function($cont, $slides, opts) {
        $cont.css('overflow', 'hidden');
        opts.before.push($.fn.cycle.commonReset);
        var w = $cont.width();
        opts.cssFirst.left = 0;
        opts.cssBefore.left = w;
        opts.cssBefore.top = 0;
        opts.animIn.left = 0;
        opts.animOut.left = 0 - w;
    };
    $.fn.cycle.transitions.scrollRight = function($cont, $slides, opts) {
        $cont.css('overflow', 'hidden');
        opts.before.push($.fn.cycle.commonReset);
        var w = $cont.width();
        opts.cssFirst.left = 0;
        opts.cssBefore.left = -w;
        opts.cssBefore.top = 0;
        opts.animIn.left = 0;
        opts.animOut.left = w;
    };
    $.fn.cycle.transitions.scrollHorz = function($cont, $slides, opts) {
        $cont.css('overflow', 'hidden').width();
        opts.before.push(function(curr, next, opts, fwd) {
            if (opts.rev)
                fwd = !fwd;
            $.fn.cycle.commonReset(curr, next, opts);
            opts.cssBefore.left = fwd ? (next.cycleW - 1) : (1 - next.cycleW);
            opts.animOut.left = fwd ? -curr.cycleW : curr.cycleW;
        });
        opts.cssFirst.left = 0;
        opts.cssBefore.top = 0;
        opts.animIn.left = 0;
        opts.animOut.top = 0;
    };
    $.fn.cycle.transitions.scrollVert = function($cont, $slides, opts) {
        $cont.css('overflow', 'hidden');
        opts.before.push(function(curr, next, opts, fwd) {
            if (opts.rev)
                fwd = !fwd;
            $.fn.cycle.commonReset(curr, next, opts);
            opts.cssBefore.top = fwd ? (1 - next.cycleH) : (next.cycleH - 1);
            opts.animOut.top = fwd ? curr.cycleH : -curr.cycleH;
        });
        opts.cssFirst.top = 0;
        opts.cssBefore.left = 0;
        opts.animIn.top = 0;
        opts.animOut.left = 0;
    };
    $.fn.cycle.transitions.slideX = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
            $(opts.elements).not(curr).hide();
            $.fn.cycle.commonReset(curr, next, opts, false, true);
            opts.animIn.width = next.cycleW;
        });
        opts.cssBefore.left = 0;
        opts.cssBefore.top = 0;
        opts.cssBefore.width = 0;
        opts.animIn.width = 'show';
        opts.animOut.width = 0;
    };
    $.fn.cycle.transitions.slideY = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
            $(opts.elements).not(curr).hide();
            $.fn.cycle.commonReset(curr, next, opts, true, false);
            opts.animIn.height = next.cycleH;
        });
        opts.cssBefore.left = 0;
        opts.cssBefore.top = 0;
        opts.cssBefore.height = 0;
        opts.animIn.height = 'show';
        opts.animOut.height = 0;
    };
    $.fn.cycle.transitions.shuffle = function($cont, $slides, opts) {
        var i, w = $cont.css('overflow', 'visible').width();
        $slides.css({
            left: 0,
            top: 0
        });
        opts.before.push(function(curr, next, opts) {
            $.fn.cycle.commonReset(curr, next, opts, true, true, true);
        });
        if (!opts.speedAdjusted) {
            opts.speed = opts.speed / 2;
            opts.speedAdjusted = true;
        }
        opts.random = 0;
        opts.shuffle = opts.shuffle || {
            left: -w,
            top: 15
        };
        opts.els = [];
        for (i = 0; i < $slides.length; i++)
            opts.els.push($slides[i]);
        for (i = 0; i < opts.currSlide; i++)
            opts.els.push(opts.els.shift());
        opts.fxFn = function(curr, next, opts, cb, fwd) {
            if (opts.rev)
                fwd = !fwd;
            var $el = fwd ? $(curr) : $(next);
            $(next).css(opts.cssBefore);
            var count = opts.slideCount;
            $el.animate(opts.shuffle, opts.speedIn, opts.easeIn, function() {
                var hops = $.fn.cycle.hopsFromLast(opts, fwd);
                for (var k = 0; k < hops; k++) {
                    if (fwd)
                        opts.els.push(opts.els.shift());
                    else
                        opts.els.unshift(opts.els.pop());
                }
                if (fwd) {
                    for (var i = 0, len = opts.els.length; i < len; i++)
                        $(opts.els[i]).css('z-index', len - i + count);
                } else {
                    var z = $(curr).css('z-index');
                    $el.css('z-index', parseInt(z, 10) + 1 + count);
                }
                $el.animate({
                    left: 0,
                    top: 0
                }, opts.speedOut, opts.easeOut, function() {
                    $(fwd ? this : curr).hide();
                    if (cb) cb();
                });
            });
        };
        $.extend(opts.cssBefore, {
            display: 'block',
            opacity: 1,
            top: 0,
            left: 0
        });
    };
    $.fn.cycle.transitions.turnUp = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
            $.fn.cycle.commonReset(curr, next, opts, true, false);
            opts.cssBefore.top = next.cycleH;
            opts.animIn.height = next.cycleH;
            opts.animOut.width = next.cycleW;
        });
        opts.cssFirst.top = 0;
        opts.cssBefore.left = 0;
        opts.cssBefore.height = 0;
        opts.animIn.top = 0;
        opts.animOut.height = 0;
    };
    $.fn.cycle.transitions.turnDown = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
            $.fn.cycle.commonReset(curr, next, opts, true, false);
            opts.animIn.height = next.cycleH;
            opts.animOut.top = curr.cycleH;
        });
        opts.cssFirst.top = 0;
        opts.cssBefore.left = 0;
        opts.cssBefore.top = 0;
        opts.cssBefore.height = 0;
        opts.animOut.height = 0;
    };
    $.fn.cycle.transitions.turnLeft = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
            $.fn.cycle.commonReset(curr, next, opts, false, true);
            opts.cssBefore.left = next.cycleW;
            opts.animIn.width = next.cycleW;
        });
        opts.cssBefore.top = 0;
        opts.cssBefore.width = 0;
        opts.animIn.left = 0;
        opts.animOut.width = 0;
    };
    $.fn.cycle.transitions.turnRight = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
            $.fn.cycle.commonReset(curr, next, opts, false, true);
            opts.animIn.width = next.cycleW;
            opts.animOut.left = curr.cycleW;
        });
        $.extend(opts.cssBefore, {
            top: 0,
            left: 0,
            width: 0
        });
        opts.animIn.left = 0;
        opts.animOut.width = 0;
    };
    $.fn.cycle.transitions.zoom = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
            $.fn.cycle.commonReset(curr, next, opts, false, false, true);
            opts.cssBefore.top = next.cycleH / 2;
            opts.cssBefore.left = next.cycleW / 2;
            $.extend(opts.animIn, {
                top: 0,
                left: 0,
                width: next.cycleW,
                height: next.cycleH
            });
            $.extend(opts.animOut, {
                width: 0,
                height: 0,
                top: curr.cycleH / 2,
                left: curr.cycleW / 2
            });
        });
        opts.cssFirst.top = 0;
        opts.cssFirst.left = 0;
        opts.cssBefore.width = 0;
        opts.cssBefore.height = 0;
    };
    $.fn.cycle.transitions.fadeZoom = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
            $.fn.cycle.commonReset(curr, next, opts, false, false);
            opts.cssBefore.left = next.cycleW / 2;
            opts.cssBefore.top = next.cycleH / 2;
            $.extend(opts.animIn, {
                top: 0,
                left: 0,
                width: next.cycleW,
                height: next.cycleH
            });
        });
        opts.cssBefore.width = 0;
        opts.cssBefore.height = 0;
        opts.animOut.opacity = 0;
    };
    $.fn.cycle.transitions.blindX = function($cont, $slides, opts) {
        var w = $cont.css('overflow', 'hidden').width();
        opts.before.push(function(curr, next, opts) {
            $.fn.cycle.commonReset(curr, next, opts);
            opts.animIn.width = next.cycleW;
            opts.animOut.left = curr.cycleW;
        });
        opts.cssBefore.left = w;
        opts.cssBefore.top = 0;
        opts.animIn.left = 0;
        opts.animOut.left = w;
    };
    $.fn.cycle.transitions.blindY = function($cont, $slides, opts) {
        var h = $cont.css('overflow', 'hidden').height();
        opts.before.push(function(curr, next, opts) {
            $.fn.cycle.commonReset(curr, next, opts);
            opts.animIn.height = next.cycleH;
            opts.animOut.top = curr.cycleH;
        });
        opts.cssBefore.top = h;
        opts.cssBefore.left = 0;
        opts.animIn.top = 0;
        opts.animOut.top = h;
    };
    $.fn.cycle.transitions.blindZ = function($cont, $slides, opts) {
        var h = $cont.css('overflow', 'hidden').height();
        var w = $cont.width();
        opts.before.push(function(curr, next, opts) {
            $.fn.cycle.commonReset(curr, next, opts);
            opts.animIn.height = next.cycleH;
            opts.animOut.top = curr.cycleH;
        });
        opts.cssBefore.top = h;
        opts.cssBefore.left = w;
        opts.animIn.top = 0;
        opts.animIn.left = 0;
        opts.animOut.top = h;
        opts.animOut.left = w;
    };
    $.fn.cycle.transitions.growX = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
            $.fn.cycle.commonReset(curr, next, opts, false, true);
            opts.cssBefore.left = this.cycleW / 2;
            opts.animIn.left = 0;
            opts.animIn.width = this.cycleW;
            opts.animOut.left = 0;
        });
        opts.cssBefore.top = 0;
        opts.cssBefore.width = 0;
    };
    $.fn.cycle.transitions.growY = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
            $.fn.cycle.commonReset(curr, next, opts, true, false);
            opts.cssBefore.top = this.cycleH / 2;
            opts.animIn.top = 0;
            opts.animIn.height = this.cycleH;
            opts.animOut.top = 0;
        });
        opts.cssBefore.height = 0;
        opts.cssBefore.left = 0;
    };
    $.fn.cycle.transitions.curtainX = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
            $.fn.cycle.commonReset(curr, next, opts, false, true, true);
            opts.cssBefore.left = next.cycleW / 2;
            opts.animIn.left = 0;
            opts.animIn.width = this.cycleW;
            opts.animOut.left = curr.cycleW / 2;
            opts.animOut.width = 0;
        });
        opts.cssBefore.top = 0;
        opts.cssBefore.width = 0;
    };
    $.fn.cycle.transitions.curtainY = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
            $.fn.cycle.commonReset(curr, next, opts, true, false, true);
            opts.cssBefore.top = next.cycleH / 2;
            opts.animIn.top = 0;
            opts.animIn.height = next.cycleH;
            opts.animOut.top = curr.cycleH / 2;
            opts.animOut.height = 0;
        });
        opts.cssBefore.height = 0;
        opts.cssBefore.left = 0;
    };
    $.fn.cycle.transitions.cover = function($cont, $slides, opts) {
        var d = opts.direction || 'left';
        var w = $cont.css('overflow', 'hidden').width();
        var h = $cont.height();
        opts.before.push(function(curr, next, opts) {
            $.fn.cycle.commonReset(curr, next, opts);
            if (d == 'right')
                opts.cssBefore.left = -w;
            else if (d == 'up')
                opts.cssBefore.top = h;
            else if (d == 'down')
                opts.cssBefore.top = -h;
            else
                opts.cssBefore.left = w;
        });
        opts.animIn.left = 0;
        opts.animIn.top = 0;
        opts.cssBefore.top = 0;
        opts.cssBefore.left = 0;
    };
    $.fn.cycle.transitions.uncover = function($cont, $slides, opts) {
        var d = opts.direction || 'left';
        var w = $cont.css('overflow', 'hidden').width();
        var h = $cont.height();
        opts.before.push(function(curr, next, opts) {
            $.fn.cycle.commonReset(curr, next, opts, true, true, true);
            if (d == 'right')
                opts.animOut.left = w;
            else if (d == 'up')
                opts.animOut.top = -h;
            else if (d == 'down')
                opts.animOut.top = h;
            else
                opts.animOut.left = -w;
        });
        opts.animIn.left = 0;
        opts.animIn.top = 0;
        opts.cssBefore.top = 0;
        opts.cssBefore.left = 0;
    };
    $.fn.cycle.transitions.toss = function($cont, $slides, opts) {
        var w = $cont.css('overflow', 'visible').width();
        var h = $cont.height();
        opts.before.push(function(curr, next, opts) {
            $.fn.cycle.commonReset(curr, next, opts, true, true, true);
            if (!opts.animOut.left && !opts.animOut.top)
                $.extend(opts.animOut, {
                    left: w * 2,
                    top: -h / 2,
                    opacity: 0
                });
            else
                opts.animOut.opacity = 0;
        });
        opts.cssBefore.left = 0;
        opts.cssBefore.top = 0;
        opts.animIn.left = 0;
    };
    $.fn.cycle.transitions.wipe = function($cont, $slides, opts) {
        var w = $cont.css('overflow', 'hidden').width();
        var h = $cont.height();
        opts.cssBefore = opts.cssBefore || {};
        var clip;
        if (opts.clip) {
            if (/l2r/.test(opts.clip))
                clip = 'rect(0px 0px ' + h + 'px 0px)';
            else if (/r2l/.test(opts.clip))
                clip = 'rect(0px ' + w + 'px ' + h + 'px ' + w + 'px)';
            else if (/t2b/.test(opts.clip))
                clip = 'rect(0px ' + w + 'px 0px 0px)';
            else if (/b2t/.test(opts.clip))
                clip = 'rect(' + h + 'px ' + w + 'px ' + h + 'px 0px)';
            else if (/zoom/.test(opts.clip)) {
                var top = parseInt(h / 2, 10);
                var left = parseInt(w / 2, 10);
                clip = 'rect(' + top + 'px ' + left + 'px ' + top + 'px ' + left + 'px)';
            }
        }
        opts.cssBefore.clip = opts.cssBefore.clip || clip || 'rect(0px 0px 0px 0px)';
        var d = opts.cssBefore.clip.match(/(\d+)/g);
        var t = parseInt(d[0], 10),
            r = parseInt(d[1], 10),
            b = parseInt(d[2], 10),
            l = parseInt(d[3], 10);
        opts.before.push(function(curr, next, opts) {
            if (curr == next) return;
            var $curr = $(curr),
                $next = $(next);
            $.fn.cycle.commonReset(curr, next, opts, true, true, false);
            opts.cssAfter.display = 'block';
            var step = 1,
                count = parseInt((opts.speedIn / 13), 10) - 1;
            (function f() {
                var tt = t ? t - parseInt(step * (t / count), 10) : 0;
                var ll = l ? l - parseInt(step * (l / count), 10) : 0;
                var bb = b < h ? b + parseInt(step * ((h - b) / count || 1), 10) : h;
                var rr = r < w ? r + parseInt(step * ((w - r) / count || 1), 10) : w;
                $next.css({
                    clip: 'rect(' + tt + 'px ' + rr + 'px ' + bb + 'px ' + ll + 'px)'
                });
                (step++ <= count) ? setTimeout(f, 13): $curr.css('display', 'none');
            })();
        });
        $.extend(opts.cssBefore, {
            display: 'block',
            opacity: 1,
            top: 0,
            left: 0
        });
        opts.animIn = {
            left: 0
        };
        opts.animOut = {
            left: 0
        };
    };
})(jQuery);

jQuery(document).ready(function() {
    jQuery('.currentmenu2,.currentmenu2 div').click(function() {
        jQuery(this).parent().find('ul.mega').slideToggle('slow', function() {});
    });
});
(function(a, b, c) {
    "use strict";
    var d = b.event,
        e;
    d.special.smartresize = {
        setup: function() {
            b(this).bind("resize", d.special.smartresize.handler)
        },
        teardown: function() {
            b(this).unbind("resize", d.special.smartresize.handler)
        },
        handler: function(a, b) {
            var c = this,
                d = arguments;
            a.type = "smartresize", e && clearTimeout(e), e = setTimeout(function() {
                jQuery.event.handle.apply(c, d)
            }, b === "execAsap" ? 0 : 100)
        }
    }, b.fn.smartresize = function(a) {
        return a ? this.bind("smartresize", a) : this.trigger("smartresize", ["execAsap"])
    }, b.Mason = function(a, c) {
        this.element = b(c), this._create(a), this._init()
    }, b.Mason.settings = {
        isResizable: !0,
        isAnimated: !1,
        animationOptions: {
            queue: !1,
            duration: 500
        },
        gutterWidth: 0,
        isRTL: !1,
        isFitWidth: !1,
        containerStyle: {
            position: "relative"
        }
    }, b.Mason.prototype = {
        _filterFindBricks: function(a) {
            var b = this.options.itemSelector;
            return b ? a.filter(b).add(a.find(b)) : a
        },
        _getBricks: function(a) {
            var b = this._filterFindBricks(a).css({
                position: "absolute"
            }).addClass("masonry-brick");
            return b
        },
        _create: function(c) {
            this.options = b.extend(!0, {}, b.Mason.settings, c), this.styleQueue = [];
            var d = this.element[0].style;
            this.originalStyle = {
                height: d.height || ""
            };
            var e = this.options.containerStyle;
            for (var f in e) this.originalStyle[f] = d[f] || "";
            this.element.css(e), this.horizontalDirection = this.options.isRTL ? "right" : "left", this.offset = {
                x: parseInt(this.element.css("padding-" + this.horizontalDirection), 10),
                y: parseInt(this.element.css("padding-top"), 10)
            }, this.isFluid = this.options.columnWidth && typeof this.options.columnWidth == "function";
            var g = this;
            setTimeout(function() {
                g.element.addClass("masonry")
            }, 0), this.options.isResizable && b(a).bind("smartresize.masonry", function() {
                g.resize()
            }), this.reloadItems()
        },
        _init: function(a) {
            this._getColumns(), this._reLayout(a)
        },
        option: function(a, c) {
            b.isPlainObject(a) && (this.options = b.extend(!0, this.options, a))
        },
        layout: function(a, b) {
            for (var c = 0, d = a.length; c < d; c++) this._placeBrick(a[c]);
            var e = {};
            e.height = Math.max.apply(Math, this.colYs);
            if (this.options.isFitWidth) {
                var f = 0;
                c = this.cols;
                while (--c) {
                    if (this.colYs[c] !== 0) break;
                    f++
                }
                e.width = (this.cols - f) * this.columnWidth - this.options.gutterWidth
            }
            this.styleQueue.push({
                $el: this.element,
                style: e
            });
            var g = this.isLaidOut ? this.options.isAnimated ? "animate" : "css" : "css",
                h = this.options.animationOptions,
                i;
            for (c = 0, d = this.styleQueue.length; c < d; c++) i = this.styleQueue[c], i.$el[g](i.style, h);
            this.styleQueue = [], b && b.call(a), this.isLaidOut = !0
        },
        _getColumns: function() {
            var a = this.options.isFitWidth ? this.element.parent() : this.element,
                b = a.width();
            this.columnWidth = this.isFluid ? this.options.columnWidth(b) : this.options.columnWidth || this.$bricks.outerWidth(!0) || b, this.columnWidth += this.options.gutterWidth, this.cols = Math.floor((b + this.options.gutterWidth) / this.columnWidth), this.cols = Math.max(this.cols, 1)
        },
        _placeBrick: function(a) {
            var c = b(a),
                d, e, f, g, h;
            d = Math.ceil(c.outerWidth(!0) / (this.columnWidth + this.options.gutterWidth)), d = Math.min(d, this.cols);
            if (d === 1) f = this.colYs;
            else {
                e = this.cols + 1 - d, f = [];
                for (h = 0; h < e; h++) g = this.colYs.slice(h, h + d), f[h] = Math.max.apply(Math, g)
            }
            var i = Math.min.apply(Math, f),
                j = 0;
            for (var k = 0, l = f.length; k < l; k++)
                if (f[k] === i) {
                    j = k;
                    break
                }
            var m = {
                top: i + this.offset.y
            };
            m[this.horizontalDirection] = this.columnWidth * j + this.offset.x, this.styleQueue.push({
                $el: c,
                style: m
            });
            var n = i + c.outerHeight(!0),
                o = this.cols + 1 - l;
            for (k = 0; k < o; k++) this.colYs[j + k] = n
        },
        resize: function() {
            var a = this.cols;
            this._getColumns(), (this.isFluid || this.cols !== a) && this._reLayout()
        },
        _reLayout: function(a) {
            var b = this.cols;
            this.colYs = [];
            while (b--) this.colYs.push(0);
            this.layout(this.$bricks, a)
        },
        reloadItems: function() {
            this.$bricks = this._getBricks(this.element.children())
        },
        reload: function(a) {
            this.reloadItems(), this._init(a)
        },
        appended: function(a, b, c) {
            if (b) {
                this._filterFindBricks(a).css({
                    top: this.element.height()
                });
                var d = this;
                setTimeout(function() {
                    d._appended(a, c)
                }, 1)
            } else this._appended(a, c)
        },
        _appended: function(a, b) {
            var c = this._getBricks(a);
            this.$bricks = this.$bricks.add(c), this.layout(c, b)
        },
        remove: function(a) {
            this.$bricks = this.$bricks.not(a), a.remove()
        },
        destroy: function() {
            this.$bricks.removeClass("masonry-brick").each(function() {
                this.style.position = "", this.style.top = "", this.style.left = ""
            });
            var c = this.element[0].style;
            for (var d in this.originalStyle) c[d] = this.originalStyle[d];
            this.element.unbind(".masonry").removeClass("masonry").removeData("masonry"), b(a).unbind(".masonry")
        }
    }, b.fn.imagesLoaded = function(a) {
        function i(a) {
            var c = a.target;
            c.src !== f && b.inArray(c, g) === -1 && (g.push(c), --e <= 0 && (setTimeout(h), d.unbind(".imagesLoaded", i)))
        }

        function h() {
            a.call(c, d)
        }
        var c = this,
            d = c.find("img").add(c.filter("img")),
            e = d.length,
            f = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==",
            g = [];
        e || h(), d.bind("load.imagesLoaded error.imagesLoaded", i).each(function() {
            var a = this.src;
            this.src = f, this.src = a
        });
        return c
    };
    var f = function(b) {
        a.console && a.console.error(b)
    };
    b.fn.masonry = function(a) {
        if (typeof a == "string") {
            var c = Array.prototype.slice.call(arguments, 1);
            this.each(function() {
                var d = b.data(this, "masonry");
                if (!d) f("cannot call methods on masonry prior to initialization; attempted to call method '" + a + "'");
                else {
                    if (!b.isFunction(d[a]) || a.charAt(0) === "_") {
                        f("no such method '" + a + "' for masonry instance");
                        return
                    }
                    d[a].apply(d, c)
                }
            })
        } else this.each(function() {
            var c = b.data(this, "masonry");
            c ? (c.option(a || {}), c._init()) : b.data(this, "masonry", new b.Mason(a, this))
        });
        return this
    }
})(window, jQuery);
$j = jQuery.noConflict();
$j(document).ready(function() {
    $j('.hide-if-no-js').show();
    $j('div.menu li:has(ul.sub-menu) > a').addClass('with-ul').append('<span class="sub-indicator">&raquo;</span>');
    $j('#menu-primary-title').click(function() {
        $j('#menu-primary .menu').toggleClass('visible');
    });
    $j('#menu-secondary-title').click(function() {
        $j('#menu-secondary .menu').toggleClass('visible');
    });
    $j('#menu-subsidiary-title').click(function() {
        $j('#menu-subsidiary .menu').toggleClass('visible');
    });
    $j('#menu-header-primary-title').click(function() {
        $j('#menu-header-primary .menu').toggleClass('visible');
    });
    $j('#menu-header-secondary-title').click(function() {
        $j('#menu-header-secondary .menu').toggleClass('visible');
    });
    $j('#menu-header-horizontal-title').click(function() {
        $j('#menu-header-horizontal .menu').toggleClass("visible");
    });
    $j('.sidebar-wrap').masonry({
        itemSelector: '.widget'
    });
});


/**
 * Cookie plugin
 *
 * Copyright (c) 2006 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */

/**
 * Create a cookie with the given name and value and other optional parameters.
 *
 * @example $.cookie('the_cookie', 'the_value');
 * @desc Set the value of a cookie.
 * @example $.cookie('the_cookie', 'the_value', { expires: 7, path: '/', domain: 'jquery.com', secure: true });
 * @desc Create a cookie with all available options.
 * @example $.cookie('the_cookie', 'the_value');
 * @desc Create a session cookie.
 * @example $.cookie('the_cookie', null);
 * @desc Delete a cookie by passing null as value. Keep in mind that you have to use the same path and domain
 *       used when the cookie was set.
 *
 * @param String name The name of the cookie.
 * @param String value The value of the cookie.
 * @param Object options An object literal containing key/value pairs to provide optional cookie attributes.
 * @option Number|Date expires Either an integer specifying the expiration date from now on in days or a Date object.
 *                             If a negative value is specified (e.g. a date in the past), the cookie will be deleted.
 *                             If set to null or omitted, the cookie will be a session cookie and will not be retained
 *                             when the the browser exits.
 * @option String path The value of the path atribute of the cookie (default: path of page that created the cookie).
 * @option String domain The value of the domain attribute of the cookie (default: domain of page that created the cookie).
 * @option Boolean secure If true, the secure attribute of the cookie will be set and the cookie transmission will
 *                        require a secure protocol (like HTTPS).
 * @type undefined
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */

/**
 * Get the value of a cookie with the given name.
 *
 * @example $.cookie('the_cookie');
 * @desc Get the value of a cookie.
 *
 * @param String name The name of the cookie.
 * @return The value of the cookie.
 * @type String
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */
jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};



jQuery(window).resize(function() {
    // This will execute whenever the window is resized

    if (jQuery(window).width() >= 980) {
        jQuery.cookie('wsize', jQuery(window).width());
        var v = jQuery('#woo_shoppingcart_box').attr('id', 'woo_mob_cart');

        //var v = jQuery('#woo_shoppingcart_box').AddClass('shoppingcart_box');
        //var v = jQuery('#woo_shoppingcart_box').removeClass('shoppingcart_box');

        var v = jQuery('#woo_shopping_cart').attr('id', 'woo_mob_shopping_cart');

    } else {
        jQuery.cookie('wsize', jQuery(window).width());

        var v = jQuery('#woo_mob_cart').attr('id', 'woo_shoppingcart_box');
        var v = jQuery('#woo_mob_shopping_cart').attr('id', 'woo_shopping_cart');
    }

});
jQuery(document).ready(function() {
    if (jQuery(window).width() >= 980) {
        jQuery.cookie('wsize', jQuery(window).width());
        var v = jQuery('#woo_shoppingcart_box').attr('id', 'woo_mob_cart');
        var v = jQuery('#woo_shopping_cart').attr('id', 'woo_mob_shopping_cart');

    }
    //location.reload();

});
jQuery('[placeholder]').focus(function() {
    var input = jQuery(this);
    if (input.val() == input.attr('placeholder')) {
        input.val('');
        input.removeClass('placeholder');
    }
}).blur(function() {
    var input = jQuery(this);
    if (input.val() == '' || input.val() == input.attr('placeholder')) {
        input.addClass('placeholder');
    }
}).blur().parents('form').submit(function() {
    jQuery(this).find('[placeholder]').each(function() {
        var input = jQuery(this);
        if (input.val() == input.attr('placeholder')) {
            input.val('');
        }
    })
});

jQuery(".accordion").on("click", "dd", function () {
    jQuery("dd.active").find(".content").slideUp(400,"swing");
    if(!jQuery(this).hasClass("active")) {
      jQuery(this).find(".content").slideToggle(400,"swing");
    }
  });

function PlaceholderFormSubmit(e) {
    for (var t = 0; t < e.elements.length; t++) {
        var n = e.elements[t];
        HandlePlaceholderItemSubmit(n)
    }
}

function HandlePlaceholderItemSubmit(e) {
    if (e.name) {
        var t = e.getAttribute("placeholder");
        if (t && t.length > 0 && e.value === t) {
            e.value = "";
            window.setTimeout(function() {
                e.value = t
            }, 100)
        }
    }
}

function ReplaceWithText(e) {
    if (_placeholderSupport) return;
    var t = document.createElement("input");
    t.type = "password";
    t.id = e.id;
    t.name = e.name;
    t.className = e.className;
    for (var n = 0; n < e.attributes.length; n++) {
        var r = e.attributes.item(n).nodeName;
        var i = e.attributes.item(n).nodeValue;
        if (r !== "type" && r !== "name") {
            t.setAttribute(r, i)
        }
    }
    t.originalTextbox = e;
    e.parentNode.replaceChild(t, e);
    HandlePlaceholder(t);
    if (!_placeholderSupport) {
        e.onblur = function() {
            if (this.dummyTextbox && this.value.length === 0) {
                this.parentNode.replaceChild(this.dummyTextbox, this)
            }
        }
    }
}

function HandlePlaceholder(e) {
    if (!_placeholderSupport) {
        var t = e.getAttribute("placeholder");
        if (t && t.length > 0) {
            Debug("Placeholder found for input box '" + e.name + "': " + t);
            e.value = t;
            e.setAttribute("old_color", e.style.color);
            e.style.color = "#c0c0c0";
            e.onfocus = function() {
                var e = this;
                if (this.originalTextbox) {
                    e = this.originalTextbox;
                    e.dummyTextbox = this;
                    this.parentNode.replaceChild(this.originalTextbox, this);
                    e.focus()
                }
                Debug("input box '" + e.name + "' focus");
                e.style.color = e.getAttribute("old_color");
                if (e.value === t) e.value = ""
            };
            e.onblur = function() {
                var e = this;
                Debug("input box '" + e.name + "' blur");
                if (e.value === "") {
                    e.style.color = "#c0c0c0";
                    e.value = t
                }
            }
        } else {
            Debug("input box '" + e.name + "' does not have placeholder attribute")
        }
    } else {
        Debug("browser has native support for placeholder")
    }
}

function Debug(e) {
    if (typeof _debug !== "undefined" && _debug) {
        var t = document.getElementById("Console");
        if (!t) {
            t = document.createElement("div");
            t.id = "Console";
            document.body.appendChild(t)
        }
        t.innerHTML += e + "<br />"
    }
}
var _debug = false;
var _placeholderSupport = function() {
    var e = document.createElement("input");
    e.type = "text";
    return typeof e.placeholder !== "undefined"
}();
window.onload = function() {
    var e = document.getElementsByTagName("input");
    var t = document.getElementsByTagName("textarea");
    var n = [];
    for (var r = 0; r < e.length; r++) n.push(e[r]);
    for (var r = 0; r < t.length; r++) n.push(t[r]);
    for (var r = 0; r < n.length; r++) {
        var i = n[r];
        if (!i.type || i.type == "" || i.type == "text" || i.type == "textarea") HandlePlaceholder(i);
        else if (i.type == "password") ReplaceWithText(i)
    }
    if (!_placeholderSupport) {
        for (var r = 0; r < document.forms.length; r++) {
            var s = document.forms[r];
            if (s.attachEvent) {
                s.attachEvent("onsubmit", function() {
                    PlaceholderFormSubmit(s)
                })
            } else if (s.addEventListener) s.addEventListener("submit", function() {
                PlaceholderFormSubmit(s)
            }, false)
        }
    }
}

jQuery( document ).ready(function() {

	// header-search
	var has_header = jQuery('.large-8.columns .header-widget-wrap div').children().length;
	if(has_header>0) {
		jQuery( ".middle.tab-bar-section" ).after( "<section class='right-small'><a href='#' class='mobile-search'><i class='fa fa-search'></i></a></section>" );
		jQuery( "body" ).addClass('mobile-header');
	}
	// search
	jQuery('.mobile-search').click(function() {
		jQuery('.large-8.columns .header-widget-wrap').slideToggle( "medium");
		jQuery('#main').toggleClass('overlay-search');
	});
	jQuery('#main.overlay-search').click(function() {
		jQuery('.large-8.columns #sidebar-header').slideUp( "medium");
		jQuery('#main').removeClass('overlay-search');
	}); 
});

/*
 * jQuery FlexSlider v2.2.2
 * Copyright 2012 WooThemes
 * Contributing Author: Tyler Smith
 */
;
(function ($) {

  //FlexSlider: Object Instance
  $.flexslider = function(el, options) {
    var slider = $(el);

    // making variables public
    slider.vars = $.extend({}, $.flexslider.defaults, options);

    var namespace = slider.vars.namespace,
        msGesture = window.navigator && window.navigator.msPointerEnabled && window.MSGesture,
        touch = (( "ontouchstart" in window ) || msGesture || window.DocumentTouch && document instanceof DocumentTouch) && slider.vars.touch,
        // depricating this idea, as devices are being released with both of these events
        //eventType = (touch) ? "touchend" : "click",
        eventType = "click touchend MSPointerUp keyup",
        watchedEvent = "",
        watchedEventClearTimer,
        vertical = slider.vars.direction === "vertical",
        reverse = slider.vars.reverse,
        carousel = (slider.vars.itemWidth > 0),
        fade = slider.vars.animation === "fade",
        asNav = slider.vars.asNavFor !== "",
        methods = {},
        focused = true;

    // Store a reference to the slider object
    $.data(el, "flexslider", slider);

    // Private slider methods
    methods = {
      init: function() {
        slider.animating = false;
        // Get current slide and make sure it is a number
        slider.currentSlide = parseInt( ( slider.vars.startAt ? slider.vars.startAt : 0), 10 );
        if ( isNaN( slider.currentSlide ) ) slider.currentSlide = 0;
        slider.animatingTo = slider.currentSlide;
        slider.atEnd = (slider.currentSlide === 0 || slider.currentSlide === slider.last);
        slider.containerSelector = slider.vars.selector.substr(0,slider.vars.selector.search(' '));
        slider.slides = $(slider.vars.selector, slider);
        slider.container = $(slider.containerSelector, slider);
        slider.count = slider.slides.length;
        // SYNC:
        slider.syncExists = $(slider.vars.sync).length > 0;
        // SLIDE:
        if (slider.vars.animation === "slide") slider.vars.animation = "swing";
        slider.prop = (vertical) ? "top" : "marginLeft";
        slider.args = {};
        // SLIDESHOW:
        slider.manualPause = false;
        slider.stopped = false;
        //PAUSE WHEN INVISIBLE
        slider.started = false;
        slider.startTimeout = null;
        // TOUCH/USECSS:
        slider.transitions = !slider.vars.video && !fade && slider.vars.useCSS && (function() {
          var obj = document.createElement('div'),
              props = ['perspectiveProperty', 'WebkitPerspective', 'MozPerspective', 'OPerspective', 'msPerspective'];
          for (var i in props) {
            if ( obj.style[ props[i] ] !== undefined ) {
              slider.pfx = props[i].replace('Perspective','').toLowerCase();
              slider.prop = "-" + slider.pfx + "-transform";
              return true;
            }
          }
          return false;
        }());
        slider.ensureAnimationEnd = '';
        // CONTROLSCONTAINER:
        if (slider.vars.controlsContainer !== "") slider.controlsContainer = $(slider.vars.controlsContainer).length > 0 && $(slider.vars.controlsContainer);
        // MANUAL:
        if (slider.vars.manualControls !== "") slider.manualControls = $(slider.vars.manualControls).length > 0 && $(slider.vars.manualControls);

        // RANDOMIZE:
        if (slider.vars.randomize) {
          slider.slides.sort(function() { return (Math.round(Math.random())-0.5); });
          slider.container.empty().append(slider.slides);
        }

        slider.doMath();

        // INIT
        slider.setup("init");

        // CONTROLNAV:
        if (slider.vars.controlNav) methods.controlNav.setup();

        // DIRECTIONNAV:
        if (slider.vars.directionNav) methods.directionNav.setup();

        // KEYBOARD:
        if (slider.vars.keyboard && ($(slider.containerSelector).length === 1 || slider.vars.multipleKeyboard)) {
          $(document).bind('keyup', function(event) {
            var keycode = event.keyCode;
            if (!slider.animating && (keycode === 39 || keycode === 37)) {
              var target = (keycode === 39) ? slider.getTarget('next') :
                           (keycode === 37) ? slider.getTarget('prev') : false;
              slider.flexAnimate(target, slider.vars.pauseOnAction);
            }
          });
        }
        // MOUSEWHEEL:
        if (slider.vars.mousewheel) {
          slider.bind('mousewheel', function(event, delta, deltaX, deltaY) {
            event.preventDefault();
            var target = (delta < 0) ? slider.getTarget('next') : slider.getTarget('prev');
            slider.flexAnimate(target, slider.vars.pauseOnAction);
          });
        }

        // PAUSEPLAY
        if (slider.vars.pausePlay) methods.pausePlay.setup();

        //PAUSE WHEN INVISIBLE
        if (slider.vars.slideshow && slider.vars.pauseInvisible) methods.pauseInvisible.init();

        // SLIDSESHOW
        if (slider.vars.slideshow) {
          if (slider.vars.pauseOnHover) {
            slider.hover(function() {
              if (!slider.manualPlay && !slider.manualPause) slider.pause();
            }, function() {
              if (!slider.manualPause && !slider.manualPlay && !slider.stopped) slider.play();
            });
          }
          // initialize animation
          //If we're visible, or we don't use PageVisibility API
          if(!slider.vars.pauseInvisible || !methods.pauseInvisible.isHidden()) {
            (slider.vars.initDelay > 0) ? slider.startTimeout = setTimeout(slider.play, slider.vars.initDelay) : slider.play();
          }
        }

        // ASNAV:
        if (asNav) methods.asNav.setup();

        // TOUCH
        if (touch && slider.vars.touch) methods.touch();

        // FADE&&SMOOTHHEIGHT || SLIDE:
        if (!fade || (fade && slider.vars.smoothHeight)) $(window).bind("resize orientationchange focus", methods.resize);

        slider.find("img").attr("draggable", "false");

        // API: start() Callback
        setTimeout(function(){
          slider.vars.start(slider);
        }, 200);
      },
      asNav: {
        setup: function() {
          slider.asNav = true;
          slider.animatingTo = Math.floor(slider.currentSlide/slider.move);
          slider.currentItem = slider.currentSlide;
          slider.slides.removeClass(namespace + "active-slide").eq(slider.currentItem).addClass(namespace + "active-slide");
          if(!msGesture){
              slider.slides.on(eventType, function(e){
                e.preventDefault();
                var $slide = $(this),
                    target = $slide.index();
                var posFromLeft = $slide.offset().left - $(slider).scrollLeft(); // Find position of slide relative to left of slider container
                if( posFromLeft <= 0 && $slide.hasClass( namespace + 'active-slide' ) ) {
                  slider.flexAnimate(slider.getTarget("prev"), true);
                } else if (!$(slider.vars.asNavFor).data('flexslider').animating && !$slide.hasClass(namespace + "active-slide")) {
                  slider.direction = (slider.currentItem < target) ? "next" : "prev";
                  slider.flexAnimate(target, slider.vars.pauseOnAction, false, true, true);
                }
              });
          }else{
              el._slider = slider;
              slider.slides.each(function (){
                  var that = this;
                  that._gesture = new MSGesture();
                  that._gesture.target = that;
                  that.addEventListener("MSPointerDown", function (e){
                      e.preventDefault();
                      if(e.currentTarget._gesture)
                          e.currentTarget._gesture.addPointer(e.pointerId);
                  }, false);
                  that.addEventListener("MSGestureTap", function (e){
                      e.preventDefault();
                      var $slide = $(this),
                          target = $slide.index();
                      if (!$(slider.vars.asNavFor).data('flexslider').animating && !$slide.hasClass('active')) {
                          slider.direction = (slider.currentItem < target) ? "next" : "prev";
                          slider.flexAnimate(target, slider.vars.pauseOnAction, false, true, true);
                      }
                  });
              });
          }
        }
      },
      controlNav: {
        setup: function() {
          if (!slider.manualControls) {
            methods.controlNav.setupPaging();
          } else { // MANUALCONTROLS:
            methods.controlNav.setupManual();
          }
        },
        setupPaging: function() {
          var type = (slider.vars.controlNav === "thumbnails") ? 'control-thumbs' : 'control-paging',
              j = 1,
              item,
              slide;

          slider.controlNavScaffold = $('<ol class="'+ namespace + 'control-nav ' + namespace + type + '"></ol>');

          if (slider.pagingCount > 1) {
            for (var i = 0; i < slider.pagingCount; i++) {
              slide = slider.slides.eq(i);
              item = (slider.vars.controlNav === "thumbnails") ? '<img src="' + slide.attr( 'data-thumb' ) + '"/>' : '<a>' + j + '</a>';
              if ( 'thumbnails' === slider.vars.controlNav && true === slider.vars.thumbCaptions ) {
                var captn = slide.attr( 'data-thumbcaption' );
                if ( '' != captn && undefined != captn ) item += '<span class="' + namespace + 'caption">' + captn + '</span>';
              }
              slider.controlNavScaffold.append('<li>' + item + '</li>');
              j++;
            }
          }

          // CONTROLSCONTAINER:
          (slider.controlsContainer) ? $(slider.controlsContainer).append(slider.controlNavScaffold) : slider.append(slider.controlNavScaffold);
          methods.controlNav.set();

          methods.controlNav.active();

          slider.controlNavScaffold.delegate('a, img', eventType, function(event) {
            event.preventDefault();

            if (watchedEvent === "" || watchedEvent === event.type) {
              var $this = $(this),
                  target = slider.controlNav.index($this);

              if (!$this.hasClass(namespace + 'active')) {
                slider.direction = (target > slider.currentSlide) ? "next" : "prev";
                slider.flexAnimate(target, slider.vars.pauseOnAction);
              }
            }

            // setup flags to prevent event duplication
            if (watchedEvent === "") {
              watchedEvent = event.type;
            }
            methods.setToClearWatchedEvent();

          });
        },
        setupManual: function() {
          slider.controlNav = slider.manualControls;
          methods.controlNav.active();

          slider.controlNav.bind(eventType, function(event) {
            event.preventDefault();

            if (watchedEvent === "" || watchedEvent === event.type) {
              var $this = $(this),
                  target = slider.controlNav.index($this);

              if (!$this.hasClass(namespace + 'active')) {
                (target > slider.currentSlide) ? slider.direction = "next" : slider.direction = "prev";
                slider.flexAnimate(target, slider.vars.pauseOnAction);
              }
            }

            // setup flags to prevent event duplication
            if (watchedEvent === "") {
              watchedEvent = event.type;
            }
            methods.setToClearWatchedEvent();
          });
        },
        set: function() {
          var selector = (slider.vars.controlNav === "thumbnails") ? 'img' : 'a';
          slider.controlNav = $('.' + namespace + 'control-nav li ' + selector, (slider.controlsContainer) ? slider.controlsContainer : slider);
        },
        active: function() {
          slider.controlNav.removeClass(namespace + "active").eq(slider.animatingTo).addClass(namespace + "active");
        },
        update: function(action, pos) {
          if (slider.pagingCount > 1 && action === "add") {
            slider.controlNavScaffold.append($('<li><a>' + slider.count + '</a></li>'));
          } else if (slider.pagingCount === 1) {
            slider.controlNavScaffold.find('li').remove();
          } else {
            slider.controlNav.eq(pos).closest('li').remove();
          }
          methods.controlNav.set();
          (slider.pagingCount > 1 && slider.pagingCount !== slider.controlNav.length) ? slider.update(pos, action) : methods.controlNav.active();
        }
      },
      directionNav: {
        setup: function() {
          var directionNavScaffold = $('<ul class="' + namespace + 'direction-nav"><li><a class="' + namespace + 'prev" href="#">' + slider.vars.prevText + '</a></li><li><a class="' + namespace + 'next" href="#">' + slider.vars.nextText + '</a></li></ul>');

          // CONTROLSCONTAINER:
          if (slider.controlsContainer) {
            $(slider.controlsContainer).append(directionNavScaffold);
            slider.directionNav = $('.' + namespace + 'direction-nav li a', slider.controlsContainer);
          } else {
            slider.append(directionNavScaffold);
            slider.directionNav = $('.' + namespace + 'direction-nav li a', slider);
          }

          methods.directionNav.update();

          slider.directionNav.bind(eventType, function(event) {
            event.preventDefault();
            var target;

            if (watchedEvent === "" || watchedEvent === event.type) {
              target = ($(this).hasClass(namespace + 'next')) ? slider.getTarget('next') : slider.getTarget('prev');
              slider.flexAnimate(target, slider.vars.pauseOnAction);
            }

            // setup flags to prevent event duplication
            if (watchedEvent === "") {
              watchedEvent = event.type;
            }
            methods.setToClearWatchedEvent();
          });
        },
        update: function() {
          var disabledClass = namespace + 'disabled';
          if (slider.pagingCount === 1) {
            slider.directionNav.addClass(disabledClass).attr('tabindex', '-1');
          } else if (!slider.vars.animationLoop) {
            if (slider.animatingTo === 0) {
              slider.directionNav.removeClass(disabledClass).filter('.' + namespace + "prev").addClass(disabledClass).attr('tabindex', '-1');
            } else if (slider.animatingTo === slider.last) {
              slider.directionNav.removeClass(disabledClass).filter('.' + namespace + "next").addClass(disabledClass).attr('tabindex', '-1');
            } else {
              slider.directionNav.removeClass(disabledClass).removeAttr('tabindex');
            }
          } else {
            slider.directionNav.removeClass(disabledClass).removeAttr('tabindex');
          }
        }
      },
      pausePlay: {
        setup: function() {
          var pausePlayScaffold = $('<div class="' + namespace + 'pauseplay"><a></a></div>');

          // CONTROLSCONTAINER:
          if (slider.controlsContainer) {
            slider.controlsContainer.append(pausePlayScaffold);
            slider.pausePlay = $('.' + namespace + 'pauseplay a', slider.controlsContainer);
          } else {
            slider.append(pausePlayScaffold);
            slider.pausePlay = $('.' + namespace + 'pauseplay a', slider);
          }

          methods.pausePlay.update((slider.vars.slideshow) ? namespace + 'pause' : namespace + 'play');

          slider.pausePlay.bind(eventType, function(event) {
            event.preventDefault();

            if (watchedEvent === "" || watchedEvent === event.type) {
              if ($(this).hasClass(namespace + 'pause')) {
                slider.manualPause = true;
                slider.manualPlay = false;
                slider.pause();
              } else {
                slider.manualPause = false;
                slider.manualPlay = true;
                slider.play();
              }
            }

            // setup flags to prevent event duplication
            if (watchedEvent === "") {
              watchedEvent = event.type;
            }
            methods.setToClearWatchedEvent();
          });
        },
        update: function(state) {
          (state === "play") ? slider.pausePlay.removeClass(namespace + 'pause').addClass(namespace + 'play').html(slider.vars.playText) : slider.pausePlay.removeClass(namespace + 'play').addClass(namespace + 'pause').html(slider.vars.pauseText);
        }
      },
      touch: function() {
        var startX,
          startY,
          offset,
          cwidth,
          dx,
          startT,
          scrolling = false,
          localX = 0,
          localY = 0,
          accDx = 0;

        if(!msGesture){
            el.addEventListener('touchstart', onTouchStart, false);

            function onTouchStart(e) {
              if (slider.animating) {
                e.preventDefault();
              } else if ( ( window.navigator.msPointerEnabled ) || e.touches.length === 1 ) {
                slider.pause();
                // CAROUSEL:
                cwidth = (vertical) ? slider.h : slider. w;
                startT = Number(new Date());
                // CAROUSEL:

                // Local vars for X and Y points.
                localX = e.touches[0].pageX;
                localY = e.touches[0].pageY;

                offset = (carousel && reverse && slider.animatingTo === slider.last) ? 0 :
                         (carousel && reverse) ? slider.limit - (((slider.itemW + slider.vars.itemMargin) * slider.move) * slider.animatingTo) :
                         (carousel && slider.currentSlide === slider.last) ? slider.limit :
                         (carousel) ? ((slider.itemW + slider.vars.itemMargin) * slider.move) * slider.currentSlide :
                         (reverse) ? (slider.last - slider.currentSlide + slider.cloneOffset) * cwidth : (slider.currentSlide + slider.cloneOffset) * cwidth;
                startX = (vertical) ? localY : localX;
                startY = (vertical) ? localX : localY;

                el.addEventListener('touchmove', onTouchMove, false);
                el.addEventListener('touchend', onTouchEnd, false);
              }
            }

            function onTouchMove(e) {
              // Local vars for X and Y points.

              localX = e.touches[0].pageX;
              localY = e.touches[0].pageY;

              dx = (vertical) ? startX - localY : startX - localX;
              scrolling = (vertical) ? (Math.abs(dx) < Math.abs(localX - startY)) : (Math.abs(dx) < Math.abs(localY - startY));

              var fxms = 500;

              if ( ! scrolling || Number( new Date() ) - startT > fxms ) {
                e.preventDefault();
                if (!fade && slider.transitions) {
                  if (!slider.vars.animationLoop) {
                    dx = dx/((slider.currentSlide === 0 && dx < 0 || slider.currentSlide === slider.last && dx > 0) ? (Math.abs(dx)/cwidth+2) : 1);
                  }
                  slider.setProps(offset + dx, "setTouch");
                }
              }
            }

            function onTouchEnd(e) {
              // finish the touch by undoing the touch session
              el.removeEventListener('touchmove', onTouchMove, false);

              if (slider.animatingTo === slider.currentSlide && !scrolling && !(dx === null)) {
                var updateDx = (reverse) ? -dx : dx,
                    target = (updateDx > 0) ? slider.getTarget('next') : slider.getTarget('prev');

                if (slider.canAdvance(target) && (Number(new Date()) - startT < 550 && Math.abs(updateDx) > 50 || Math.abs(updateDx) > cwidth/2)) {
                  slider.flexAnimate(target, slider.vars.pauseOnAction);
                } else {
                  if (!fade) slider.flexAnimate(slider.currentSlide, slider.vars.pauseOnAction, true);
                }
              }
              el.removeEventListener('touchend', onTouchEnd, false);

              startX = null;
              startY = null;
              dx = null;
              offset = null;
            }
        }else{
            el.style.msTouchAction = "none";
            el._gesture = new MSGesture();
            el._gesture.target = el;
            el.addEventListener("MSPointerDown", onMSPointerDown, false);
            el._slider = slider;
            el.addEventListener("MSGestureChange", onMSGestureChange, false);
            el.addEventListener("MSGestureEnd", onMSGestureEnd, false);

            function onMSPointerDown(e){
                e.stopPropagation();
                if (slider.animating) {
                    e.preventDefault();
                }else{
                    slider.pause();
                    el._gesture.addPointer(e.pointerId);
                    accDx = 0;
                    cwidth = (vertical) ? slider.h : slider. w;
                    startT = Number(new Date());
                    // CAROUSEL:

                    offset = (carousel && reverse && slider.animatingTo === slider.last) ? 0 :
                        (carousel && reverse) ? slider.limit - (((slider.itemW + slider.vars.itemMargin) * slider.move) * slider.animatingTo) :
                            (carousel && slider.currentSlide === slider.last) ? slider.limit :
                                (carousel) ? ((slider.itemW + slider.vars.itemMargin) * slider.move) * slider.currentSlide :
                                    (reverse) ? (slider.last - slider.currentSlide + slider.cloneOffset) * cwidth : (slider.currentSlide + slider.cloneOffset) * cwidth;
                }
            }

            function onMSGestureChange(e) {
                e.stopPropagation();
                var slider = e.target._slider;
                if(!slider){
                    return;
                }
                var transX = -e.translationX,
                    transY = -e.translationY;

                //Accumulate translations.
                accDx = accDx + ((vertical) ? transY : transX);
                dx = accDx;
                scrolling = (vertical) ? (Math.abs(accDx) < Math.abs(-transX)) : (Math.abs(accDx) < Math.abs(-transY));

                if(e.detail === e.MSGESTURE_FLAG_INERTIA){
                    setImmediate(function (){
                        el._gesture.stop();
                    });

                    return;
                }

                if (!scrolling || Number(new Date()) - startT > 500) {
                    e.preventDefault();
                    if (!fade && slider.transitions) {
                        if (!slider.vars.animationLoop) {
                            dx = accDx / ((slider.currentSlide === 0 && accDx < 0 || slider.currentSlide === slider.last && accDx > 0) ? (Math.abs(accDx) / cwidth + 2) : 1);
                        }
                        slider.setProps(offset + dx, "setTouch");
                    }
                }
            }

            function onMSGestureEnd(e) {
                e.stopPropagation();
                var slider = e.target._slider;
                if(!slider){
                    return;
                }
                if (slider.animatingTo === slider.currentSlide && !scrolling && !(dx === null)) {
                    var updateDx = (reverse) ? -dx : dx,
                        target = (updateDx > 0) ? slider.getTarget('next') : slider.getTarget('prev');

                    if (slider.canAdvance(target) && (Number(new Date()) - startT < 550 && Math.abs(updateDx) > 50 || Math.abs(updateDx) > cwidth/2)) {
                        slider.flexAnimate(target, slider.vars.pauseOnAction);
                    } else {
                        if (!fade) slider.flexAnimate(slider.currentSlide, slider.vars.pauseOnAction, true);
                    }
                }

                startX = null;
                startY = null;
                dx = null;
                offset = null;
                accDx = 0;
            }
        }
      },
      resize: function() {
        if (!slider.animating && slider.is(':visible')) {
          if (!carousel) slider.doMath();

          if (fade) {
            // SMOOTH HEIGHT:
            methods.smoothHeight();
          } else if (carousel) { //CAROUSEL:
            slider.slides.width(slider.computedW);
            slider.update(slider.pagingCount);
            slider.setProps();
          }
          else if (vertical) { //VERTICAL:
            slider.viewport.height(slider.h);
            slider.setProps(slider.h, "setTotal");
          } else {
            // SMOOTH HEIGHT:
            if (slider.vars.smoothHeight) methods.smoothHeight();
            slider.newSlides.width(slider.computedW);
            slider.setProps(slider.computedW, "setTotal");
          }
        }
      },
      smoothHeight: function(dur) {
        if (!vertical || fade) {
          var $obj = (fade) ? slider : slider.viewport;
          (dur) ? $obj.animate({"height": slider.slides.eq(slider.animatingTo).height()}, dur) : $obj.height(slider.slides.eq(slider.animatingTo).height());
        }
      },
      sync: function(action) {
        var $obj = $(slider.vars.sync).data("flexslider"),
            target = slider.animatingTo;

        switch (action) {
          case "animate": $obj.flexAnimate(target, slider.vars.pauseOnAction, false, true); break;
          case "play": if (!$obj.playing && !$obj.asNav) { $obj.play(); } break;
          case "pause": $obj.pause(); break;
        }
      },
      uniqueID: function($clone) {
        // Append _clone to current level and children elements with id attributes
        $clone.filter( '[id]' ).add($clone.find( '[id]' )).each(function() {
          var $this = $(this);
          $this.attr( 'id', $this.attr( 'id' ) + '_clone' );
        });
        return $clone;
      },
      pauseInvisible: {
        visProp: null,
        init: function() {
          var prefixes = ['webkit','moz','ms','o'];

          if ('hidden' in document) return 'hidden';
          for (var i = 0; i < prefixes.length; i++) {
            if ((prefixes[i] + 'Hidden') in document)
            methods.pauseInvisible.visProp = prefixes[i] + 'Hidden';
          }
          if (methods.pauseInvisible.visProp) {
            var evtname = methods.pauseInvisible.visProp.replace(/[H|h]idden/,'') + 'visibilitychange';
            document.addEventListener(evtname, function() {
              if (methods.pauseInvisible.isHidden()) {
                if(slider.startTimeout) clearTimeout(slider.startTimeout); //If clock is ticking, stop timer and prevent from starting while invisible
                else slider.pause(); //Or just pause
              }
              else {
                if(slider.started) slider.play(); //Initiated before, just play
                else (slider.vars.initDelay > 0) ? setTimeout(slider.play, slider.vars.initDelay) : slider.play(); //Didn't init before: simply init or wait for it
              }
            });
          }
        },
        isHidden: function() {
          return document[methods.pauseInvisible.visProp] || false;
        }
      },
      setToClearWatchedEvent: function() {
        clearTimeout(watchedEventClearTimer);
        watchedEventClearTimer = setTimeout(function() {
          watchedEvent = "";
        }, 3000);
      }
    };

    // public methods
    slider.flexAnimate = function(target, pause, override, withSync, fromNav) {
      if (!slider.vars.animationLoop && target !== slider.currentSlide) {
        slider.direction = (target > slider.currentSlide) ? "next" : "prev";
      }

      if (asNav && slider.pagingCount === 1) slider.direction = (slider.currentItem < target) ? "next" : "prev";

      if (!slider.animating && (slider.canAdvance(target, fromNav) || override) && slider.is(":visible")) {
        if (asNav && withSync) {
          var master = $(slider.vars.asNavFor).data('flexslider');
          slider.atEnd = target === 0 || target === slider.count - 1;
          master.flexAnimate(target, true, false, true, fromNav);
          slider.direction = (slider.currentItem < target) ? "next" : "prev";
          master.direction = slider.direction;

          if (Math.ceil((target + 1)/slider.visible) - 1 !== slider.currentSlide && target !== 0) {
            slider.currentItem = target;
            slider.slides.removeClass(namespace + "active-slide").eq(target).addClass(namespace + "active-slide");
            target = Math.floor(target/slider.visible);
          } else {
            slider.currentItem = target;
            slider.slides.removeClass(namespace + "active-slide").eq(target).addClass(namespace + "active-slide");
            return false;
          }
        }

        slider.animating = true;
        slider.animatingTo = target;

        // SLIDESHOW:
        if (pause) slider.pause();

        // API: before() animation Callback
        slider.vars.before(slider);

        // SYNC:
        if (slider.syncExists && !fromNav) methods.sync("animate");

        // CONTROLNAV
        if (slider.vars.controlNav) methods.controlNav.active();

        // !CAROUSEL:
        // CANDIDATE: slide active class (for add/remove slide)
        if (!carousel) slider.slides.removeClass(namespace + 'active-slide').eq(target).addClass(namespace + 'active-slide');

        // INFINITE LOOP:
        // CANDIDATE: atEnd
        slider.atEnd = target === 0 || target === slider.last;

        // DIRECTIONNAV:
        if (slider.vars.directionNav) methods.directionNav.update();

        if (target === slider.last) {
          // API: end() of cycle Callback
          slider.vars.end(slider);
          // SLIDESHOW && !INFINITE LOOP:
          if (!slider.vars.animationLoop) slider.pause();
        }

        // SLIDE:
        if (!fade) {
          var dimension = (vertical) ? slider.slides.filter(':first').height() : slider.computedW,
              margin, slideString, calcNext;

          // INFINITE LOOP / REVERSE:
          if (carousel) {
            //margin = (slider.vars.itemWidth > slider.w) ? slider.vars.itemMargin * 2 : slider.vars.itemMargin;
            margin = slider.vars.itemMargin;
            calcNext = ((slider.itemW + margin) * slider.move) * slider.animatingTo;
            slideString = (calcNext > slider.limit && slider.visible !== 1) ? slider.limit : calcNext;
          } else if (slider.currentSlide === 0 && target === slider.count - 1 && slider.vars.animationLoop && slider.direction !== "next") {
            slideString = (reverse) ? (slider.count + slider.cloneOffset) * dimension : 0;
          } else if (slider.currentSlide === slider.last && target === 0 && slider.vars.animationLoop && slider.direction !== "prev") {
            slideString = (reverse) ? 0 : (slider.count + 1) * dimension;
          } else {
            slideString = (reverse) ? ((slider.count - 1) - target + slider.cloneOffset) * dimension : (target + slider.cloneOffset) * dimension;
          }
          slider.setProps(slideString, "", slider.vars.animationSpeed);
          if (slider.transitions) {
            if (!slider.vars.animationLoop || !slider.atEnd) {
              slider.animating = false;
              slider.currentSlide = slider.animatingTo;
            }
            
            // Unbind previous transitionEnd events and re-bind new transitionEnd event
            slider.container.unbind("webkitTransitionEnd transitionend");
            slider.container.bind("webkitTransitionEnd transitionend", function() {
              clearTimeout(slider.ensureAnimationEnd);
              slider.wrapup(dimension);
            });

            // Insurance for the ever-so-fickle transitionEnd event
            clearTimeout(slider.ensureAnimationEnd);
            slider.ensureAnimationEnd = setTimeout(function() {
              slider.wrapup(dimension);
            }, slider.vars.animationSpeed + 100);

          } else {
            slider.container.animate(slider.args, slider.vars.animationSpeed, slider.vars.easing, function(){
              slider.wrapup(dimension);
            });
          }
        } else { // FADE:
          if (!touch) {
            //slider.slides.eq(slider.currentSlide).fadeOut(slider.vars.animationSpeed, slider.vars.easing);
            //slider.slides.eq(target).fadeIn(slider.vars.animationSpeed, slider.vars.easing, slider.wrapup);

            slider.slides.eq(slider.currentSlide).css({"zIndex": 1}).animate({"opacity": 0}, slider.vars.animationSpeed, slider.vars.easing);
            slider.slides.eq(target).css({"zIndex": 2}).animate({"opacity": 1}, slider.vars.animationSpeed, slider.vars.easing, slider.wrapup);

          } else {
            slider.slides.eq(slider.currentSlide).css({ "opacity": 0, "zIndex": 1 });
            slider.slides.eq(target).css({ "opacity": 1, "zIndex": 2 });
            slider.wrapup(dimension);
          }
        }
        // SMOOTH HEIGHT:
        if (slider.vars.smoothHeight) methods.smoothHeight(slider.vars.animationSpeed);
      }
    };
    slider.wrapup = function(dimension) {
      // SLIDE:
      if (!fade && !carousel) {
        if (slider.currentSlide === 0 && slider.animatingTo === slider.last && slider.vars.animationLoop) {
          slider.setProps(dimension, "jumpEnd");
        } else if (slider.currentSlide === slider.last && slider.animatingTo === 0 && slider.vars.animationLoop) {
          slider.setProps(dimension, "jumpStart");
        }
      }
      slider.animating = false;
      slider.currentSlide = slider.animatingTo;
      // API: after() animation Callback
      slider.vars.after(slider);
    };

    // SLIDESHOW:
    slider.animateSlides = function() {
      if (!slider.animating && focused ) slider.flexAnimate(slider.getTarget("next"));
    };
    // SLIDESHOW:
    slider.pause = function() {
      clearInterval(slider.animatedSlides);
      slider.animatedSlides = null;
      slider.playing = false;
      // PAUSEPLAY:
      if (slider.vars.pausePlay) methods.pausePlay.update("play");
      // SYNC:
      if (slider.syncExists) methods.sync("pause");
    };
    // SLIDESHOW:
    slider.play = function() {
      if (slider.playing) clearInterval(slider.animatedSlides);
      slider.animatedSlides = slider.animatedSlides || setInterval(slider.animateSlides, slider.vars.slideshowSpeed);
      slider.started = slider.playing = true;
      // PAUSEPLAY:
      if (slider.vars.pausePlay) methods.pausePlay.update("pause");
      // SYNC:
      if (slider.syncExists) methods.sync("play");
    };
    // STOP:
    slider.stop = function () {
      slider.pause();
      slider.stopped = true;
    };
    slider.canAdvance = function(target, fromNav) {
      // ASNAV:
      var last = (asNav) ? slider.pagingCount - 1 : slider.last;
      return (fromNav) ? true :
             (asNav && slider.currentItem === slider.count - 1 && target === 0 && slider.direction === "prev") ? true :
             (asNav && slider.currentItem === 0 && target === slider.pagingCount - 1 && slider.direction !== "next") ? false :
             (target === slider.currentSlide && !asNav) ? false :
             (slider.vars.animationLoop) ? true :
             (slider.atEnd && slider.currentSlide === 0 && target === last && slider.direction !== "next") ? false :
             (slider.atEnd && slider.currentSlide === last && target === 0 && slider.direction === "next") ? false :
             true;
    };
    slider.getTarget = function(dir) {
      slider.direction = dir;
      if (dir === "next") {
        return (slider.currentSlide === slider.last) ? 0 : slider.currentSlide + 1;
      } else {
        return (slider.currentSlide === 0) ? slider.last : slider.currentSlide - 1;
      }
    };

    // SLIDE:
    slider.setProps = function(pos, special, dur) {
      var target = (function() {
        var posCheck = (pos) ? pos : ((slider.itemW + slider.vars.itemMargin) * slider.move) * slider.animatingTo,
            posCalc = (function() {
              if (carousel) {
                return (special === "setTouch") ? pos :
                       (reverse && slider.animatingTo === slider.last) ? 0 :
                       (reverse) ? slider.limit - (((slider.itemW + slider.vars.itemMargin) * slider.move) * slider.animatingTo) :
                       (slider.animatingTo === slider.last) ? slider.limit : posCheck;
              } else {
                switch (special) {
                  case "setTotal": return (reverse) ? ((slider.count - 1) - slider.currentSlide + slider.cloneOffset) * pos : (slider.currentSlide + slider.cloneOffset) * pos;
                  case "setTouch": return (reverse) ? pos : pos;
                  case "jumpEnd": return (reverse) ? pos : slider.count * pos;
                  case "jumpStart": return (reverse) ? slider.count * pos : pos;
                  default: return pos;
                }
              }
            }());

            return (posCalc * -1) + "px";
          }());

      if (slider.transitions) {
        target = (vertical) ? "translate3d(0," + target + ",0)" : "translate3d(" + target + ",0,0)";
        dur = (dur !== undefined) ? (dur/1000) + "s" : "0s";
        slider.container.css("-" + slider.pfx + "-transition-duration", dur);
         slider.container.css("transition-duration", dur);
      }

      slider.args[slider.prop] = target;
      if (slider.transitions || dur === undefined) slider.container.css(slider.args);

      slider.container.css('transform',target);
    };

    slider.setup = function(type) {
      // SLIDE:
      if (!fade) {
        var sliderOffset, arr;

        if (type === "init") {
          slider.viewport = $('<div class="' + namespace + 'viewport"></div>').css({"overflow": "hidden", "position": "relative"}).appendTo(slider).append(slider.container);
          // INFINITE LOOP:
          slider.cloneCount = 0;
          slider.cloneOffset = 0;
          // REVERSE:
          if (reverse) {
            arr = $.makeArray(slider.slides).reverse();
            slider.slides = $(arr);
            slider.container.empty().append(slider.slides);
          }
        }
        // INFINITE LOOP && !CAROUSEL:
        if (slider.vars.animationLoop && !carousel) {
          slider.cloneCount = 2;
          slider.cloneOffset = 1;
          // clear out old clones
          if (type !== "init") slider.container.find('.clone').remove();
          slider.container.append(methods.uniqueID(slider.slides.first().clone().addClass('clone')).attr('aria-hidden', 'true'))
                          .prepend(methods.uniqueID(slider.slides.last().clone().addClass('clone')).attr('aria-hidden', 'true'));
        }
        slider.newSlides = $(slider.vars.selector, slider);

        sliderOffset = (reverse) ? slider.count - 1 - slider.currentSlide + slider.cloneOffset : slider.currentSlide + slider.cloneOffset;
        // VERTICAL:
        if (vertical && !carousel) {
          slider.container.height((slider.count + slider.cloneCount) * 200 + "%").css("position", "absolute").width("100%");
          setTimeout(function(){
            slider.newSlides.css({"display": "block"});
            slider.doMath();
            slider.viewport.height(slider.h);
            slider.setProps(sliderOffset * slider.h, "init");
          }, (type === "init") ? 100 : 0);
        } else {
          slider.container.width((slider.count + slider.cloneCount) * 200 + "%");
          slider.setProps(sliderOffset * slider.computedW, "init");
          setTimeout(function(){
            slider.doMath();
            slider.newSlides.css({"width": slider.computedW, "float": "left", "display": "block"});
            // SMOOTH HEIGHT:
            if (slider.vars.smoothHeight) methods.smoothHeight();
          }, (type === "init") ? 100 : 0);
        }
      } else { // FADE:
        slider.slides.css({"width": "100%", "float": "left", "marginRight": "-100%", "position": "relative"});
        if (type === "init") {
          if (!touch) {
            //slider.slides.eq(slider.currentSlide).fadeIn(slider.vars.animationSpeed, slider.vars.easing);
            if (slider.vars.fadeFirstSlide == false) {
              slider.slides.css({ "opacity": 0, "display": "block", "zIndex": 1 }).eq(slider.currentSlide).css({"zIndex": 2}).css({"opacity": 1});
            } else {
              slider.slides.css({ "opacity": 0, "display": "block", "zIndex": 1 }).eq(slider.currentSlide).css({"zIndex": 2}).animate({"opacity": 1},slider.vars.animationSpeed,slider.vars.easing);
            }
          } else {
            slider.slides.css({ "opacity": 0, "display": "block", "webkitTransition": "opacity " + slider.vars.animationSpeed / 1000 + "s ease", "zIndex": 1 }).eq(slider.currentSlide).css({ "opacity": 1, "zIndex": 2});
          }
        }
        // SMOOTH HEIGHT:
        if (slider.vars.smoothHeight) methods.smoothHeight();
      }
      // !CAROUSEL:
      // CANDIDATE: active slide
      if (!carousel) slider.slides.removeClass(namespace + "active-slide").eq(slider.currentSlide).addClass(namespace + "active-slide");

      //FlexSlider: init() Callback
      slider.vars.init(slider);
    };

    slider.doMath = function() {
      var slide = slider.slides.first(),
          slideMargin = slider.vars.itemMargin,
          minItems = slider.vars.minItems,
          maxItems = slider.vars.maxItems;

      slider.w = (slider.viewport===undefined) ? slider.width() : slider.viewport.width();
      slider.h = slide.height();
      slider.boxPadding = slide.outerWidth() - slide.width();

      // CAROUSEL:
      if (carousel) {
        slider.itemT = slider.vars.itemWidth + slideMargin;
        slider.minW = (minItems) ? minItems * slider.itemT : slider.w;
        slider.maxW = (maxItems) ? (maxItems * slider.itemT) - slideMargin : slider.w;
        slider.itemW = (slider.minW > slider.w) ? (slider.w - (slideMargin * (minItems - 1)))/minItems :
                       (slider.maxW < slider.w) ? (slider.w - (slideMargin * (maxItems - 1)))/maxItems :
                       (slider.vars.itemWidth > slider.w) ? slider.w : slider.vars.itemWidth;

        slider.visible = Math.floor(slider.w/(slider.itemW));
        slider.move = (slider.vars.move > 0 && slider.vars.move < slider.visible ) ? slider.vars.move : slider.visible;
        slider.pagingCount = Math.ceil(((slider.count - slider.visible)/slider.move) + 1);
        slider.last =  slider.pagingCount - 1;
        slider.limit = (slider.pagingCount === 1) ? 0 :
                       (slider.vars.itemWidth > slider.w) ? (slider.itemW * (slider.count - 1)) + (slideMargin * (slider.count - 1)) : ((slider.itemW + slideMargin) * slider.count) - slider.w - slideMargin;
      } else {
        slider.itemW = slider.w;
        slider.pagingCount = slider.count;
        slider.last = slider.count - 1;
      }
      slider.computedW = slider.itemW - slider.boxPadding;
    };

    slider.update = function(pos, action) {
      slider.doMath();

      // update currentSlide and slider.animatingTo if necessary
      if (!carousel) {
        if (pos < slider.currentSlide) {
          slider.currentSlide += 1;
        } else if (pos <= slider.currentSlide && pos !== 0) {
          slider.currentSlide -= 1;
        }
        slider.animatingTo = slider.currentSlide;
      }

      // update controlNav
      if (slider.vars.controlNav && !slider.manualControls) {
        if ((action === "add" && !carousel) || slider.pagingCount > slider.controlNav.length) {
          methods.controlNav.update("add");
        } else if ((action === "remove" && !carousel) || slider.pagingCount < slider.controlNav.length) {
          if (carousel && slider.currentSlide > slider.last) {
            slider.currentSlide -= 1;
            slider.animatingTo -= 1;
          }
          methods.controlNav.update("remove", slider.last);
        }
      }
      // update directionNav
      if (slider.vars.directionNav) methods.directionNav.update();

    };

    slider.addSlide = function(obj, pos) {
      var $obj = $(obj);

      slider.count += 1;
      slider.last = slider.count - 1;

      // append new slide
      if (vertical && reverse) {
        (pos !== undefined) ? slider.slides.eq(slider.count - pos).after($obj) : slider.container.prepend($obj);
      } else {
        (pos !== undefined) ? slider.slides.eq(pos).before($obj) : slider.container.append($obj);
      }

      // update currentSlide, animatingTo, controlNav, and directionNav
      slider.update(pos, "add");

      // update slider.slides
      slider.slides = $(slider.vars.selector + ':not(.clone)', slider);
      // re-setup the slider to accomdate new slide
      slider.setup();

      //FlexSlider: added() Callback
      slider.vars.added(slider);
    };
    slider.removeSlide = function(obj) {
      var pos = (isNaN(obj)) ? slider.slides.index($(obj)) : obj;

      // update count
      slider.count -= 1;
      slider.last = slider.count - 1;

      // remove slide
      if (isNaN(obj)) {
        $(obj, slider.slides).remove();
      } else {
        (vertical && reverse) ? slider.slides.eq(slider.last).remove() : slider.slides.eq(obj).remove();
      }

      // update currentSlide, animatingTo, controlNav, and directionNav
      slider.doMath();
      slider.update(pos, "remove");

      // update slider.slides
      slider.slides = $(slider.vars.selector + ':not(.clone)', slider);
      // re-setup the slider to accomdate new slide
      slider.setup();

      // FlexSlider: removed() Callback
      slider.vars.removed(slider);
    };

    //FlexSlider: Initialize
    methods.init();
  };

  // Ensure the slider isn't focussed if the window loses focus.
  $( window ).blur( function ( e ) {
    focused = false;
  }).focus( function ( e ) {
    focused = true;
  });

  //FlexSlider: Default Settings
  $.flexslider.defaults = {
    namespace: "flex-",             //{NEW} String: Prefix string attached to the class of every element generated by the plugin
    selector: ".slides > li",       //{NEW} Selector: Must match a simple pattern. '{container} > {slide}' -- Ignore pattern at your own peril
    animation: "fade",              //String: Select your animation type, "fade" or "slide"
    easing: "swing",                //{NEW} String: Determines the easing method used in jQuery transitions. jQuery easing plugin is supported!
    direction: "horizontal",        //String: Select the sliding direction, "horizontal" or "vertical"
    reverse: false,                 //{NEW} Boolean: Reverse the animation direction
    animationLoop: true,            //Boolean: Should the animation loop? If false, directionNav will received "disable" classes at either end
    smoothHeight: false,            //{NEW} Boolean: Allow height of the slider to animate smoothly in horizontal mode
    startAt: 0,                     //Integer: The slide that the slider should start on. Array notation (0 = first slide)
    slideshow: true,                //Boolean: Animate slider automatically
    slideshowSpeed: 7000,           //Integer: Set the speed of the slideshow cycling, in milliseconds
    animationSpeed: 600,            //Integer: Set the speed of animations, in milliseconds
    initDelay: 0,                   //{NEW} Integer: Set an initialization delay, in milliseconds
    randomize: false,               //Boolean: Randomize slide order
    fadeFirstSlide: true,           //Boolean: Fade in the first slide when animation type is "fade"
    thumbCaptions: false,           //Boolean: Whether or not to put captions on thumbnails when using the "thumbnails" controlNav.

    // Usability features
    pauseOnAction: true,            //Boolean: Pause the slideshow when interacting with control elements, highly recommended.
    pauseOnHover: false,            //Boolean: Pause the slideshow when hovering over slider, then resume when no longer hovering
    pauseInvisible: true,   		//{NEW} Boolean: Pause the slideshow when tab is invisible, resume when visible. Provides better UX, lower CPU usage.
    useCSS: true,                   //{NEW} Boolean: Slider will use CSS3 transitions if available
    touch: true,                    //{NEW} Boolean: Allow touch swipe navigation of the slider on touch-enabled devices
    video: false,                   //{NEW} Boolean: If using video in the slider, will prevent CSS3 3D Transforms to avoid graphical glitches

    // Primary Controls
    controlNav: true,               //Boolean: Create navigation for paging control of each slide? Note: Leave true for manualControls usage
    directionNav: true,             //Boolean: Create navigation for previous/next navigation? (true/false)
    prevText: "Previous",           //String: Set the text for the "previous" directionNav item
    nextText: "Next",               //String: Set the text for the "next" directionNav item

    // Secondary Navigation
    keyboard: true,                 //Boolean: Allow slider navigating via keyboard left/right keys
    multipleKeyboard: false,        //{NEW} Boolean: Allow keyboard navigation to affect multiple sliders. Default behavior cuts out keyboard navigation with more than one slider present.
    mousewheel: false,              //{UPDATED} Boolean: Requires jquery.mousewheel.js (https://github.com/brandonaaron/jquery-mousewheel) - Allows slider navigating via mousewheel
    pausePlay: false,               //Boolean: Create pause/play dynamic element
    pauseText: "Pause",             //String: Set the text for the "pause" pausePlay item
    playText: "Play",               //String: Set the text for the "play" pausePlay item

    // Special properties
    controlsContainer: "",          //{UPDATED} jQuery Object/Selector: Declare which container the navigation elements should be appended too. Default container is the FlexSlider element. Example use would be $(".flexslider-container"). Property is ignored if given element is not found.
    manualControls: "",             //{UPDATED} jQuery Object/Selector: Declare custom control navigation. Examples would be $(".flex-control-nav li") or "#tabs-nav li img", etc. The number of elements in your controlNav should match the number of slides/tabs.
    sync: "",                       //{NEW} Selector: Mirror the actions performed on this slider with another slider. Use with care.
    asNavFor: "",                   //{NEW} Selector: Internal property exposed for turning the slider into a thumbnail navigation for another slider

    // Carousel Options
    itemWidth: 0,                   //{NEW} Integer: Box-model width of individual carousel items, including horizontal borders and padding.
    itemMargin: 0,                  //{NEW} Integer: Margin between carousel items.
    minItems: 1,                    //{NEW} Integer: Minimum number of carousel items that should be visible. Items will resize fluidly when below this.
    maxItems: 0,                    //{NEW} Integer: Maxmimum number of carousel items that should be visible. Items will resize fluidly when above this limit.
    move: 0,                        //{NEW} Integer: Number of carousel items that should move on animation. If 0, slider will move all visible items.
    allowOneSlide: true,           //{NEW} Boolean: Whether or not to allow a slider comprised of a single slide

    // Callback API
    start: function(){},            //Callback: function(slider) - Fires when the slider loads the first slide
    before: function(){},           //Callback: function(slider) - Fires asynchronously with each slider animation
    after: function(){},            //Callback: function(slider) - Fires after each slider animation completes
    end: function(){},              //Callback: function(slider) - Fires when the slider reaches the last slide (asynchronous)
    added: function(){},            //{NEW} Callback: function(slider) - Fires after a slide is added
    removed: function(){},           //{NEW} Callback: function(slider) - Fires after a slide is removed
    init: function() {}             //{NEW} Callback: function(slider) - Fires after the slider is initially setup
  };

  //FlexSlider: Plugin Function
  $.fn.flexslider = function(options) {
    if (options === undefined) options = {};

    if (typeof options === "object") {
      return this.each(function() {
        var $this = $(this),
            selector = (options.selector) ? options.selector : ".slides > li",
            $slides = $this.find(selector);

      if ( ( $slides.length === 1 && options.allowOneSlide === true ) || $slides.length === 0 ) {
          $slides.fadeIn(400);
          if (options.start) options.start($this);
        } else if ($this.data('flexslider') === undefined) {
          new $.flexslider(this, options);
        }
      });
    } else {
      // Helper strings to quickly perform functions on the slider
      var $slider = $(this).data('flexslider');
      switch (options) {
        case "play": $slider.play(); break;
        case "pause": $slider.pause(); break;
        case "stop": $slider.stop(); break;
        case "next": $slider.flexAnimate($slider.getTarget("next"), true); break;
        case "prev":
        case "previous": $slider.flexAnimate($slider.getTarget("prev"), true); break;
        default: if (typeof options === "number") $slider.flexAnimate(options, true);
      }
    }
  };
})(jQuery);
