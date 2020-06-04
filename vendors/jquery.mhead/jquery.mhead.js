;(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        module.exports = factory(require('jquery'));
    } else {
        root.jquery_mhead_js = factory(root.jQuery);
    }
}(this, function (jQuery) {
    /*
     * jQuery mhead v1.0.1
     * @requires jQuery 1.7.0 or later
     *
     * mmenu.frebsite.nl/mhead-plugin
     *
     * Copyright (c) Fred Heusschen
     * www.frebsite.nl
     *
     * License: CC-BY-4.0
     * http://creativecommons.org/licenses/by/4.0/
     */
    'use strict';
    !function ($) {
        /**
         * @return {undefined}
         */
        function init() {
            if (!$[i].glbl) {
                idealSelect = {
                    $wndw: $(window),
                    $docu: $(document),
                    $html: $("html"),
                    $body: $("body")
                };
                defaults = {};
                input = {};
                settings = {};
                $.each([defaults, input, settings], function (canCreateDiscussions, f) {
                    /**
                     * @param {string} c
                     * @return {undefined}
                     */
                    f.add = function (c) {
                        c = c.split(" ");
                        /** @type {number} */
                        var i = 0;
                        var cl = c.length;
                        for (; i < cl; i++) {
                            f[c[i]] = f.mh(c[i]);
                        }
                    };
                });
                /**
                 * @param {string} id
                 * @return {?}
                 */
                defaults.mh = function (id) {
                    return "mh-" + id;
                };
                defaults.add("head sticky scrolledout align btns list hamburger");
                /**
                 * @param {string} headerPlusSegments
                 * @return {?}
                 */
                defaults.umh = function (headerPlusSegments) {
                    return "mh-" == headerPlusSegments.slice(0, 3) && (headerPlusSegments = headerPlusSegments.slice(3)), headerPlusSegments;
                };
                /**
                 * @param {string} id
                 * @return {?}
                 */
                input.mh = function (id) {
                    return "mh-" + id;
                };
                /**
                 * @param {string} canCreateDiscussions
                 * @return {?}
                 */
                settings.mh = function (canCreateDiscussions) {
                    return canCreateDiscussions + ".mh";
                };
                settings.add("scroll click");
                $[i]._c = defaults;
                $[i]._d = input;
                $[i]._e = settings;
                $[i].glbl = idealSelect;
            }
        }

        /** @type {string} */
        var i = "mhead";
        /** @type {string} */
        var version = "1.0.1";
        if (!($[i] && $[i].version > version)) {
            /**
             * @param {!Object} callback
             * @param {!Object} deep
             * @param {!Object} conf
             * @return {?}
             */
            $[i] = function (callback, deep, conf) {
                return this.$head = callback, this.opts = deep, this.conf = conf, this._initButtons(), this._initList(), this._initHamburger(), this._initScroll(), this;
            };
            /** @type {string} */
            $[i].version = version;
            $[i].defaults = {
                scroll: {
                    hide: 0,
                    show: 0,
                    tolerance: 4
                },
                hamburger: {
                    menu: null,
                    animation: "collapse"
                }
            };
            $[i].configuration = {
                initButtons: true,
                initList: true,
                initHamburger: true,
                initScroll: true
            };
            $[i].prototype = {
                _initButtons: function () {
                    if (!this.conf.initButtons) {
                        return this;
                    }
                    /** @type {boolean} */
                    var time = false;
                    var path = {
                        left: 0,
                        right: 0
                    };
                    /** @type {number} */
                    var m = 0;
                    /** @type {number} */
                    var x = 0;
                    var i;
                    for (i in path) {
                        time = time || this.$head.hasClass(defaults.align + "-" + i);
                        if (m = this.$head.children("." + defaults.btns + "-" + i).children().length) {
                            /** @type {number} */
                            x = Math.max(m, x);
                            path[i] = m;
                        }
                    }
                    if (!time) {
                        for (i in path) {
                            /** @type {number} */
                            path[i] = x;
                        }
                    }
                    for (i in path) {
                        if (path[i]) {
                            /** @type {string} */
                            var version = defaults.btns + "-" + i;
                            if (path[i] > 1) {
                                /** @type {string} */
                                version = version + ("-" + path[i]);
                            }
                            this.$head.addClass(version);
                        }
                    }
                    return this;
                },
                _initList: function () {
                    return this.conf.initList ? void this.$head.find("." + defaults.list).each(function () {
                        $(this).children().appendTo(this);
                    }) : this;
                },
                _initScroll: function () {
                    if (!this.conf.initScroll) {
                        return this;
                    }
                    if (!this.opts.scroll || this.opts.scroll.hide === false) {
                        return this;
                    }
                    if (!this.$head.hasClass(defaults.sticky)) {
                        this.$head.addClass(defaults.sticky);
                    }
                    var base = this;
                    /** @type {number} */
                    var targetValue = 0;
                    /** @type {null} */
                    var isBigger = null;
                    var value = this.$head.offset().top + this.$head.outerHeight();
                    return this.opts.scroll.hide = Math.max(value, this.opts.scroll.hide || 0), this.opts.scroll.show = Math.max(value, this.opts.scroll.show || 0), idealSelect.$wndw.on(settings.scroll, function () {
                        var value = idealSelect.$wndw.scrollTop();
                        /** @type {number} */
                        var delta = targetValue - value;
                        /** @type {string} */
                        var direction = delta < 0 ? "down" : "up";
                        /** @type {number} */
                        delta = Math.abs(delta);
                        targetValue = value;
                        if (null === isBigger) {
                            /** @type {boolean} */
                            isBigger = value > base.opts.scroll.show;
                        }
                        if (isBigger) {
                            if ("up" == direction && (value < base.opts.scroll.show || delta > base.opts.scroll.tolerance)) {
                                /** @type {boolean} */
                                isBigger = false;
                                base.$head.removeClass(defaults.scrolledout);
                            }
                        } else {
                            if ("down" == direction && value > base.opts.scroll.hide && delta > base.opts.scroll.tolerance) {
                                /** @type {boolean} */
                                isBigger = true;
                                base.$head.addClass(defaults.scrolledout);
                            }
                        }
                    }).trigger(settings.scroll), this;
                },
                _initHamburger: function () {
                    if (!this.conf.initHamburger) {
                        return this;
                    }
                    var $imagesToLoad = this.$head.find("." + defaults.hamburger);
                    if ($imagesToLoad.length) {
                        var transitionEvt = this;
                        return $imagesToLoad.each(function () {
                            var opts = $(this);
                            var element = $('<button class="hamburger"><span class="hamburger-box"><span class="hamburger-inner"></span></span></button>');
                            var this_href = opts.attr("href");
                            opts.replaceWith(element);
                            element.addClass("hamburger--" + transitionEvt.opts.hamburger.animation);
                            var r = $();
                            /** @type {!Array} */
                            var $canvases = [this_href, transitionEvt.opts.hamburger.menu, ".mm-menu"];
                            /** @type {number} */
                            var e = 0;
                            for (; e < $canvases.length; e++) {
                                if ($canvases[e] && (r = $($canvases[e]), r.length && r.is(".mm-menu"))) {
                                    r = r.first();
                                    break;
                                }
                            }
                            var myUI = r.data("mmenu");
                            element.on(settings.click, function () {
                                myUI.open();
                            });
                            myUI.bind("open:finish", function () {
                                setTimeout(function () {
                                    element.addClass("is-active");
                                }, 100);
                            });
                            myUI.bind("close:finish", function () {
                                setTimeout(function () {
                                    element.removeClass("is-active");
                                }, 100);
                            });
                        }), this;
                    }
                }
            };
            /**
             * @param {?} args
             * @param {?} config
             * @return {?}
             */
            $.fn[i] = function (args, config) {
                return init(), args = $.extend(true, {}, $[i].defaults, args), config = $.extend(true, {}, $[i].configuration, config), this.each(function () {
                    var _this = $(this);
                    if (!_this.data(i)) {
                        var plugin = new $[i](_this, args, config);
                        _this.data(i, plugin);
                    }
                });
            };
            $[i].support = {
                touch: "ontouchstart" in window || navigator.msMaxTouchPoints || false
            };
            var defaults;
            var input;
            var settings;
            var idealSelect;
        }
    }(jQuery);
    return false;
}));