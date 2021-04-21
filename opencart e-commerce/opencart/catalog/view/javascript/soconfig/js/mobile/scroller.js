var scroller = (function($) {
    "use strict";
    var _modOpts = {},
        _scrOpts = {},
        _barOpts = {},
		$window = $('.content'),
		$document = $('#content'),
        ispInstance, btn, prodCont, paginator, scrollPos, $scrollBar, $arrow, $eventBuffer, lastPage, init = function(opts) {
            _modOpts = opts.infScroll;
            _barOpts = opts.scrollTop;
            if (~~opts.infScroll.enabled) {
                _scrOptsMap(opts.infScroll);
                _scrOpts.behavior = 'ociscr';
                _scrOpts.loading.finished = _onLoadingFinished;
                if (_modOpts.loadingMode !== 'auto') {
                    _scrOpts.errorCallback = function() {
                        if (btn) btn.hide();
                    };
                }
                _create();
                if (~~opts.infScroll.statefulScroll) {
                    setTimeout(function() {
                        $document.on('scroll', _preservePosition);
                    }, 1);
                }
            }
            if (~~opts.scrollTop.enabled) {
                _createScrollTopBar();
            }
            if (~~opts.infScroll.offsetTop) {
                $window.scrollTop(~~opts.infScroll.offsetTop);
            }
        },
        _scrOptsMap = function(opts) {
            _scrOpts.navSelector = opts.paginatorSelector;
            _scrOpts.nextSelector = opts.paginatorSelector + ' a:first';
            _scrOpts.itemSelector = opts.itemSelector;
            _scrOpts.loading = _scrOpts.loading || {};
            _scrOpts.loading.msgText = opts.loadingMsg;
            _scrOpts.loading.finishedMsg = opts.finishMsg;
            _scrOpts.loading.speed = 0;
            _scrOpts.loading.img = 'catalog/view/theme_mobile/default/image/scroller-loader.gif';
            _scrOpts.state = _scrOpts.state || {};
            _scrOpts.state.currPage = opts.curPage;
            if ($('.journal-sf').size()) {
                _scrOpts.loading.start = _customLoading;
            }
            _scrOpts.bufferPx = ~~opts.minDistToBottom;
            _scrOpts.debug = opts.debugMode === '1';
            _scrOpts.animate = opts.animation === '1';
        },
        _create = function() {
            prodCont = $(_modOpts.containerSelector);
            paginator = $(_modOpts.paginatorSelector);
            _scrOpts.loading.msg = $('<i id="infscr-loading"><img alt="Loading..." src="' + _scrOpts.loading.img + '" /><i>' + _scrOpts.loading.msgText + '</i></i>');
            prodCont.infinitescroll(_scrOpts, _onAfterPageLoad);
            ispInstance = prodCont.data('infinitescroll');
            if (ispInstance) {
                ispInstance._nearbottom_ociscr = _nearbottom;
                ispInstance._showdonemsg_ociscr = _showdonemsg;
                if (_modOpts.loadingMode === 'button' || (_modOpts.loadingMode === 'smart' && _modOpts.show_btn_after <= ispInstance.options.state.currPage)) {
                    prodCont.infinitescroll('pause');
                    _addButton();
                }
            }
            paginator.hide();
            _observeDomChanges();
        },
        _destroy = function() {
            $('body').disconnect();
            if (ispInstance) {
                ispInstance.destroy();
                $(_modOpts.containerSelector).data('infinitescroll', null);
            }
            if (btn) {
                btn.remove();
            }
            if (paginator) {
                paginator.show();
            }
            $('.scroller-separator').remove();
        },
        _onAfterPageLoad = function(items, opts) {
            $('body').disconnect();
            var viewMode = 'grid';
            if (typeof $.totalStorage !== 'undefined' && $.totalStorage('display')) {
                viewMode = $.totalStorage('display');
            } else if (typeof $.cookie !== 'undefined' && $.cookie('display')) {
                viewMode = $.cookie('display');
            } else if (typeof localStorage !== "undefined" && localStorage.getItem("display")) {
                viewMode = localStorage.getItem("display");
            }
            if (typeof display === 'function') {
                display(viewMode);
            } else if (typeof Journal !== 'undefined' && typeof Journal.applyView !== 'undefined') {
                Journal.applyView(viewMode);
            } else if ($("#" + viewMode + "-view").size()) {
                $("#" + viewMode + "-view").trigger("click");
            } else if ($("." + viewMode + "-view").size()) {
                $("." + viewMode + "-view").trigger("click");
            }
            if (typeof $.fn.lazy !== 'undefined') {
                $(items).find(".lazy").lazy({
                    bind: 'event',
                    visibleOnly: false,
                    effect: "fadeIn",
                    effectTime: 250
                });
            }
            if (~~_modOpts.showSeparator && items.length) {
                var separator = $('<i />');
                separator.addClass("scroller-separator").append('<span />').find('span').text(opts.state.currPage);
                $(items[0]).before(separator);
            }
            if (_modOpts.loadingMode === 'smart' && !btn) {
                if (_modOpts.show_btn_after <= ispInstance.options.state.currPage) {
                    prodCont.infinitescroll('pause');
                    _addButton();
                }
            }
            if (~~_modOpts.statefulScroll) {
                _preservePosition();
            }
            _bindItemEvents(items);
            _observeDomChanges();
        },
        _bindItemEvents = function(items) {
            if (!$eventBuffer) {
                $eventBuffer = $('<div />').copyEvents($(_modOpts.itemSelector).first());
            }
            $.each(items, function(i, item) {
                $(item).copyEvents($eventBuffer);
            });
        },
        _addButton = function() {
            var btnCont = $('<div />', {
                class: 'scroller-btn-cont'
            });
            btn = $('<a />', {
                class: 'scroller-btn btn btn-default'
            });
            btn.text(_modOpts.buttonLabel).on('click', function() {
                prodCont.infinitescroll('retrieve');
            });
            prodCont.after(btnCont.append(btn));
        },
        _observeDomChanges = function() {
            $('body').observe('childList', _modOpts.paginatorSelector, function() {
                _destroy();
                _create();
            });
            $('body').observe({
                attributes: true,
                attributeFilter: ['href']
            }, _modOpts.paginatorSelector + ' a', function() {
                _destroy();
                _create();
            });
        },
        _nearbottom = function() {
            var $lastItem = $(_modOpts.itemSelector).last();
            return $window.scrollTop() + $window.height() + this.options.bufferPx > $lastItem.offset().top + $lastItem.height();
        },
        _showdonemsg = function() {
            var opts = this.options;
            opts.loading.msg.find('img').animate({
                opacity: 0
            }, 500).parent().find('i').html(opts.loading.finishedMsg);
            opts.loading.msg.delay(3000).animate({
                opacity: 0
            }, 1000, function() {
                $(this).slideUp(500, function() {
                    $(this).css({
                        opacity: 1
                    });
                });
            });
            opts.errorCallback.call($(opts.contentSelector)[0], 'done');
            lastPage = opts.state.currPage - 1;
        },
        _onLoadingFinished = function() {
            var opts = ispInstance.options;
            if (!opts.state.isBeyondMaxPage) {
                opts.loading.msg.animate({
                    opacity: 0
                }, 100, function() {
                    $(this).slideUp(200, function() {
                        $(this).css({
                            opacity: 1
                        });
                    });
                });
            }
        },
        _createScrollTopBar = function() {
            $scrollBar = $('<div />', {
                class: 'scroller-bar'
            });
            $arrow = $('<div />', {
                class: 'scroller-arrow'
            });
            $scrollBar.css({
                display: 'none',
                backgroundColor: _barOpts.barColor
            });
            $scrollBar.addClass(_barOpts.position === 'left' ? 'scroller-left' : 'scroller-right');
            $arrow.css({
                backgroundColor: _barOpts.arrowColor
            });
            $arrow.append('<div/>');
            $arrow.find('div').css({
                borderBottomColor: _barOpts.arrowColor,
                borderTopColor: 'transparent'
            });
            $('body').append($scrollBar.append($arrow));
            _onResize();
            $scrollBar.on('click', _onScrollBarClick);
            $document.on('scroll', _onSroll);
            $window.on('resize', _onResize);
        },
        _onScrollBarClick = function() {
            var toTop = !$scrollBar.hasClass('scroller-top'),
                stickTo = toTop ? 'bottom' : 'top',
                curPos = toTop ? $window.scrollTop() : scrollPos,
                scrollTo = toTop ? 0 : curPos,
                css1 = {},
                css2 = {},
                css3 = {};
            scrollPos = curPos;
            if (toTop && !curPos) {
                return;
            }
            css1[stickTo] = parseInt($arrow.css(stickTo)) + 50;
            css1.opacity = 0;
            css2[stickTo] = '';
            css2.opacity = 1;
            css3.borderBottomColor = toTop ? 'transparent' : _barOpts.arrowColor;
            css3.borderTopColor = toTop ? _barOpts.arrowColor : 'transparent';
            $scrollBar.data('auto', true);
            $scrollBar.data('toTop', toTop);
            $scrollBar.data('scrollTo', scrollTo);
            $('html, body').stop().animate({
                scrollTop: scrollTo
            }, ~~_barOpts.speed, function() {
                $scrollBar.removeClass('scroller-auto');
            });
            $arrow.stop().animate(css1, 300, function() {
                $scrollBar.toggleClass('scroller-top', toTop);
                $arrow.delay(300).css(css2).stop().fadeIn(300).find('div').css(css3);
            });
        },
        _onSroll = function() {
            var curPos = $window.scrollTop(),
                auto = $scrollBar.data('auto'),
                scrollTo = $scrollBar.data('scrollTo');
            if (!auto) {
                if (curPos) {
                    if (!$scrollBar.is(':visible')) {
                        $scrollBar.stop().fadeIn(300);
                    }
                } else if ($scrollBar.is(':visible')) {
                    $scrollBar.stop().fadeOut(300);
                }
                $scrollBar.removeClass('scroller-top');
                $arrow.find('div').css({
                    borderBottomColor: _barOpts.arrowColor,
                    borderTopColor: 'transparent'
                });
            }
            if (auto && curPos === scrollTo) {
                $scrollBar.data('auto', false);
            }
        },
        _onResize = function() {
            var curWidth = $window.width();
            $scrollBar.toggleClass('scroller-hidden', curWidth <= ~~_barOpts.minWidthToShow);
            $scrollBar.toggleClass('scroller-as-btn', curWidth <= ~~_barOpts.minWidthToShowAsBar);
            if (_barOpts.fitTo) {
                var elem = $(_barOpts.fitTo).first();
                if (elem.size()) {
                    if (_barOpts.position === 'left') {
                        var left = elem.offset().left;
                        $scrollBar.css({
                            left: left,
                            right: 'auto'
                        });
                    } else {
                        var right = $document.width() - elem.offset().left -
                            elem.outerWidth();
                        $scrollBar.css({
                            left: 'auto',
                            right: right
                        });
                    }
                }
            }
        },
        _preservePosition = function() {
            if (typeof window.history.pushState === 'undefined') {
                return;
            }
            var curPos = ~~$document.scrollTop(),
                ispPage = lastPage || ispInstance.options.state.currPage,
                pct = prodCont.offset().top,
                pch = prodCont.height(),
                sch = $window.height(),
                curPage = Math.ceil((curPos - pct + sch) / (pch / ispPage)),
                ls = window.location.search.replace(/\&?(xpage|xoffset)\=[\d]+/g, ''),
                loc;
            curPage = curPage < 1 ? 1 : (curPage > ispPage ? ispPage : curPage);
            ls = ls === '' ? '?' : (ls === '?' ? ls : ls + '&');
            ls += 'xpage=' + curPage + '&xoffset=' + curPos;
            loc = window.location.protocol + '//' + window.location.hostname +
                window.location.pathname + ls + window.location.hash;
            window.history.replaceState(null, null, loc);
        },
        _customLoading = function() {
            var opts = ispInstance.options,
                instance = ispInstance;
            $(opts.navSelector).hide();
            opts.loading.msg.appendTo(opts.loading.selector).show(opts.loading.speed, $.proxy(function() {
                opts.state.currPage++;
                var url_parts = [];
                if (window.location.hash.substring(1) === '') {
                    if ($('.sort').length > 0) {
                        var value = $('.sort select option:selected').val().split('sort=')[1].split('&');
                        url_parts.push('sort=' + value[0]);
                        url_parts.push('order=' + value[1].replace('order=', ''));
                    }
                    if ($('.limit').length > 0) {
                        url_parts.push('limit=' + $('.limit select option:selected').text());
                    }
                }
                if (opts.state.currPage) {
                    url_parts.push('page=' + opts.state.currPage);
                }
                var $parent = $('.journal-sf'),
                    url = window.location.hash.substring(1) + '/' + url_parts.join('/'),
                    data = {
                        filters: url,
                        route: $parent.attr('data-route'),
                        path: $parent.attr('data-path'),
                        manufacturer_id: $parent.attr('data-manufacturer'),
                        search: $parent.attr('data-search'),
                        tag: $parent.attr('data-tag')
                    };
                $.ajax({
                    url: $parent.attr('data-products-action'),
                    type: 'post',
                    data: data,
                    success: function(response) {
                        var box = $(opts.contentSelector).is('table, tbody') ? $('<tbody/>') : $('<div/>'),
                            div = $('<div/>'),
                            $items = div.append(response).find(opts.itemSelector);
                        box.append($items);
                        instance._loadcallback(box, response, url);
                    }
                });
            }, self));
        };
    return {
        init: init
    };
})(jQuery);

window['scroller'] = scroller;
(function(d) {
    d.Observe = {}
})(jQuery);
(function(d, q) {
    var r = function(e, f) {
        f || (f = e, e = window.document);
        var m = [];
        d(f).each(function() {
            for (var l = [], g = d(this), h = g.parent(); h.length && !g.is(e); h = h.parent()) {
                var f = g.get(0).tagName.toLowerCase();
                l.push(f + ":eq(" + h.children(f).index(g) + ")");
                g = h
            }(h.length || g.is(e)) && m.push("> " + l.reverse().join(" > "))
        });
        return m.join(", ")
    };
    q.path = {
        get: r,
        capture: function(e, f) {
            f || (f = e, e = window.document);
            var m = [];
            d(f).each(function() {
                var l = -1,
                    g = this;
                if (this instanceof Text)
                    for (var g = this.parentNode, h = g ? g.childNodes : [], f = 0; f < h.length; f++)
                        if (h[f] === this) {
                            l = f;
                            break
                        }
                var k = r(e, g),
                    n = d(e).is(g);
                m.push(function(e) {
                    e = n ? e : d(e).find(k);
                    return -1 === l ? e : e.contents()[l]
                })
            });
            return function(e) {
                e = e || window.document;
                return m.reduce(function(d, f) {
                    return d.add(f(e))
                }, d([]))
            }
        }
    }
})(jQuery, jQuery.Observe);
(function(d, q) {
    var r = function(e) {
        this.original = d(e);
        this.root = this.original.clone(!1, !0)
    };
    r.prototype.find = function(d) {
        return q.path.capture(this.original, d)(this.root)
    };
    q.Branch = r
})(jQuery, jQuery.Observe);
(function(d, q) {
    var r = function(a, b) {
            var c = {};
            a.forEach(function(a) {
                (a = b(a)) && (c[a[0]] = a[1])
            });
            return c
        },
        e = r("childList attributes characterData subtree attributeOldValue characterDataOldValue attributeFilter".split(" "), function(a) {
            return [a.toLowerCase(), a]
        }),
        f = r(Object.keys(e), function(a) {
            if ("attributefilter" !== a) return [e[a], !0]
        }),
        m = r(["added", "removed"], function(a) {
            return [a.toLowerCase(), a]
        }),
        l = d([]),
        g = function(a) {
            if ("object" === typeof a) return a;
            a = a.split(/\s+/);
            var b = {};
            a.forEach(function(a) {
                a = a.toLowerCase();
                if (!e[a] && !m[a]) throw Error("Unknown option " + a);
                b[e[a] || m[a]] = !0
            });
            return b
        },
        h = function(a) {
            return "[" + Object.keys(a).sort().reduce(function(b, c) {
                var d = a[c] && "object" === typeof a[c] ? h(a[c]) : a[c];
                return b + "[" + JSON.stringify(c) + ":" + d + "]"
            }, "") + "]"
        },
        t = window.MutationObserver || window.WebKitMutationObserver,
        k = function(a, b, c, s) {
            this._originalOptions = d.extend({}, b);
            b = d.extend({}, b);
            this.attributeFilter = b.attributeFilter;
            delete b.attributeFilter;
            c && (b.subtree = !0);
            b.childList && (b.added = !0, b.removed = !0);
            if (b.added || b.removed) b.childList = !0;
            this.target = d(a);
            this.options = b;
            this.selector = c;
            this.handler = s
        };
    k.prototype.is = function(a, b, c) {
        return h(this._originalOptions) === h(a) && this.selector === b && this.handler === c
    };
    k.prototype.match = function(a) {
        var b = this.options,
            c = a.type;
        if (!this.options[c]) return l;
        if (this.selector) switch (c) {
            case "attributes":
                if (!this._matchAttributeFilter(a)) break;
            case "characterData":
                return this._matchAttributesAndCharacterData(a);
            case "childList":
                if (a.addedNodes && a.addedNodes.length && b.added && (c = this._matchAddedNodes(a), c.length)) return c;
                if (a.removedNodes && a.removedNodes.length && b.removed) return this._matchRemovedNodes(a)
        } else {
            var s = a.target instanceof Text ? d(a.target).parent() : d(a.target);
            if (!b.subtree && s.get(0) !== this.target.get(0)) return l;
            switch (c) {
                case "attributes":
                    if (!this._matchAttributeFilter(a)) break;
                case "characterData":
                    return this.target;
                case "childList":
                    if (a.addedNodes && a.addedNodes.length && b.added || a.removedNodes && a.removedNodes.length && b.removed) return this.target
            }
        }
        return l
    };
    k.prototype._matchAttributesAndCharacterData = function(a) {
        return this._matchSelector(this.target, [a.target])
    };
    k.prototype._matchAddedNodes = function(a) {
        return this._matchSelector(this.target, a.addedNodes)
    };
    k.prototype._matchRemovedNodes = function(a) {
        var b = new q.Branch(this.target),
            c = Array.prototype.slice.call(a.removedNodes).map(function(a) {
                return a.cloneNode(!0)
            });
        a.previousSibling ? b.find(a.previousSibling).after(c) : a.nextSibling ? b.find(a.nextSibling).before(c) : (this.target === a.target ? b.root : b.find(a.target)).empty().append(c);
        return this._matchSelector(b.root, c).length ? d(a.target) : l
    };
    k.prototype._matchSelector = function(a, b) {
        var c = a.find(this.selector);
        b = Array.prototype.slice.call(b);
        return c = c.filter(function() {
            var a = this;
            return b.some(function(b) {
                return b instanceof Text ? b.parentNode === a : b === a || d(b).has(a).length
            })
        })
    };
    k.prototype._matchAttributeFilter = function(a) {
        return this.attributeFilter && this.attributeFilter.length ? 0 <= this.attributeFilter.indexOf(a.attributeName) : !0
    };
    var n = function(a) {
        this.patterns = [];
        this._target = a;
        this._observer = null
    };
    n.prototype.observe = function(a, b, c) {
        var d = this;
        this._observer ? this._observer.disconnect() : this._observer = new t(function(a) {
            a.forEach(function(a) {
                d.patterns.forEach(function(b) {
                    var c = b.match(a);
                    c.length && c.each(function() {
                        b.handler.call(this, a)
                    })
                })
            })
        });
        this.patterns.push(new k(this._target, a, b, c));
        this._observer.observe(this._target, this._collapseOptions())
    };
    n.prototype.disconnect = function(a, b, c) {
        var d = this;
        this._observer && (this.patterns.filter(function(d) {
            return d.is(a, b, c)
        }).forEach(function(a) {
            a = d.patterns.indexOf(a);
            d.patterns.splice(a, 1)
        }), this.patterns.length || this._observer.disconnect())
    };
    n.prototype.disconnectAll = function() {
        this._observer && (this.patterns = [], this._observer.disconnect())
    };
    n.prototype.pause = function() {
        this._observer && this._observer.disconnect()
    };
    n.prototype.resume = function() {
        this._observer && this._observer.observe(this._target, this._collapseOptions())
    };
    n.prototype._collapseOptions = function() {
        var a = {};
        this.patterns.forEach(function(b) {
            var c = a.attributes && a.attributeFilter;
            if (!c && a.attributes || !b.attributeFilter) c && b.options.attributes && !b.attributeFilter && delete a.attributeFilter;
            else {
                var e = {},
                    f = [];
                (a.attributeFilter || []).concat(b.attributeFilter).forEach(function(a) {
                    e[a] || (f.push(a), e[a] = 1)
                });
                a.attributeFilter = f
            }
            d.extend(a, b.options)
        });
        Object.keys(m).forEach(function(b) {
            delete a[m[b]]
        });
        return a
    };
    var p = function(a) {
        this.patterns = [];
        this._paused = !1;
        this._target = a;
        this._events = {};
        this._handler = this._handler.bind(this)
    };
    p.prototype.NS = ".jQueryObserve";
    p.prototype.observe = function(a, b, c) {
        a = new k(this._target, a, b, c);
        d(this._target);
        a.options.childList && (this._addEvent("DOMNodeInserted"), this._addEvent("DOMNodeRemoved"));
        a.options.attributes && this._addEvent("DOMAttrModified");
        a.options.characterData && this._addEvent("DOMCharacerDataModified");
        this.patterns.push(a)
    };
    p.prototype.disconnect = function(a, b, c) {
        var e = d(this._target),
            f = this;
        this.patterns.filter(function(d) {
            return d.is(a, b, c)
        }).forEach(function(a) {
            a = f.patterns.indexOf(a);
            f.patterns.splice(a, 1)
        });
        var g = this.patterns.reduce(function(a, b) {
            b.options.childList && (a.DOMNodeInserted = !0, a.DOMNodeRemoved = !0);
            b.options.attributes && (a.DOMAttrModified = !0);
            b.options.characterData && (a.DOMCharacerDataModified = !0);
            return a
        }, {});
        Object.keys(this._events).forEach(function(a) {
            g[a] || (delete f._events[a], e.off(a + f.NS, f._handler))
        })
    };
    p.prototype.disconnectAll = function() {
        var a = d(this._target),
            b;
        for (b in this._events) a.off(b + this.NS, this._handler);
        this._events = {};
        this.patterns = []
    };
    p.prototype.pause = function() {
        this._paused = !0
    };
    p.prototype.resume = function() {
        this._paused = !1
    };
    p.prototype._handler = function(a) {
        if (!this._paused) {
            var b = {
                type: null,
                target: null,
                addedNodes: null,
                removedNodes: null,
                previousSibling: null,
                nextSibling: null,
                attributeName: null,
                attributeNamespace: null,
                oldValue: null
            };
            switch (a.type) {
                case "DOMAttrModified":
                    b.type = "attributes";
                    b.target = a.target;
                    b.attributeName = a.attrName;
                    b.oldValue = a.prevValue;
                    break;
                case "DOMCharacerDataModified":
                    b.type = "characterData";
                    b.target = d(a.target).parent().get(0);
                    b.attributeName = a.attrName;
                    b.oldValue = a.prevValue;
                    break;
                case "DOMNodeInserted":
                    b.type = "childList";
                    b.target = a.relatedNode;
                    b.addedNodes = [a.target];
                    b.removedNodes = [];
                    break;
                case "DOMNodeRemoved":
                    b.type = "childList", b.target = a.relatedNode, b.addedNodes = [], b.removedNodes = [a.target]
            }
            for (a = 0; a < this.patterns.length; a++) {
                var c = this.patterns[a],
                    e = c.match(b);
                e.length && e.each(function() {
                    c.handler.call(this, b)
                })
            }
        }
    };
    p.prototype._addEvent = function(a) {
        this._events[a] || (d(this._target).on(a + this.NS, this._handler), this._events[a] = !0)
    };
    q.Pattern = k;
    q.MutationObserver = n;
    q.DOMEventObserver = p;
    d.fn.observe = function(a, b, c) {
        b ? c || (c = b, b = null) : (c = a, a = f);
        return this.each(function() {
            var e = d(this),
                f = e.data("observer");
            f || (f = t ? new n(this) : new p(this), e.data("observer", f));
            a = g(a);
            f.observe(a, b, c)
        })
    };
    d.fn.disconnect = function(a, b, c) {
        a && (b ? c || (c = b, b = null) : (c = a, a = f));
        return this.each(function() {
            var e = d(this),
                f = e.data("observer");
            f && (a ? (a = g(a), f.disconnect(a, b, c)) : (f.disconnectAll(), e.removeData("observer")))
        })
    }
})(jQuery, jQuery.Observe);
(function(a) {
    "function" === typeof define && define.amd ? define(["jquery"], a) : a(jQuery)
})(function(a) {
    a.fn.extend({
        copyEvents: function(c) {
            a.event.copy(a(c), this);
            return this
        },
        copyEventsTo: function(c) {
            a.event.copy(this, a(c));
            return this
        }
    });
    a.event.copy = function(c, g) {
        var b = c[0],
            f = b && (a._data && a._data(b, "events") || a.data && a.data(b, "events") || b.$events || b.events) || {};
        g.each(function() {
            var c = this,
                b;
            for (b in f) a.each(f[b], function(f, d) {
                var e = void 0 !== d.namespace && d.namespace || d.type || "",
                    e = e.length ? (0 === e.indexOf(".") ? "" : ".") + e : "";
                a.event.add(c, b + e, d.handler || d, d.data)
            })
        })
    }
});
(function(e, t, n) {
    "use strict";
    t.infinitescroll = function(n, r, i) {
        this.element = t(i);
        if (!this._create(n, r)) {
            this.failed = true
        }
    };
    t.infinitescroll.defaults = {
        loading: {
            finished: n,
            finishedMsg: "<em>Congratulations, you've reached the end of the internet.</em>",
            img: "",
            msg: null,
            msgText: "<em>Loading the next set of posts...</em>",
            selector: null,
            speed: "fast",
            start: n
        },
        state: {
            isDuringAjax: false,
            isInvalidPage: false,
            isDestroyed: false,
            isDone: false,
            isPaused: false,
            isBeyondMaxPage: false,
            currPage: 1
        },
        debug: false,
        behavior: n,
        binder: t(e),
        nextSelector: "div.navigation a:first",
        navSelector: "div.navigation",
        contentSelector: null,
        extraScrollPx: 150,
        itemSelector: "div.post",
        animate: false,
        pathParse: n,
        dataType: "html",
        appendCallback: true,
        bufferPx: 40,
        errorCallback: function() {},
        infid: 0,
        pixelsFromNavToBottom: n,
        path: n,
        prefill: false,
        maxPage: n
    };
    t.infinitescroll.prototype = {
        _binding: function(t) {
            var r = this,
                i = r.options;
            i.v = "2.0b2.120520";
            if (!!i.behavior && this["_binding_" + i.behavior] !== n) {
                this["_binding_" + i.behavior].call(this);
                return
            }
            if (t !== "bind" && t !== "unbind") {
                this._debug("Binding value  " + t + " not valid");
                return false
            }
            if (t === "unbind") {
                this.options.binder.unbind("smartscroll.infscr." + r.options.infid)
            } else {
                this.options.binder[t]("smartscroll.infscr." + r.options.infid, function() {
                    r.scroll()
                })
            }
            this._debug("Binding", t)
        },
        _create: function(i, s) {
            var o = t.extend(true, {}, t.infinitescroll.defaults, i);
            this.options = o;
            var u = t(e);
            var a = this;
            if (!a._validate(i)) {
                return false
            }
            var f = t(o.nextSelector).attr("href");
            if (!f) {
                this._debug("Navigation selector not found");
                return false
            }
            o.path = o.path || this._determinepath(f);
            o.contentSelector = o.contentSelector || this.element;
            o.loading.selector = o.loading.selector || o.contentSelector;
            o.loading.msg = o.loading.msg || t('<div id="infscr-loading"><img alt="Loading..." src="' + o.loading.img + '" /><div>' + o.loading.msgText + "</div></div>");
            (new Image).src = o.loading.img;
            if (o.pixelsFromNavToBottom === n) {
                o.pixelsFromNavToBottom = t(document).height() - t(o.navSelector).offset().top;
                this._debug("pixelsFromNavToBottom: " + o.pixelsFromNavToBottom)
            }
            var l = this;
            o.loading.start = o.loading.start || function() {
                t(o.navSelector).hide();
                o.loading.msg.appendTo(o.loading.selector).show(o.loading.speed, t.proxy(function() {
                    this.beginAjax(o)
                }, l))
            };
            o.loading.finished = o.loading.finished || function() {
                if (!o.state.isBeyondMaxPage) o.loading.msg.fadeOut(o.loading.speed)
            };
            o.callback = function(e, r, i) {
                if (!!o.behavior && e["_callback_" + o.behavior] !== n) {
                    e["_callback_" + o.behavior].call(t(o.contentSelector)[0], r, i)
                }
                if (s) {
                    s.call(t(o.contentSelector)[0], r, o, i)
                }
                if (o.prefill) {
                    u.bind("resize.infinite-scroll", e._prefill)
                }
            };
            if (i.debug) {
                if (Function.prototype.bind && (typeof console === "object" || typeof console === "function") && typeof console.log === "object") {
                    ["log", "info", "warn", "error", "assert", "dir", "clear", "profile", "profileEnd"].forEach(function(e) {
                        console[e] = this.call(console[e], console)
                    }, Function.prototype.bind)
                }
            }
            this._setup();
            if (o.prefill) {
                this._prefill()
            }
            return true
        },
        _prefill: function() {
            function s() {
                return r.options.contentSelector.height() <= i.height()
            }
            var r = this;
            var i = t(e);
            this._prefill = function() {
                if (s()) {
                    r.scroll()
                }
                i.bind("resize.infinite-scroll", function() {
                    if (s()) {
                        i.unbind("resize.infinite-scroll");
                        r.scroll()
                    }
                })
            };
            this._prefill()
        },
        _debug: function() {
            if (true !== this.options.debug) {
                return
            }
            if (typeof console !== "undefined" && typeof console.log === "function") {
                if (Array.prototype.slice.call(arguments).length === 1 && typeof Array.prototype.slice.call(arguments)[0] === "string") {
                    console.log(Array.prototype.slice.call(arguments).toString())
                } else {
                    console.log(Array.prototype.slice.call(arguments))
                }
            } else if (!Function.prototype.bind && typeof console !== "undefined" && typeof console.log === "object") {
                Function.prototype.call.call(console.log, console, Array.prototype.slice.call(arguments))
            }
        },
        _determinepath: function(t) {
            var r = this.options;
            if (!!r.behavior && this["_determinepath_" + r.behavior] !== n) {
                return this["_determinepath_" + r.behavior].call(this, t)
            }
            if (!!r.pathParse) {
                this._debug("pathParse manual");
                return r.pathParse(t, this.options.state.currPage + 1)
            } else if (t.match(/^(.*?)\b2\b(.*?$)/)) {
                t = t.match(/^(.*?)\b2\b(.*?$)/).slice(1)
            } else if (t.match(/^(.*?)2(.*?$)/)) {
                if (t.match(/^(.*?page=)2(\/.*|$)/)) {
                    t = t.match(/^(.*?page=)2(\/.*|$)/).slice(1);
                    return t
                }
                t = t.match(/^(.*?)2(.*?$)/).slice(1)
            } else {
                if (t.match(/^(.*?page=)1(\/.*|$)/)) {
                    t = t.match(/^(.*?page=)1(\/.*|$)/).slice(1);
                    return t
                } else {
                    this._debug("Sorry, we couldn't parse your Next (Previous Posts) URL. Verify your the css selector points to the correct A tag. If you still get this error: yell, scream, and kindly ask for help at infinite-scroll.com.");
                    r.state.isInvalidPage = true
                }
            }
            this._debug("determinePath", t);
            return t
        },
        _error: function(t) {
            var r = this.options;
            if (!!r.behavior && this["_error_" + r.behavior] !== n) {
                this["_error_" + r.behavior].call(this, t);
                return
            }
            if (t !== "destroy" && t !== "end") {
                t = "unknown"
            }
            this._debug("Error", t);
            if (t === "end" || r.state.isBeyondMaxPage) {
                this._showdonemsg()
            }
            r.state.isDone = true;
            r.state.currPage = 1;
            r.state.isPaused = false;
            r.state.isBeyondMaxPage = false;
            this._binding("unbind")
        },
        _loadcallback: function(i, s, o) {
            var u = this.options,
                a = this.options.callback,
                f = u.state.isDone ? "done" : !u.appendCallback ? "no-append" : "append",
                l;
            if (!!u.behavior && this["_loadcallback_" + u.behavior] !== n) {
                this["_loadcallback_" + u.behavior].call(this, i, s);
                return
            }
            switch (f) {
                case "done":
                    this._showdonemsg();
                    return false;
                case "no-append":
                    if (u.dataType === "html") {
                        s = "<div>" + s + "</div>";
                        s = t(s).find(u.itemSelector)
                    }
                    break;
                case "append":
                    var c = i.children();
                    if (c.length === 0) {
                        return this._error("end")
                    }
                    l = document.createDocumentFragment();
                    while (i[0].firstChild) {
                        l.appendChild(i[0].firstChild)
                    }
                    this._debug("contentSelector", t(u.contentSelector)[0]);
                    t(u.contentSelector)[0].appendChild(l);
                    s = c.get();
                    break
            }
            u.loading.finished.call(t(u.contentSelector)[0], u);
            if (u.animate) {
                var h = t(e).scrollTop() + t(u.loading.msg).height() + u.extraScrollPx + "px";
                t("html,body").animate({
                    scrollTop: h
                }, 800, function() {
                    u.state.isDuringAjax = false
                })
            }
            if (!u.animate) {
                u.state.isDuringAjax = false
            }
            a(this, s, o);
            if (u.prefill) {
                this._prefill()
            }
        },
        _nearbottom: function() {
            var i = this.options,
                s = 0 + t(document).height() - i.binder.scrollTop() - t(e).height();
            if (!!i.behavior && this["_nearbottom_" + i.behavior] !== n) {
                return this["_nearbottom_" + i.behavior].call(this)
            }
            this._debug("math:", s, i.pixelsFromNavToBottom);
            return s - i.bufferPx < i.pixelsFromNavToBottom
        },
        _pausing: function(t) {
            var r = this.options;
            if (!!r.behavior && this["_pausing_" + r.behavior] !== n) {
                this["_pausing_" + r.behavior].call(this, t);
                return
            }
            if (t !== "pause" && t !== "resume" && t !== null) {
                this._debug("Invalid argument. Toggling pause value instead")
            }
            t = t && (t === "pause" || t === "resume") ? t : "toggle";
            switch (t) {
                case "pause":
                    r.state.isPaused = true;
                    break;
                case "resume":
                    r.state.isPaused = false;
                    break;
                case "toggle":
                    r.state.isPaused = !r.state.isPaused;
                    break
            }
            this._debug("Paused", r.state.isPaused);
            return false
        },
        _setup: function() {
            var t = this.options;
            if (!!t.behavior && this["_setup_" + t.behavior] !== n) {
                this["_setup_" + t.behavior].call(this);
                return
            }
            this._binding("bind");
            return false
        },
        _showdonemsg: function() {
            var r = this.options;
            if (!!r.behavior && this["_showdonemsg_" + r.behavior] !== n) {
                this["_showdonemsg_" + r.behavior].call(this);
                return
            }
            r.loading.msg.find("img").hide().parent().find("div").html(r.loading.finishedMsg).animate({
                opacity: 1
            }, 2e3, function() {
                t(this).parent().fadeOut(r.loading.speed)
            });
            r.errorCallback.call(t(r.contentSelector)[0], "done")
        },
        _validate: function(n) {
            for (var r in n) {
                if (r.indexOf && r.indexOf("Selector") > -1 && t(n[r]).length === 0) {
                    this._debug("Your " + r + " found no elements.");
                    return false
                }
            }
            return true
        },
        bind: function() {
            this._binding("bind")
        },
        destroy: function() {
            this.options.state.isDestroyed = true;
            this.options.loading.finished();
            return this._error("destroy")
        },
        pause: function() {
            this._pausing("pause")
        },
        resume: function() {
            this._pausing("resume")
        },
        beginAjax: function(r) {
            var i = this,
                s = r.path,
                o, u, a, f;
            r.state.currPage++;
            if (r.maxPage != n && r.state.currPage > r.maxPage) {
                r.state.isBeyondMaxPage = true;
                this.destroy();
                return
            }
            o = t(r.contentSelector).is("table, tbody") ? t("<tbody/>") : t("<div/>");
            u = typeof s === "function" ? s(r.state.currPage) : s.join(r.state.currPage);
            i._debug("heading into ajax", u);
            a = r.dataType === "html" || r.dataType === "json" ? r.dataType : "html+callback";
            if (r.appendCallback && r.dataType === "html") {
                a += "+callback"
            }
            switch (a) {
                case "html+callback":
                    i._debug("Using HTML via .load() method");
                    o.load(u + " " + r.itemSelector, n, function(t) {
                        i._loadcallback(o, t, u)
                    });
                    break;
                case "html":
                    i._debug("Using " + a.toUpperCase() + " via $.ajax() method");
                    t.ajax({
                        url: u,
                        dataType: r.dataType,
                        complete: function(t, n) {
                            f = typeof t.isResolved !== "undefined" ? t.isResolved() : n === "success" || n === "notmodified";
                            if (f) {
                                i._loadcallback(o, t.responseText, u)
                            } else {
                                i._error("end")
                            }
                        }
                    });
                    break;
                case "json":
                    i._debug("Using " + a.toUpperCase() + " via $.ajax() method");
                    t.ajax({
                        dataType: "json",
                        type: "GET",
                        url: u,
                        success: function(e, t, s) {
                            f = typeof s.isResolved !== "undefined" ? s.isResolved() : t === "success" || t === "notmodified";
                            if (r.appendCallback) {
                                if (r.template !== n) {
                                    var a = r.template(e);
                                    o.append(a);
                                    if (f) {
                                        i._loadcallback(o, a)
                                    } else {
                                        i._error("end")
                                    }
                                } else {
                                    i._debug("template must be defined.");
                                    i._error("end")
                                }
                            } else {
                                if (f) {
                                    i._loadcallback(o, e, u)
                                } else {
                                    i._error("end")
                                }
                            }
                        },
                        error: function() {
                            i._debug("JSON ajax request failed.");
                            i._error("end")
                        }
                    });
                    break
            }
        },
        retrieve: function(r) {
            r = r || null;
            var i = this,
                s = i.options;
            if (!!s.behavior && this["retrieve_" + s.behavior] !== n) {
                this["retrieve_" + s.behavior].call(this, r);
                return
            }
            if (s.state.isDestroyed) {
                this._debug("Instance is destroyed");
                return false
            }
            s.state.isDuringAjax = true;
            s.loading.start.call(t(s.contentSelector)[0], s)
        },
        scroll: function() {
            var t = this.options,
                r = t.state;
            if (!!t.behavior && this["scroll_" + t.behavior] !== n) {
                this["scroll_" + t.behavior].call(this);
                return
            }
            if (r.isDuringAjax || r.isInvalidPage || r.isDone || r.isDestroyed || r.isPaused) {
                return
            }
            if (!this._nearbottom()) {
                return
            }
            this.retrieve()
        },
        toggle: function() {
            this._pausing()
        },
        unbind: function() {
            this._binding("unbind")
        },
        update: function(n) {
            if (t.isPlainObject(n)) {
                this.options = t.extend(true, this.options, n)
            }
        }
    };
    t.fn.infinitescroll = function(n, r) {
        var i = typeof n;
        switch (i) {
            case "string":
                var s = Array.prototype.slice.call(arguments, 1);
                this.each(function() {
                    var e = t.data(this, "infinitescroll");
                    if (!e) {
                        return false
                    }
                    if (!t.isFunction(e[n]) || n.charAt(0) === "_") {
                        return false
                    }
                    e[n].apply(e, s)
                });
                break;
            case "object":
                this.each(function() {
                    var e = t.data(this, "infinitescroll");
                    if (e) {
                        e.update(n)
                    } else {
                        e = new t.infinitescroll(n, r, this);
                        if (!e.failed) {
                            t.data(this, "infinitescroll", e)
                        }
                    }
                });
                break
        }
        return this
    };
    var r = t.event,
        i;
    r.special.smartscroll = {
        setup: function() {
            t(this).bind("scroll", r.special.smartscroll.handler)
        },
        teardown: function() {
            t(this).unbind("scroll", r.special.smartscroll.handler)
        },
        handler: function(e, n) {
            var r = this,
                s = arguments;
            e.type = "smartscroll";
            if (i) {
                clearTimeout(i)
            }
            i = setTimeout(function() {
                t(r).trigger("smartscroll", s)
            }, n === "execAsap" ? 0 : 100)
        }
    };
    t.fn.smartscroll = function(e) {
        return e ? this.bind("smartscroll", e) : this.trigger("smartscroll", ["execAsap"])
    }
})(window, jQuery);