define('lib/avalon/avalon', function (b, c, d) {
    !function (a, b) {
        'object' == typeof d && 'object' == typeof d.exports ? d.exports = a.document ? b(a, !0)  : function (a) {
            if (!a.document) throw new Error('Avalon requires a window with a document');
            return b(a)
        }
         : b(a)
    }('undefined' != typeof window ? window : this, function (b, c) {
        function d() {
            b.console && avalon.config.debug && Function.apply.call(console.log, console, arguments)
        }
        function e() {
        }
        function f(a, b) {
            'string' == typeof a && (a = a.match(Da) || []);
            for (var c = {
            }, d = void 0 !== b ? b : 1, e = 0, f = a.length; f > e; e++) c[a[e]] = d;
            return c
        }
        function g() {
            if (b.VBArray) {
                var a = document.documentMode;
                return a ? a : b.XMLHttpRequest ? 7 : 6
            }
            return 0
        }
        function h(a) {
            return Ga.test(Ja.call(a))
        }
        function i(a, b) {
            return a = Math.floor(a) || 0,
            0 > a ? Math.max(b + a, 0)  : Math.min(a, b)
        }
        function j(a) {
            if (!a) return !1;
            var b = a.length;
            if (b === b >>> 0) {
                var c = Ja.call(a).slice(8, - 1);
                if (/(?:regexp|string|function|window|global)$/i.test(c)) return !1;
                if ('Array' === c) return !0;
                try {
                    return {
                    }.propertyIsEnumerable.call(a, 'length') === !1 ? /^\s?function/.test(a.item || a.callee)  : !0
                } catch (d) {
                    return !a.window
                }
            }
            return !1
        }
        function k(a, b, c) {
            var d = 'for(var ' + a + 'i=0,n = this.length; i < n; i++){' + b.replace('_', '((i in this) && fn.call(scope,this[i],i,this))') + '}' + c;
            return Function('fn,scope', d)
        }
        function l(a, b) {
            try {
                for (; b = b.parentNode; ) if (b === a) return !0;
                return !1
            } catch (c) {
                return !1
            }
        }
        function m() {
            return (new XMLSerializer).serializeToString(this)
        }
        function o(a, b) {
            if (a && a.childNodes) for (var c, d = a.childNodes, e = 0; c = d[e++]; ) if (c.tagName) {
                var f = va.createElementNS(db, c.tagName.toLowerCase());
                Ka.forEach.call(c.attributes, function (a) {
                    f.setAttribute(a.name, a.value)
                }),
                o(c, f),
                b.appendChild(f)
            }
        }
        function p(a) {
            var b = {
            };
            for (var c in a) b[c] = a[c];
            var d = b.target = a.srcElement;
            if (0 === a.type.indexOf('key')) b.which = null != a.charCode ? a.charCode : a.keyCode;
             else if (fb.test(a.type)) {
                var e = d.ownerDocument || va,
                f = 'BackCompat' === e.compatMode ? e.body : e.documentElement;
                b.pageX = a.clientX + (f.scrollLeft >> 0) - (f.clientLeft >> 0),
                b.pageY = a.clientY + (f.scrollTop >> 0) - (f.clientTop >> 0),
                b.wheelDeltaY = b.wheelDelta,
                b.wheelDeltaX = 0
            }
            return b.timeStamp = new Date - 0,
            b.originalEvent = a,
            b.preventDefault = function () {
                a.returnValue = !1
            },
            b.stopPropagation = function () {
                a.cancelBubble = !0
            },
            b
        }
        function q(a) {
            for (var b in a) if (Ia.call(a, b)) {
                var c = a[b];
                'function' == typeof q.plugins[b] ? q.plugins[b](c)  : 'object' == typeof q[b] ? avalon.mix(q[b], c)  : q[b] = c
            }
            return this
        }
        function r(a) {
            return (a + '').replace(ob, '\\$&')
        }
        function s(a, b, c) {
            if (Ua(b) || b && b.nodeType) return !1;
            if ( - 1 !== c.indexOf(a)) return !1;
            if ( - 1 !== vb.indexOf(a)) return !1;
            var d = c.$special;
            return a && '$' === a.charAt(0) && !d[a] ? !1 : !0
        }
        function t(a, b, c, d) {
            switch (a.type) {
                case 0:
                    var e = a.get,
                    f = a.set;
                    if (Ua(f)) {
                        var g = d.$events,
                        h = g[b];
                        g[b] = [
                        ],
                        f.call(d, c),
                        g[b] = h
                    }
                    return e.call(d);
                case 1:
                    return c;
                case 2:
                    if (c !== d.$model[b]) {
                        var i = a.svmodel = w(d, b, c, a.valueType);
                        c = i.$model;
                        var j = wb[i.$id];
                        j && j()
                    }
                    return c
            }
        }
        function u(a, b, c) {
            if (Array.isArray(a)) {
                var d = a.concat();
                a.length = 0;
                var f = y(a);
                return f.pushArray(d),
                f
            }
            if ('number' == typeof a.nodeType) return a;
            if (a.$id && a.$events) return a;
            Array.isArray(a.$skipArray) || (a.$skipArray = [
            ]),
            a.$skipArray.$special = b || {
            };
            var g = {
            };
            c = c || {
            };
            var h = {
            },
            i = {
            },
            j = [
            ];
            for (var k in a) !function (b, d) {
                if (c[b] = d, s(b, d, a.$skipArray)) {
                    h[b] = [
                    ];
                    var f = avalon.type(d),
                    k = function (a) {
                        var b = k._name,
                        c = this,
                        d = c.$model,
                        e = d[b],
                        f = c.$events;
                        return arguments.length ? void (Ca || (1 === k.type || (a = t(k, b, a, c), k.type)) && (Bb(e, a) || (d[b] = a, f.$digest ? k.pedding || (k.pedding = !0, setTimeout(function () {
                            H(f[b]),
                            v(c, b, d[b], e),
                            k.pedding = !1
                        }))  : (H(f[b]), v(c, b, a, e)))))  : 0 === k.type ? (a = k.get.call(c), e !== a && (d[b] = a, f.$digest ? k.pedding || (k.pedding = !0, setTimeout(function () {
                            v(c, b, d[b], e),
                            k.pedding = !1
                        }))  : v(c, b, a, e)), a)  : (C(f[b]), k.svmodel || e)
                    };
                    'object' === f && Ua(d.get) && Object.keys(d).length <= 2 ? (k.set = d.set, k.get = d.get, k.type = 0, j.push(function () {
                        var a = {
                            evaluator: function () {
                                a.type = Math.random(),
                                a.element = null,
                                c[b] = k.get.call(g)
                            },
                            element: wa,
                            type: Math.random(),
                            handler: e,
                            args: [
                            ]
                        };
                        Ma[ua] = a,
                        k.call(g),
                        delete Ma[ua]
                    }))  : Ea.test(f) ? (k.type = 2, k.valueType = f, j.push(function () {
                        var a = u(d, 0, c[b]);
                        k.svmodel = a,
                        a.$events[za] = h[b]
                    }))  : k.type = 1,
                    k._name = b,
                    i[b] = k
                }
            }(k, a[k]);
            vb.forEach(function (b) {
                delete a[b],
                delete c[b]
            }),
            g = zb(g, Cb(i), a);
            for (var l in a) i[l] || (g[l] = a[l]);
            g.$id = Sa(),
            g.$model = c,
            g.$events = h;
            for (k in tb) {
                var m = tb[k];
                Na || (m = m.bind(g)),
                g[k] = m
            }
            return yb ? Object.defineProperty(g, 'hasOwnProperty', {
                value: function (a) {
                    return a in this.$model
                },
                writable: !1,
                enumerable: !1,
                configurable: !0
            })  : g.hasOwnProperty = function (a) {
                return a in g.$model
            },
            j.forEach(function (a) {
                a()
            }),
            g
        }
        function v(a, b, c, d) {
            a.$events && tb.$fire.call(a, b, c, d)
        }
        function w(a, b, c, d) {
            var e = a[b];
            if ('array' === d) return Array.isArray(c) && e !== c ? (e._.$unwatch(), e.clear(), e._.$watch(), e.pushArray(c.concat()), e)  : e;
            var f = a.$events[b],
            g = e.$events.$withProxyPool;
            g && (qa(g, 'with'), e.$events.$withProxyPool = null);
            var h = u(c);
            return h.$events[za] = f,
            wb[h.$id] = function (a) {
                for (; a = f.shift(); ) !function (a) {
                    avalon.nextTick(function () {
                        a.type && (a.rollback && a.rollback(), Xa[a.type](a, a.vmodels))
                    })
                }(a);
                delete wb[h.$id]
            },
            h
        }
        function x(a, b, c, d) {
            var e = b[c];
            return 4 !== arguments.length ? e.call(a)  : void e.call(a, d)
        }
        function y(a) {
            var b = [
            ];
            b.$id = Sa(),
            b.$model = a,
            b.$events = {
            },
            b.$events[za] = [
            ],
            b._ = u({
                length: a.length
            }),
            b._.$watch('length', function (a, c) {
                b.$fire('length', a, c)
            });
            for (var c in tb) b[c] = tb[c];
            return avalon.mix(b, Eb),
            b
        }
        function z(a, b, c, d, e, f, g) {
            for (var h = this.length, i = 2; --i; ) {
                switch (a) {
                    case 'add':
                        var j = this.$model.slice(b, b + c).map(function (a) {
                            return Ea.test(avalon.type(a)) ? a.$id ? a : u(a, 0, a)  : a
                        });
                        Db.apply(this, [
                            b,
                            0
                        ].concat(j)),
                        this._fire('add', b, c);
                        break;
                    case 'del':
                        var k = this._splice(b, c);
                        this._fire('del', b, c)
                }
                e && (a = e, b = f, c = g, i = 2, e = 0)
            }
            return this._fire('index', d),
            this.length !== h && (this._.length = this.length),
            k
        }
        function A(a, b) {
            for (var c = {
            }, d = 0, e = b.length; e > d; d++) {
                c[d] = a[d];
                var f = b[d];
                f in c ? (a[d] = c[f], delete c[f])  : a[d] = a[f]
            }
        }
        function B(a) {
            Ma[ua] = a,
            avalon.openComputedCollect = !0;
            var b = a.evaluator;
            if (b) try {
                var c = Fb.test(a.type) ? a : b.apply(0, a.args);
                a.handler(c, a.element, a)
            } catch (d) {
                delete a.evaluator;
                var e = a.element;
                if (3 === e.nodeType) {
                    var f = e.parentNode;
                    q.commentInterpolate ? f.replaceChild(va.createComment(a.value), e)  : e.data = jb + a.value + kb
                }
            }
            avalon.openComputedCollect = !1,
            delete Ma[ua]
        }
        function C(a) {
            var b = Ma[ua];
            a && b && avalon.Array.ensure(a, b) && b.element && D(b, a)
        }
        function D(a, b) {
            a.$uuid = a.$uuid || Sa(),
            b.$uuid = b.$uuid || Sa();
            var c = {
                data: a,
                list: b,
                $$uuid: a.$uuid + b.$uuid
            };
            Gb[c.$$uuid] || (Gb[c.$$uuid] = 1, Gb.push(c))
        }
        function E(a) {
            a.element = null,
            a.rollback && a.rollback();
            for (var b in a) a[b] = null
        }
        function F(a) {
            try {
                if (!a.parentNode) return !0
            } catch (b) {
                return !0
            }
            return a.msRetain ? 0 : 1 === a.nodeType ? 'number' == typeof a.sourceIndex ? 0 === a.sourceIndex : !Oa.contains(a)  : !avalon.contains(Oa, a)
        }
        function G() {
            for (var a, b = Gb.length, c = b, d = 0, e = [
            ], f = {
            }, g = {
            }; a = Gb[--b]; ) {
                var h = a.data,
                i = h.type;
                f[i] ? f[i]++ : (f[i] = 1, e.push(i))
            }
            var j = !1;
            if (e.forEach(function (a) {
                Ib[a] !== f[a] && (g[a] = 1, j = !0)
            }), b = c, j) for (; a = Gb[--b]; ) h = a.data,
            void 0 !== h.element && g[h.type] && F(h.element) && (d++, Gb.splice(b, 1), delete Gb[a.$$uuid], avalon.Array.remove(a.list, h), E(h), a.data = a.list = null);
            Ib = f,
            Hb = new Date
        }
        function H(a) {
            if (a && a.length) {
                new Date - Hb > 444 && 'object' == typeof a[0] && G();
                for (var b, c = La.call(arguments, 1), d = a.length; b = a[--d]; ) {
                    var f = b.element;
                    if (f && f.parentNode) if (b.$repeat) b.handler.apply(b, c);
                     else if ('on' !== b.type) {
                        var g = b.evaluator || e;
                        b.handler(g.apply(0, b.args || []), f, b)
                    }
                }
            }
        }
        function I(a) {
            var b = a.nodeName;
            return b.toLowerCase() === b && a.scopeName && '' === a.outerText
        }
        function J(a) {
            'url(#default#VML)' !== a.currentStyle.behavior && (a.style.behavior = 'url(#default#VML)', a.style.display = 'inline-block', a.style.zoom = 1)
        }
        function K(a, b, c) {
            var d = setTimeout(function () {
                var e = a.innerHTML;
                clearTimeout(d),
                e === c ? b()  : K(a, b, e)
            })
        }
        function L(a, b) {
            var c = a.getAttribute('avalonctrl') || b.$id;
            a.setAttribute('avalonctrl', c),
            b.$events.expr = a.tagName + '[avalonctrl="' + c + '"]'
        }
        function M(a, b) {
            for (var c, d = 0; c = a[d++]; ) c.vmodels = b,
            Xa[c.type](c, b),
            c.evaluator && c.element && 1 === c.element.nodeType && c.element.removeAttribute(c.name);
            a.length = 0
        }
        function N(a, b) {
            return a.priority - b.priority
        }
        function O(a, b) {
            for (var c, e, f = cc ? cc(a)  : avalon.slice(a.attributes), g = [
            ], h = {
            }, i = 0; e = f[i++]; ) if (e.specified && (c = e.name.match(Tb))) {
                var j = c[1],
                k = c[2] || '',
                l = e.value,
                m = e.name;
                if (h[m] = l, Vb[j] ? (k = j, j = 'on')  : Wb[j] && (d('ms-' + j + '已经被废弃,请使用ms-attr-*代替'), 'enabled' === j && (j = 'disabled', l = '!(' + l + ')'), k = j, j = 'attr', a.removeAttribute(m), m = 'ms-attr-' + k, a.setAttribute(m, l), c = [
                    m
                ], h[m] = l), 'function' == typeof Xa[j]) {
                    var n = {
                        type: j,
                        param: k,
                        element: a,
                        name: c[0],
                        value: l,
                        priority: j in Ub ? Ub[j] : 10 * j.charCodeAt(0) + (Number(k) || 0)
                    };
                    if ('html' === j || 'text' === j) {
                        var o = T(l);
                        avalon.mix(n, o),
                        n.filters = n.filters.replace(dc, function () {
                            return n.type = 'html',
                            n.group = 1,
                            ''
                        })
                    }
                    'ms-if-loop' === m && (n.priority += 100),
                    b.length && (g.push(n), 'widget' === j && (a.msData = a.msData || h))
                }
            }
            g.sort(N),
            h['ms-attr-checked'] && h['ms-duplex'] && d('warning!一个元素上不能同时定义ms-attr-checked与ms-duplex');
            var p = !0;
            for (i = 0; n = g[i]; i++) {
                if (j = n.type, Xb.test(j)) return M(g.slice(0, i + 1), b);
                p && (p = !Yb.test(j))
            }
            M(g, b),
            p && !Qb[a.tagName] && nb.test(a.innerHTML.replace(fc, '<').replace(gc, '>')) && (Sb && Sb(a), P(a, b))
        }
        function P(a, b) {
            for (var c = a.firstChild; c; ) {
                var d = c.nextSibling;
                R(c, c.nodeType, b),
                c = d
            }
        }
        function Q(a, b) {
            for (var c, d = 0; c = a[d++]; ) R(c, c.nodeType, b)
        }
        function R(a, b, c) {
            1 === b ? S(a, c)  : 3 === b && lb.test(a.data) ? V(a, c)  : q.commentInterpolate && 8 === b && !lb.test(a.nodeValue) && V(a, c)
        }
        function S(a, b, c) {
            var e = a.getAttribute('ms-skip');
            if (!a.getAttributeNode) return d('warning ' + a.tagName + ' no getAttributeNode method');
            var f = a.getAttributeNode('ms-important'),
            g = a.getAttributeNode('ms-controller');
            if ('string' != typeof e) {
                if (c = f || g) {
                    var h = avalon.vmodels[c.value];
                    if (!h) return;
                    b = c === f ? [
                        h
                    ] : [
                        h
                    ].concat(b);
                    var i = c.name;
                    a.removeAttribute(i),
                    avalon(a).removeClass(i),
                    L(a, h)
                }
                O(a, b)
            }
        }
        function T(a) {
            if (a.indexOf('|') > 0) {
                var b = a.replace(rstringLiteral, function (a) {
                    return Array(a.length + 1).join('1')
                }),
                c = b.replace(ec, 'ᄢ㍄').indexOf('|');
                if (c > - 1) return {
                    filters: a.slice(c),
                    value: a.slice(0, c),
                    expr: !0
                }
            }
            return {
                value: a,
                filters: '',
                expr: !0
            }
        }
        function U(a) {
            for (var b, c, d = [
            ], e = 0; ; ) {
                if (c = a.indexOf(jb, e), - 1 === c) break;
                if (b = a.slice(e, c), b && d.push({
                    value: b,
                    filters: '',
                    expr: !1
                }), e = c + jb.length, c = a.indexOf(kb, e), - 1 === c) break;
                b = a.slice(e, c),
                b && d.push(T(b)),
                e = c + kb.length
            }
            return b = a.slice(e),
            b && d.push({
                value: b,
                expr: !1,
                filters: ''
            }),
            d
        }
        function V(a, b) {
            var c = [
            ];
            if (8 === a.nodeType) var d = T(a.nodeValue),
            e = [
                d
            ];
             else e = U(a.data);
            if (e.length) {
                for (var f = 0; d = e[f++]; ) {
                    var g = va.createTextNode(d.value);
                    d.expr && (d.type = 'text', d.element = g, d.filters = d.filters.replace(dc, function () {
                        return d.type = 'html',
                        d.group = 1,
                        ''
                    }), c.push(d)),
                    Pa.appendChild(g)
                }
                a.parentNode.replaceChild(Pa, a),
                c.length && M(c, b)
            }
        }
        function W(a) {
            return a.replace(/([a-z\d])([A-Z]+)/g, '$1-$2').toLowerCase()
        }
        function X(a) {
            return a.indexOf('-') < 0 && a.indexOf('_') < 0 ? a : a.replace(/[-_][^-_]/g, function (a) {
                return a.charAt(1).toUpperCase()
            })
        }
        function Y(a) {
            if (!('classList' in a)) {
                a.classList = {
                    node: a
                };
                for (var b in hc) a.classList[b.slice(1)] = hc[b]
            }
            return a.classList
        }
        function Z(a) {
            try {
                if ('object' == typeof a) return a;
                a = 'true' === a ? !0 : 'false' === a ? !1 : 'null' === a ? null : + a + '' === a ? + a : ic.test(a) ? avalon.parseJSON(a)  : a
            } catch (b) {
            }
            return a
        }
        function $(a) {
            return a.window && a.document ? a : 9 === a.nodeType ? a.defaultView || a.parentWindow : !1
        }
        function _(a, b) {
            if (a.offsetWidth <= 0) {
                if (xc.test(nc['@:get'](a, 'display'))) {
                    var c = {
                        node: a
                    };
                    for (var d in wc) c[d] = a.style[d],
                    a.style[d] = wc[d];
                    b.push(c)
                }
                var e = a.parentNode;
                e && 1 === e.nodeType && _(e, b)
            }
        }
        function aa(a) {
            var b = a.tagName.toLowerCase();
            return 'input' === b && /checkbox|radio/.test(a.type) ? 'checked' : b
        }
        function ba(a, b, c, d) {
            for (var e, f = [
            ], g = ' = ' + c + '.', h = a.length; e = a[--h]; ) b.hasOwnProperty(e) && (f.push(e + g + e), d.vars.push(e), 'duplex' === d.type && (a.get = c + '.' + e), a.splice(h, 1));
            return f
        }
        function ca(a) {
            for (var b = [
            ], c = {
            }, d = 0; d < a.length; d++) {
                var e = a[d],
                f = e && 'string' == typeof e.$id ? e.$id : e;
                c[f] || (c[f] = b.push(e))
            }
            return b
        }
        function da(a, b) {
            return b = b.replace(Nc, '').replace(Oc, function () {
                return '],|'
            }).replace(Pc, function (a, b) {
                return '[' + Bc(b)
            }).replace(Qc, function () {
                return '"],["'
            }).replace(Rc, function () {
                return '",'
            }) + ']',
            'return avalon.filters.$filter(' + a + ', ' + b + ')'
        }
        function ea(a, b, c) {
            var f = c.type,
            g = c.filters || '',
            h = b.map(function (a) {
                return String(a.$id).replace(Mc, '$1')
            }) + a + f + g,
            i = Jc(a).concat(),
            j = [
            ],
            k = [
            ],
            l = [
            ],
            m = '';
            b = ca(b),
            c.vars = [
            ];
            for (var n = 0, o = b.length; o > n; n++) if (i.length) {
                var p = 'vm' + ua + '_' + n;
                k.push(p),
                l.push(b[n]),
                j.push.apply(j, ba(i, b[n], p, c))
            }
            if (j.length || 'duplex' !== f) {
                'duplex' !== f && (a.indexOf('||') > - 1 || a.indexOf('&&') > - 1) && c.vars.forEach(function (b) {
                    var c = new RegExp('\\b' + b + '(?:\\.\\w+|\\[\\w+\\])+', 'ig');
                    a = a.replace(c, function (c) {
                        var d = c.charAt(b.length),
                        e = Ta ? a.slice(arguments[1] + c.length)  : RegExp.rightContext,
                        f = /^\s*\(/.test(e);
                        if ('.' === d || '[' === d || f) {
                            var g = 'var' + String(Math.random()).replace(/^0\./, '');
                            if (f) {
                                var h = c.split('.');
                                if (h.length > 2) {
                                    var i = h.pop();
                                    return j.push(g + ' = ' + h.join('.')),
                                    g + '.' + i
                                }
                                return c
                            }
                            return j.push(g + ' = ' + c),
                            g
                        }
                        return c
                    })
                }),
                c.args = l;
                var q = Kc.get(h);
                if (q) return void (c.evaluator = q);
                if (m = j.join(', '), m && (m = 'var ' + m), /\S/.test(g)) {
                    if (!/text|html/.test(c.type)) throw Error('ms-' + c.type + '不支持过滤器');
                    a = '\nvar ret' + ua + ' = ' + a + ';\r\n',
                    a += da('ret' + ua, g)
                } else {
                    if ('duplex' === f) {
                        var r = '\'use strict\';\nreturn function(vvv){\n\t' + m + ';\n\tif(!arguments.length){\n\t\treturn ' + a + '\n\t}\n\t' + (Lc.test(a) ? a : i.get) + '= vvv;\n} ';
                        try {
                            q = Function.apply(e, k.concat(r)),
                            c.evaluator = Kc.put(h, q)
                        } catch (s) {
                            d('debug: parse error,' + s.message)
                        }
                        return
                    }
                    if ('on' === f) {
                        - 1 === a.indexOf('(') ? a += '.call(this, $event)' : a = a.replace('(', '.call(this,'),
                        k.push('$event'),
                        a = '\nreturn ' + a + ';';
                        var t = a.lastIndexOf('\nreturn'),
                        u = a.slice(0, t),
                        v = a.slice(t);
                        a = u + '\n' + v
                    } else a = '\nreturn ' + a + ';'
                }
                try {
                    q = Function.apply(e, k.concat('\'use strict\';\n' + m + a)),
                    c.evaluator = Kc.put(h, q)
                } catch (s) {
                    d('debug: parse error,' + s.message)
                } finally {
                    i = textBuffer = k = null
                }
            }
        }
        function fa(a, b, c, d, e) {
            Array.isArray(d) && (a = d.map(function (a) {
                return a.expr ? '(' + a.value + ')' : Bc(a.value)
            }).join(' + ')),
            ea(a, b, c),
            c.evaluator && !e && (c.handler = Ya[c.handlerName || c.type], B(c))
        }
        function ga(a) {
            return null == a ? '' : a
        }
        function ha(a, b, c, d) {
            return b.param.replace(/\w+/g, function (d) {
                var e = avalon.duplexHooks[d];
                e && 'function' == typeof e[c] && (a = e[c](a, b))
            }),
            a
        }
        function ia() {
            for (var a = ad.length - 1; a >= 0; a--) {
                var b = ad[a];
                b() === !1 && ad.splice(a, 1)
            }
            ad.length || clearInterval(_c)
        }
        function ja(a, b, c, d) {
            var e = a.template.cloneNode(!0),
            f = avalon.slice(e.childNodes);
            c.$stamp && e.insertBefore(c.$stamp, e.firstChild),
            b.appendChild(e);
            var g = [
                c
            ].concat(a.vmodels),
            h = {
                nodes: f,
                vmodels: g
            };
            d.push(h)
        }
        function ka(a, b) {
            var c = a.proxies[b];
            return c ? c.$stamp : a.element
        }
        function la(a, b, c) {
            for (; ; ) {
                var d = b.previousSibling;
                if (!d) break;
                if (d.parentNode.removeChild(d), c && c.call(d), d === a) break
            }
        }
        function ma(a) {
            var b = {
                $host: [
                ],
                $outer: {
                },
                $stamp: 1,
                $index: 0,
                $first: !1,
                $last: !1,
                $remove: avalon.noop
            };
            b[a] = {
                get: function () {
                    return this.$host[this.$index]
                },
                set: function (a) {
                    this.$host.set(this.$index, a)
                }
            };
            var c = {
                $last: 1,
                $first: 1,
                $index: 1
            },
            d = u(b, c),
            e = d.$events;
            return e[a] = e.$first = e.$last = e.$index,
            d.$id = Sa('$proxy$each'),
            d
        }
        function na(a, b) {
            for (var c, d = b.param || 'el', e = 0, f = dd.length; f > e; e++) {
                var g = dd[e];
                g && g.hasOwnProperty(d) && (c = g, dd.splice(e, 1))
            }
            c || (c = ma(d));
            var h = b.$repeat,
            i = h.length - 1;
            return c.$index = a,
            c.$first = 0 === a,
            c.$last = a === i,
            c.$host = h,
            c.$outer = b.$outer,
            c.$stamp = b.clone.cloneNode(!1),
            c.$remove = function () {
                return h.removeAt(c.$index)
            },
            c
        }
        function oa() {
            var a = u({
                $key: '',
                $outer: {
                },
                $host: {
                },
                $val: {
                    get: function () {
                        return this.$host[this.$key]
                    },
                    set: function (a) {
                        this.$host[this.$key] = a
                    }
                }
            }, {
                $val: 1
            });
            return a.$id = Sa('$proxy$with'),
            a
        }
        function pa(a, b) {
            var c = ed.pop();
            c || (c = oa());
            var d = b.$repeat;
            return c.$key = a,
            c.$host = d,
            c.$outer = b.$outer,
            d.$events ? c.$events.$val = d.$events[a] : c.$events = {
            },
            c
        }
        function qa(a, b) {
            var c = 'each' === b ? dd : ed;
            avalon.each(a, function (a, b) {
                if (b.$events) {
                    for (var d in b.$events) Array.isArray(b.$events[d]) && (b.$events[d].forEach(function (a) {
                        'object' == typeof a && E(a)
                    }), b.$events[d].length = 0);
                    b.$host = b.$outer = {
                    },
                    c.unshift(b) > q.maxRepeatSize && c.pop()
                }
            }),
            'each' === b && (a.length = 0)
        }
        function ra(a, b) {
            var c = '_' + a;
            if (!ra[c]) {
                var d = va.createElement(a);
                Oa.appendChild(d),
                b = Na ? getComputedStyle(d, null).display : d.currentStyle.display,
                Oa.removeChild(d),
                ra[c] = b
            }
            return ra[c]
        }
        function sa(a, b, c, d) {
            a = (a + '').replace(/[^0-9+\-Ee.]/g, '');
            var e = isFinite( + a) ? + a : 0,
            f = isFinite( + b) ? Math.abs(b)  : 3,
            g = d || ',',
            h = c || '.',
            i = '',
            j = function (a, b) {
                var c = Math.pow(10, b);
                return '' + (Math.round(a * c) / c).toFixed(b)
            };
            return i = (f ? j(e, f)  : '' + Math.round(e)).split('.'),
            i[0].length > 3 && (i[0] = i[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, g)),
            (i[1] || '').length < f && (i[1] = i[1] || '', i[1] += new Array(f - i[1].length + 1).join('0')),
            i.join(h)
        }
        function ta() {
            try {
                Oa.doScroll('left'),
                qd()
            } catch (a) {
                setTimeout(ta)
            }
        }
        var ua = new Date - 0,
        va = b.document,
        wa = va.getElementsByTagName('head') [0],
        xa = wa.insertBefore(document.createElement('avalon'), wa.firstChild);
        xa.innerHTML = 'X<style id=\'avalonStyle\'>.avalonHide{ display: none!important }</style>',
        xa.setAttribute('ms-skip', '1'),
        xa.className = 'avalonHide';
        var ya = /\[native code\]/,
        za = '$' + ua,
        Aa = b.require,
        Ba = b.define,
        Ca = !1,
        Da = /[^, ]+/g,
        Ea = /^(?:object|array)$/,
        Fa = /^\[object SVG\w*Element\]$/,
        Ga = /^\[object (?:Window|DOMWindow|global)\]$/,
        Ha = Object.prototype,
        Ia = Ha.hasOwnProperty,
        Ja = Ha.toString,
        Ka = Array.prototype,
        La = Ka.slice,
        Ma = {
        },
        Na = b.dispatchEvent,
        Oa = va.documentElement,
        Pa = va.createDocumentFragment(),
        Qa = va.createElement('div'),
        Ra = {
        };
        'Boolean Number String Function Array Date RegExp Object Error'.replace(Da, function (a) {
            Ra['[object ' + a + ']'] = a.toLowerCase()
        });
        var Sa = function (a) {
            return a = a || 'avalon',
            (a + Math.random() + Math.random()).replace(/0\./g, '')
        },
        Ta = g();
        avalon = function (a) {
            return new avalon.init(a)
        },
        avalon.nextTick = new function () {
            function a() {
                for (var a = f.length, b = 0; a > b; b++) f[b]();
                f = f.slice(a)
            }
            var c = b.setImmediate,
            d = b.MutationObserver,
            e = Na && b.postMessage;
            if (c) return c.bind(b);
            var f = [
            ];
            if (d) {
                var g = document.createTextNode('avalon');
                return new d(a).observe(g, {
                    characterData: !0
                }),
                function (a) {
                    f.push(a),
                    g.data = Math.random()
                }
            }
            return e ? (b.addEventListener('message', function (c) {
                var d = c.source;
                d !== b && null !== d || 'process-tick' !== c.data || (c.stopPropagation(), a())
            }), function (a) {
                f.push(a),
                b.postMessage('process-tick', '*')
            })  : function (a) {
                setTimeout(a, 0)
            }
        },
        avalon.init = function (a) {
            this[0] = this.element = a
        },
        avalon.fn = avalon.prototype = avalon.init.prototype,
        avalon.type = function (a) {
            return null == a ? String(a)  : 'object' == typeof a || 'function' == typeof a ? Ra[Ja.call(a)] || 'object' : typeof a
        };
        var Ua = 'object' == typeof alert ? function (a) {
            try {
                return /^\s*\bfunction\b/.test(a + '')
            } catch (b) {
                return !1
            }
        }
         : function (a) {
            return '[object Function]' === Ja.call(a)
        };
        avalon.isFunction = Ua,
        avalon.isWindow = function (a) {
            return a ? a == a.document && a.document != a : !1
        },
        h(b) && (avalon.isWindow = h);
        var Va;
        for (Va in avalon({
        })) break;
        var Wa = '0' !== Va;
        avalon.isPlainObject = function (a, b) {
            if (!a || 'object' !== avalon.type(a) || a.nodeType || avalon.isWindow(a)) return !1;
            try {
                if (a.constructor && !Ia.call(a, 'constructor') && !Ia.call(a.constructor.prototype, 'isPrototypeOf')) return !1
            } catch (c) {
                return !1
            }
            if (Wa) for (b in a) return Ia.call(a, b);
            for (b in a);
            return void 0 === b || Ia.call(a, b)
        },
        ya.test(Object.getPrototypeOf) && (avalon.isPlainObject = function (a) {
            return '[object Object]' === Ja.call(a) && Object.getPrototypeOf(a) === Ha
        }),
        avalon.mix = avalon.fn.mix = function () {
            var a,
            b,
            c,
            d,
            e,
            f,
            g = arguments[0] || {
            },
            h = 1,
            i = arguments.length,
            j = !1;
            for ('boolean' == typeof g && (j = g, g = arguments[1] || {
            }, h++), 'object' == typeof g || Ua(g) || (g = {
            }), h === i && (g = this, h--); i > h; h++) if (null != (a = arguments[h])) for (b in a) {
                c = g[b];
                try {
                    d = a[b]
                } catch (k) {
                    continue
                }
                g !== d && (j && d && (avalon.isPlainObject(d) || (e = Array.isArray(d))) ? (e ? (e = !1, f = c && Array.isArray(c) ? c : [
                ])  : f = c && avalon.isPlainObject(c) ? c : {
                }, g[b] = avalon.mix(j, f, d))  : void 0 !== d && (g[b] = d))
            }
            return g
        },
        avalon.mix({
            rword: Da,
            subscribers: za,
            version: 1.4,
            ui: {
            },
            log: d,
            slice: Na ? function (a, b, c) {
                return La.call(a, b, c)
            }
             : function (a, b, c) {
                var d = [
                ],
                e = a.length;
                if (void 0 === c && (c = e), 'number' == typeof c && isFinite(c)) {
                    b = i(b, e),
                    c = i(c, e);
                    for (var f = b; c > f; ++f) d[f - b] = a[f]
                }
                return d
            },
            noop: e,
            error: function (a, b) {
                throw (b || Error) (a)
            },
            oneObject: f,
            range: function (a, b, c) {
                c || (c = 1),
                null == b && (b = a || 0, a = 0);
                for (var d = - 1, e = Math.max(0, Math.ceil((b - a) / c)), f = new Array(e); ++d < e; ) f[d] = a,
                a += c;
                return f
            },
            eventHooks: {
            },
            bind: function (a, b, c, d) {
                var e = avalon.eventHooks,
                f = e[b];
                'object' == typeof f && (b = f.type, f.deel && (c = f.deel(a, b, c, d)));
                var g = Na ? c : function (b) {
                    c.call(a, p(b))
                };
                return Na ? a.addEventListener(b, g, !!d)  : a.attachEvent('on' + b, g),
                g
            },
            unbind: function (a, b, c, d) {
                var f = avalon.eventHooks,
                g = f[b],
                h = c || e;
                'object' == typeof g && (b = g.type, g.deel && (c = g.deel(a, b, c, !1))),
                Na ? a.removeEventListener(b, h, !!d)  : a.detachEvent('on' + b, h)
            },
            css: function (a, b, c) {
                a instanceof avalon && (a = a[0]);
                var d,
                e = /[_-]/.test(b) ? X(b)  : b;
                if (b = avalon.cssName(e) || e, void 0 === c || 'boolean' == typeof c) {
                    d = nc[e + ':get'] || nc['@:get'],
                    'background' === b && (b = 'backgroundColor');
                    var f = d(a, b);
                    return c === !0 ? parseFloat(f) || 0 : f
                }
                if ('' === c) a.style[b] = '';
                 else {
                    if (null == c || c !== c) return;
                    isFinite(c) && !avalon.cssNumber[e] && (c += 'px'),
                    d = nc[e + ':set'] || nc['@:set'],
                    d(a, b, c)
                }
            },
            each: function (a, b) {
                if (a) {
                    var c = 0;
                    if (j(a)) for (var d = a.length; d > c && b(c, a[c]) !== !1; c++);
                     else for (c in a) if (a.hasOwnProperty(c) && b(c, a[c]) === !1) break
                }
            },
            getWidgetData: function (a, b) {
                var c = avalon(a).data(),
                d = {
                };
                for (var e in c) 0 === e.indexOf(b) && (d[e.replace(b, '').replace(/\w/, function (a) {
                    return a.toLowerCase()
                })] = c[e]);
                return d
            },
            Array: {
                ensure: function (a, b) {
                    return - 1 === a.indexOf(b) ? a.push(b)  : void 0
                },
                removeAt: function (a, b) {
                    return !!a.splice(b, 1).length
                },
                remove: function (a, b) {
                    var c = a.indexOf(b);
                    return ~c ? avalon.Array.removeAt(a, c)  : !1
                }
            }
        });
        var Xa = avalon.bindingHandlers = {
        },
        Ya = avalon.bindingExecutors = {
        },
        Za = new function () {
            function a(a) {
                this.size = 0,
                this.limit = a,
                this.head = this.tail = void 0,
                this._keymap = {
                }
            }
            var b = a.prototype;
            return b.put = function (a, b) {
                var c = {
                    key: a,
                    value: b
                };
                return this._keymap[a] = c,
                this.tail ? (this.tail.newer = c, c.older = this.tail)  : this.head = c,
                this.tail = c,
                this.size === this.limit ? this.shift()  : this.size++,
                b
            },
            b.shift = function () {
                var a = this.head;
                a && (this.head = this.head.newer, this.head.older = a.newer = a.older = this._keymap[a.key] = void 0)
            },
            b.get = function (a) {
                var b = this._keymap[a];
                if (void 0 !== b) return b === this.tail ? b.value : (b.newer && (b === this.head && (this.head = b.newer), b.newer.older = b.older), b.older && (b.older.newer = b.newer), b.newer = void 0, b.older = this.tail, this.tail && (this.tail.newer = b), this.tail = b, b.value)
            },
            a
        };
        if (!'司徒正美'.trim) {
            var $a = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
            String.prototype.trim = function () {
                return this.replace($a, '')
            }
        }
        var _a = !{
            toString: null
        }.propertyIsEnumerable('toString'),
        ab = function () {
        }.propertyIsEnumerable('prototype'),
        bb = [
            'toString',
            'toLocaleString',
            'valueOf',
            'hasOwnProperty',
            'isPrototypeOf',
            'propertyIsEnumerable',
            'constructor'
        ],
        cb = bb.length;
        if (Object.keys || (Object.keys = function (a) {
            var b = [
            ],
            c = ab && 'function' == typeof a;
            if ('string' == typeof a || a && a.callee) for (var d = 0; d < a.length; ++d) b.push(String(d));
             else for (var e in a) c && 'prototype' === e || !Ia.call(a, e) || b.push(String(e));
            if (_a) for (var f = a.constructor, g = f && f.prototype === a, h = 0; cb > h; h++) {
                var i = bb[h];
                g && 'constructor' === i || !Ia.call(a, i) || b.push(i)
            }
            return b
        }), Array.isArray || (Array.isArray = function (a) {
            return '[object Array]' === Ja.call(a)
        }), e.bind || (Function.prototype.bind = function (a) {
            if (arguments.length < 2 && void 0 === a) return this;
            var b = this,
            c = arguments;
            return function () {
                var d,
                e = [
                ];
                for (d = 1; d < c.length; d++) e.push(c[d]);
                for (d = 0; d < arguments.length; d++) e.push(arguments[d]);
                return b.apply(a, e)
            }
        }), ya.test([].map) || avalon.mix(Ka, {
            indexOf: function (a, b) {
                var c = this.length,
                d = ~~b;
                for (0 > d && (d += c); c > d; d++) if (this[d] === a) return d;
                return - 1
            },
            lastIndexOf: function (a, b) {
                var c = this.length,
                d = null == b ? c - 1 : b;
                for (0 > d && (d = Math.max(0, c + d)); d >= 0; d--) if (this[d] === a) return d;
                return - 1
            },
            forEach: k('', '_', ''),
            filter: k('r=[],j=0,', 'if(_)r[j++]=this[i]', 'return r'),
            map: k('r=[],', 'r[i]=_', 'return r'),
            some: k('', 'if(_)return true', 'return false'),
            every: k('', 'if(!_)return false', 'return true')
        }), avalon.contains = l, Oa.contains || (Node.prototype.contains = function (a) {
            return !!(16 & this.compareDocumentPosition(a))
        }), va.contains || (va.contains = function (a) {
            return l(va, a)
        }), b.SVGElement) {
            var db = 'http://www.w3.org/2000/svg',
            eb = va.createElementNS(db, 'svg');
            eb.innerHTML = '<circle cx="50" cy="50" r="40" fill="red" />',
            Fa.test(eb.firstChild) || Object.defineProperties(SVGElement.prototype, {
                outerHTML: {
                    enumerable: !0,
                    configurable: !0,
                    get: m,
                    set: function (a) {
                        var b = this.tagName.toLowerCase(),
                        c = this.parentNode,
                        d = avalon.parseHTML(a);
                        if ('svg' === b) c.insertBefore(d, this);
                         else {
                            var e = va.createDocumentFragment();
                            o(d, e),
                            c.insertBefore(e, this)
                        }
                        c.removeChild(this)
                    }
                },
                innerHTML: {
                    enumerable: !0,
                    configurable: !0,
                    get: function () {
                        var a = this.outerHTML,
                        b = new RegExp('<' + this.nodeName + '\\b(?:(["\'])[^"]*?(\\1)|[^>])*>', 'i'),
                        c = new RegExp('</' + this.nodeName + '>$', 'i');
                        return a.replace(b, '').replace(c, '')
                    },
                    set: function (a) {
                        if (avalon.clearHTML) {
                            avalon.clearHTML(this);
                            var b = avalon.parseHTML(a);
                            o(b, this)
                        }
                    }
                }
            })
        }
        !Oa.outerHTML && b.HTMLElement && HTMLElement.prototype.__defineGetter__('outerHTML', m);
        var fb = /^(?:mouse|contextmenu|drag)|click/,
        gb = avalon.eventHooks;
        if ('onmouseenter' in Oa || avalon.each({
            mouseenter: 'mouseover',
            mouseleave: 'mouseout'
        }, function (a, b) {
            gb[a] = {
                type: b,
                deel: function (b, c, d) {
                    return function (c) {
                        var e = c.relatedTarget;
                        return e && (e === b || 16 & b.compareDocumentPosition(e)) ? void 0 : (delete c.type, c.type = a, d.call(b, c))
                    }
                }
            }
        }), avalon.each({
            AnimationEvent: 'animationend',
            WebKitAnimationEvent: 'webkitAnimationEnd'
        }, function (a, c) {
            b[a] && !gb.animationend && (gb.animationend = {
                type: c
            })
        }), 'oninput' in va.createElement('input') || (gb.input = {
            type: 'propertychange',
            deel: function (a, b, c) {
                return function (b) {
                    return 'value' === b.propertyName ? (b.type = 'input', c.call(a, b))  : void 0
                }
            }
        }), void 0 === va.onmousewheel) {
            var hb = void 0 !== va.onwheel ? 'wheel' : 'DOMMouseScroll',
            ib = 'wheel' === hb ? 'deltaY' : 'detail';
            gb.mousewheel = {
                type: hb,
                deel: function (a, b, c) {
                    return function (b) {
                        b.wheelDeltaY = b.wheelDelta = b[ib] > 0 ? - 120 : 120,
                        b.wheelDeltaX = 0,
                        Object.defineProperty && Object.defineProperty(b, 'type', {
                            value: 'mousewheel'
                        }),
                        c.call(a, b)
                    }
                }
            }
        }
        var jb,
        kb,
        lb,
        mb,
        nb,
        ob = /[-.*+?^${}()|[\]\/\\]/g,
        pb = e,
        qb = {
            loader: function (a) {
                b.define = a ? pb.define : Ba,
                b.require = a ? pb : Aa
            },
            interpolate: function (a) {
                if (jb = a[0], kb = a[1], jb === kb) throw new SyntaxError('openTag!==closeTag');
                if (a + '' == '<!--,-->') q.commentInterpolate = !0;
                 else {
                    var b = jb + 'test' + kb;
                    if (Qa.innerHTML = b, Qa.innerHTML !== b && Qa.innerHTML.indexOf('&lt;') > - 1) throw new SyntaxError('此定界符不合法');
                    Qa.innerHTML = ''
                }
                var c = r(jb),
                d = r(kb);
                lb = new RegExp(c + '(.*?)' + d),
                mb = new RegExp(c + '(.*?)' + d, 'g'),
                nb = new RegExp(c + '.*?' + d + '|\\sms-')
            }
        };
        q.debug = !0,
        q.plugins = qb,
        q.plugins.interpolate(['{{',
        '}}']),
        q.paths = {
        },
        q.shim = {
        },
        q.maxRepeatSize = 100,
        avalon.config = q;
        var rb = /(\w+)\[(avalonctrl)="(\S+)"\]/,
        sb = va.querySelectorAll ? function (a) {
            return va.querySelectorAll(a)
        }
         : function (a) {
            for (var b, c = a.match(rb), d = va.getElementsByTagName(c[1]), e = [
            ], f = 0; b = d[f++]; ) b.getAttribute(c[2]) === c[3] && e.push(b);
            return e
        },
        tb = {
            $watch: function (a, b) {
                if ('function' == typeof b) {
                    var c = this.$events[a];
                    c ? c.push(b)  : this.$events[a] = [
                        b
                    ]
                } else this.$events = this.$watch.backup;
                return this
            },
            $unwatch: function (a, b) {
                var c = arguments.length;
                if (0 === c) this.$watch.backup = this.$events,
                this.$events = {
                };
                 else if (1 === c) this.$events[a] = [
                ];
                 else for (var d = this.$events[a] || [], e = d.length; ~--e < 0; ) if (d[e] === b) return d.splice(e, 1);
                return this
            },
            $fire: function (a) {
                var b,
                c,
                d,
                e;
                /^(\w+)!(\S+)$/.test(a) && (b = RegExp.$1, a = RegExp.$2);
                var f = this.$events;
                if (f) {
                    var g = La.call(arguments, 1),
                    h = [
                        a
                    ].concat(g);
                    if ('all' === b) for (c in avalon.vmodels) d = avalon.vmodels[c],
                    d !== this && d.$fire.apply(d, h);
                     else if ('up' === b || 'down' === b) {
                        var i = f.expr ? sb(f.expr)  : [
                        ];
                        if (0 === i.length) return;
                        for (c in avalon.vmodels) if (d = avalon.vmodels[c], d !== this && d.$events.expr) {
                            var j = sb(d.$events.expr);
                            if (0 === j.length) continue;
                            Array.prototype.forEach.call(j, function (a) {
                                Array.prototype.forEach.call(i, function (c) {
                                    var e = 'down' === b ? c.contains(a)  : a.contains(c);
                                    e && (a._avalon = d)
                                })
                            })
                        }
                        var k = va.getElementsByTagName('*'),
                        l = [
                        ];
                        for (Array.prototype.forEach.call(k, function (a) {
                            a._avalon && (l.push(a._avalon), a._avalon = '', a.removeAttribute('_avalon'))
                        }), 'up' === b && l.reverse(), c = 0; (e = l[c++]) && e.$fire.apply(e, h) !== !1; );
                    } else {
                        var m = f[a] || [],
                        n = f.$all || [];
                        for (c = 0; e = m[c++]; ) Ua(e) && e.apply(this, g);
                        for (c = 0; e = n[c++]; ) Ua(e) && e.apply(this, arguments)
                    }
                }
            }
        },
        ub = avalon.vmodels = {
        };
        avalon.define = function (a, b) {
            var c = a.$id || a;
            if (c || d('warning: vm必须指定$id'), ub[c] && d('warning: ' + c + ' 已经存在于avalon.vmodels中'), 'object' == typeof a) var f = u(a);
             else {
                var g = {
                    $watch: e
                };
                b(g),
                f = u(g),
                Ca = !0,
                b(f),
                Ca = !1
            }
            return f.$id = c,
            ub[c] = f
        };
        var vb = String('$id,$watch,$unwatch,$fire,$events,$model,$skipArray').match(Da),
        wb = {
        },
        xb = Object.defineProperty,
        yb = !0;
        try {
            xb({
            }, '_', {
                value: 'x'
            });
            var zb = Object.defineProperties
        } catch (Ab) {
            yb = !1
        }
        var Bb = Object.is || function (a, b) {
            return 0 === a && 0 === b ? 1 / a === 1 / b : a !== a ? b !== b : a === b
        },
        Cb = Na ? function (a) {
            var b = {
            };
            for (var c in a) b[c] = {
                get: a[c],
                set: a[c],
                enumerable: !0,
                configurable: !0
            };
            return b
        }
         : function (a) {
            return a
        };
        yb || ('__defineGetter__' in avalon && (xb = function (a, b, c) {
            return 'value' in c && (a[b] = c.value),
            'get' in c && a.__defineGetter__(b, c.get),
            'set' in c && a.__defineSetter__(b, c.set),
            a
        }, zb = function (a, b) {
            for (var c in b) b.hasOwnProperty(c) && xb(a, c, b[c]);
            return a
        }), Ta && (b.execScript(['Function parseVB(code)',
        '\tExecuteGlobal(code)',
        'End Function',
        'Dim VBClassBodies',
        'Set VBClassBodies=CreateObject("Scripting.Dictionary")',
        'Function findOrDefineVBClass(name,body)',
        '\tDim found',
        '\tfound=""',
        '\tFor Each key in VBClassBodies',
        '\t\tIf body=VBClassBodies.Item(key) Then',
        '\t\t\tfound=key',
        '\t\t\tExit For',
        '\t\tEnd If',
        '\tnext',
        '\tIf found="" Then',
        '\t\tparseVB("Class " + name + body)',
        '\t\tVBClassBodies.Add name, body',
        '\t\tfound=name',
        '\tEnd If',
        '\tfindOrDefineVBClass=found',
        'End Function'].join('\n'), 'VBScript'), zb = function (a, c, d) {
            var e = 'VBClass' + setTimeout('1'),
            f = [
            ];
            f.push('\r\n\tPrivate [__data__], [__proxy__]', '\tPublic Default Function [__const__](d, p)', '\t\tSet [__data__] = d: set [__proxy__] = p', '\t\tSet [__const__] = Me', '\tEnd Function');
            for (a in d) c.hasOwnProperty(a) || f.push('\tPublic [' + a + ']');
            vb.forEach(function (a) {
                c.hasOwnProperty(a) || f.push('\tPublic [' + a + ']')
            }),
            f.push('\tPublic [hasOwnProperty]');
            for (a in c) f.push('\tPublic Property Let [' + a + '](val' + ua + ')', '\t\tCall [__proxy__](Me,[__data__], "' + a + '", val' + ua + ')', '\tEnd Property', '\tPublic Property Set [' + a + '](val' + ua + ')', '\t\tCall [__proxy__](Me,[__data__], "' + a + '", val' + ua + ')', '\tEnd Property', '\tPublic Property Get [' + a + ']', '\tOn Error Resume Next', '\t\tSet[' + a + '] = [__proxy__](Me,[__data__],"' + a + '")', '\tIf Err.Number <> 0 Then', '\t\t[' + a + '] = [__proxy__](Me,[__data__],"' + a + '")', '\tEnd If', '\tOn Error Goto 0', '\tEnd Property');
            f.push('End Class');
            var g = f.join('\r\n'),
            h = b.findOrDefineVBClass(e, g);
            h === e && b.parseVB(['Function ' + e + 'Factory(a, b)',
            '\tDim o',
            '\tSet o = (New ' + e + ')(a, b)',
            '\tSet ' + e + 'Factory = o',
            'End Function'].join('\r\n'));
            var i = b[h + 'Factory'](c, x);
            return i
        }));
        var Db = Ka.splice,
        Eb = {
            _splice: Db,
            _fire: function (a, b, c) {
                H(this.$events[za], a, b, c)
            },
            size: function () {
                return this._.length
            },
            pushArray: function (a) {
                var b = a.length,
                c = this.length;
                return b && (Ka.push.apply(this.$model, a), z.call(this, 'add', c, b, c)),
                b + c
            },
            push: function () {
                var a,
                b = [
                ],
                c = arguments.length;
                for (a = 0; c > a; a++) b[a] = arguments[a];
                return this.pushArray(arguments)
            },
            unshift: function () {
                var a = arguments.length,
                b = this.length;
                return a && (Ka.unshift.apply(this.$model, arguments), z.call(this, 'add', 0, a, 0)),
                a + b
            },
            shift: function () {
                if (this.length) {
                    var a = this.$model.shift();
                    return z.call(this, 'del', 0, 1, 0),
                    a
                }
            },
            pop: function () {
                var a = this.length;
                if (a) {
                    var b = this.$model.pop();
                    return z.call(this, 'del', a - 1, 1, Math.max(0, a - 2)),
                    b
                }
            },
            splice: function (a) {
                var b,
                c = arguments.length,
                d = [
                ],
                e = Db.apply(this.$model, arguments);
                return e.length && (d.push('del', a, e.length, 0), b = !0),
                c > 2 && (b ? d.splice(3, 1, 0, 'add', a, c - 2)  : d.push('add', a, c - 2, 0), b = !0),
                b ? z.apply(this, d)  : [
                ]
            },
            contains: function (a) {
                return - 1 !== this.indexOf(a)
            },
            remove: function (a) {
                return this.removeAt(this.indexOf(a))
            },
            removeAt: function (a) {
                return a >= 0 ? (this.$model.splice(a, 1), z.call(this, 'del', a, 1, 0))  : [
                ]
            },
            clear: function () {
                return this.$model.length = this.length = this._.length = 0,
                this._fire('clear', 0),
                this
            },
            removeAll: function (a) {
                if (Array.isArray(a)) a.forEach(function (a) {
                    this.remove(a)
                }, this);
                 else if ('function' == typeof a) for (var b = this.length - 1; b >= 0; b--) {
                    var c = this[b];
                    a(c, b) && this.removeAt(b)
                } else this.clear()
            },
            ensure: function (a) {
                return this.contains(a) || this.push(a),
                this
            },
            set: function (a, b) {
                if (a >= 0) {
                    var c = avalon.type(b);
                    b && b.$model && (b = b.$model);
                    var d = this[a];
                    if ('object' === c) for (var e in b) d.hasOwnProperty(e) && (d[e] = b[e]);
                     else 'array' === c ? d.clear().push.apply(d, b)  : d !== b && (this[a] = b, this.$model[a] = b, this._fire('set', a, b))
                }
                return this;
            }
        };
        'sort,reverse'.replace(Da, function (a) {
            Eb[a] = function () {
                var b,
                c = this.$model,
                d = c.concat(),
                e = Math.random(),
                f = [
                ];
                Ka[a].apply(c, arguments);
                for (var g = 0, h = d.length; h > g; g++) {
                    var i = c[g],
                    j = d[g];
                    if (Bb(i, j)) f.push(g);
                     else {
                        var k = d.indexOf(i);
                        f.push(k),
                        d[k] = e,
                        b = !0
                    }
                }
                return b && (A(this, f), this._fire('move', f), this._fire('index', 0)),
                this
            }
        });
        var Fb = /^(duplex|on)$/,
        Gb = avalon.$$subscribers = [
        ],
        Hb = new Date,
        Ib = {
        },
        Jb = {
            area: [
                1,
                '<map>'
            ],
            param: [
                1,
                '<object>'
            ],
            col: [
                2,
                '<table><tbody></tbody><colgroup>',
                '</table>'
            ],
            legend: [
                1,
                '<fieldset>'
            ],
            option: [
                1,
                '<select multiple=\'multiple\'>'
            ],
            thead: [
                1,
                '<table>',
                '</table>'
            ],
            tr: [
                2,
                '<table><tbody>',
                '</tbody></table>'
            ],
            th: [
                3,
                '<table><tbody><tr>',
                '</tr></tbody></table>'
            ],
            td: [
                3,
                '<table><tbody><tr>'
            ],
            g: [
                1,
                '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1">',
                '</svg>'
            ],
            _default: Na ? [
                0,
                ''
            ] : [
                1,
                'X<div>'
            ]
        };
        Jb.optgroup = Jb.option,
        Jb.tbody = Jb.tfoot = Jb.colgroup = Jb.caption = Jb.thead,
        String('circle,defs,ellipse,image,line,path,polygon,polyline,rect,symbol,text,use').replace(Da, function (a) {
            Jb[a] = Jb.g
        });
        var Kb = /<([\w:]+)/,
        Lb = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,
        Mb = Na ? /[^\d\D]/ : /(<(?:script|link|style|meta|noscript))/gi,
        Nb = f(['',
        'text/javascript',
        'text/ecmascript',
        'application/ecmascript',
        'application/javascript']),
        Ob = /<(?:tb|td|tf|th|tr|col|opt|leg|cap|area)/,
        Pb = va.createElement('script');
        avalon.parseHTML = function (a) {
            if ('string' != typeof a) return va.createDocumentFragment();
            a = a.replace(Lb, '<$1></$2>').trim();
            var b,
            c,
            d = (Kb.exec(a) || ['',
            '']) [1].toLowerCase(),
            e = Jb[d] || Jb._default,
            f = Pa.cloneNode(!1),
            g = Qa;
            Na || (a = a.replace(Mb, '<br class=msNoScope>$1')),
            g.innerHTML = e[1] + a + (e[2] || '');
            var h = g.getElementsByTagName('script');
            if (h.length) for (var i, j = 0; i = h[j++]; ) Nb[i.type] && (c = Pb.cloneNode(!1), Ka.forEach.call(i.attributes, function (a) {
                a && a.specified && (c[a.name] = a.value, c.setAttribute(a.name, a.value))
            }), c.text = i.text, i.parentNode.replaceChild(c, i));
            for (j = e[0]; j--; g = g.lastChild);
            if (!Na) {
                for (h = g.getElementsByTagName('br'), n = h.length; i = h[--n]; ) 'msNoScope' === i.className && i.parentNode.removeChild(i);
                for (h = g.all, j = 0; i = h[j++]; ) I(i) && J(i)
            }
            for (; b = g.firstChild; ) f.appendChild(b);
            return f
        },
        avalon.innerHTML = function (a, b) {
            if (!Na && !Mb.test(b) && !Ob.test(b)) try {
                return void (a.innerHTML = b)
            } catch (c) {
            }
            var d = this.parseHTML(b);
            this.clearHTML(a).appendChild(d)
        },
        avalon.clearHTML = function (a) {
            for (a.textContent = ''; a.firstChild; ) a.removeChild(a.firstChild);
            return a
        },
        avalon.scan = function (a, b, c) {
            a = a || Oa;
            var d = b ? [
            ].concat(b)  : [
            ];
            S(a, d)
        };
        var Qb = f('area,base,basefont,br,col,command,embed,hr,img,input,link,meta,param,source,track,wbr,noscript,script,style,textarea'.toUpperCase()),
        Rb = function (a, b, c) {
            var d = a.getAttribute(b);
            if (d) for (var e, f = 0; e = c[f++]; ) if (e.hasOwnProperty(d) && 'function' == typeof e[d]) return e[d]
        },
        Sb = Ta && b.MutationObserver ? function (a) {
            for (var b, c = a.firstChild; c; ) {
                var d = c.nextSibling;
                3 === c.nodeType ? b ? (b.nodeValue += c.nodeValue, a.removeChild(c))  : b = c : b = null,
                c = d
            }
        }
         : 0,
        Tb = /ms-(\w+)-?(.*)/,
        Ub = {
            'if': 10,
            repeat: 90,
            data: 100,
            widget: 110,
            each: 1400,
            'with': 1500,
            duplex: 2000,
            on: 3000
        },
        Vb = f('animationend,blur,change,input,click,dblclick,focus,keydown,keypress,keyup,mousedown,mouseenter,mouseleave,mousemove,mouseout,mouseover,mouseup,scan,scroll,submit'),
        Wb = f('value,title,alt,checked,selected,disabled,readonly,enabled'),
        Xb = /^if|widget|repeat$/,
        Yb = /^each|with|html|include$/;
        if (!'1'[0]) var Zb = new Za(512),
        $b = /\s+(ms-[^=\s]+)(?:=("[^"]*"|'[^']*'|[^\s>]+))?/g,
        _b = /^['"]/,
        ac = /<\w+\b(?:(["'])[^"]*?(\1)|[^>])*>/i,
        bc = /&amp;/g,
        cc = function (a) {
            var b = a.outerHTML;
            if ('</' === b.slice(0, 2) || !b.trim()) return [];
            var c,
            d,
            e,
            f = b.match(ac) [0],
            g = [
            ],
            h = Zb.get(f);
            if (h) return h;
            for (; d = $b.exec(f); ) {
                e = d[2],
                e && (e = (_b.test(e) ? e.slice(1, - 1)  : e).replace(bc, '&'));
                var i = d[1].toLowerCase();
                c = i.match(Tb);
                var j = {
                    name: i,
                    specified: !0,
                    value: e || ''
                };
                g.push(j)
            }
            return Zb.put(f, g)
        };
        var dc = /\|\s*html\s*/,
        ec = /\|\|/g,
        fc = /&lt;/g,
        gc = /&gt;/g;
        rstringLiteral = /(['"])(\\\1|.)+?\1/g;
        var hc = {
            _toString: function () {
                var a = this.node,
                b = a.className,
                c = 'string' == typeof b ? b : b.baseVal;
                return c.split(/\s+/).join(' ')
            },
            _contains: function (a) {
                return (' ' + this + ' ').indexOf(' ' + a + ' ') > - 1
            },
            _add: function (a) {
                this.contains(a) || this._set(this + ' ' + a)
            },
            _remove: function (a) {
                this._set((' ' + this + ' ').replace(' ' + a + ' ', ' '))
            },
            __set: function (a) {
                a = a.trim();
                var b = this.node;
                Fa.test(b) ? b.setAttribute('class', a)  : b.className = a
            }
        };
        'add,remove'.replace(Da, function (a) {
            avalon.fn[a + 'Class'] = function (b) {
                var c = this[0];
                return b && 'string' == typeof b && c && 1 === c.nodeType && b.replace(/\S+/g, function (b) {
                    Y(c) [a](b)
                }),
                this
            }
        }),
        avalon.fn.mix({
            hasClass: function (a) {
                var b = this[0] || {
                };
                return 1 === b.nodeType && Y(b).contains(a)
            },
            toggleClass: function (a, b) {
                for (var c, d = 0, e = a.split(/\s+/), f = 'boolean' == typeof b; c = e[d++]; ) {
                    var g = f ? b : !this.hasClass(c);
                    this[g ? 'addClass' : 'removeClass'](c)
                }
                return this
            },
            attr: function (a, b) {
                return 2 === arguments.length ? (this[0].setAttribute(a, b), this)  : this[0].getAttribute(a)
            },
            data: function (a, b) {
                switch (a = 'data-' + W(a || ''), arguments.length) {
                    case 2:
                        return this.attr(a, b),
                        this;
                    case 1:
                        var c = this.attr(a);
                        return Z(c);
                    case 0:
                        var d = {
                        };
                        return Ka.forEach.call(this[0].attributes, function (b) {
                            b && (a = b.name, a.indexOf('data-') || (a = X(a.slice(5)), d[a] = Z(b.value)))
                        }),
                        d
                }
            },
            removeData: function (a) {
                return a = 'data-' + W(a),
                this[0].removeAttribute(a),
                this
            },
            css: function (a, b) {
                if (avalon.isPlainObject(a)) for (var c in a) avalon.css(this, c, a[c]);
                 else var d = avalon.css(this, a, b);
                return void 0 !== d ? d : this
            },
            position: function () {
                var a,
                b,
                c = this[0],
                d = {
                    top: 0,
                    left: 0
                };
                if (c) return 'fixed' === this.css('position') ? b = c.getBoundingClientRect()  : (a = this.offsetParent(), b = this.offset(), 'HTML' !== a[0].tagName && (d = a.offset()), d.top += avalon.css(a[0], 'borderTopWidth', !0), d.left += avalon.css(a[0], 'borderLeftWidth', !0)),
                {
                    top: b.top - d.top - avalon.css(c, 'marginTop', !0),
                    left: b.left - d.left - avalon.css(c, 'marginLeft', !0)
                }
            },
            offsetParent: function () {
                for (var a = this[0].offsetParent; a && 'static' === avalon.css(a, 'position'); ) a = a.offsetParent;
                return avalon(a || Oa)
            },
            bind: function (a, b, c) {
                return this[0] ? avalon.bind(this[0], a, b, c)  : void 0
            },
            unbind: function (a, b, c) {
                return this[0] && avalon.unbind(this[0], a, b, c),
                this
            },
            val: function (a) {
                var b = this[0];
                if (b && 1 === b.nodeType) {
                    var c = 0 === arguments.length,
                    d = c ? ':get' : ':set',
                    e = zc[aa(b) + d];
                    if (e) var f = e(b, a);
                     else {
                        if (c) return (b.value || '').replace(/\r/g, '');
                        b.value = a
                    }
                }
                return c ? f : this
            }
        }); var ic = /(?:\{[\s\S]*\}|\[[\s\S]*\])$/, jc = /^[\],:{}\s]*$/, kc = /(?:^|:|,)(?:\s*\[)+/g, lc = /\\(?:["\\\/bfnrt]|u[\da-fA-F]{4})/g, mc = /"[^"\\\r\n]*"|true|false|null|-?(?:\d+\.|)\d+(?:[eE][+-]?\d+|)/g; avalon.parseJSON = b.JSON ? JSON.parse : function (a) {
            if ('string' == typeof a) {
                if (a = a.trim(), a && jc.test(a.replace(lc, '@').replace(mc, ']').replace(kc, ''))) return new Function('return ' + a) ();
                avalon.error('Invalid JSON: ' + a)
            }
            return a
        }, avalon.each({
            scrollLeft: 'pageXOffset',
            scrollTop: 'pageYOffset'
        }, function (a, b) {
            avalon.fn[a] = function (c) {
                var d = this[0] || {
                },
                e = $(d),
                f = 'scrollTop' === a;
                return arguments.length ? void (e ? e.scrollTo(f ? avalon(e).scrollLeft()  : c, f ? c : avalon(e).scrollTop())  : d[a] = c)  : e ? b in e ? e[b] : Oa[a] : d[a]
            }
        }); var nc = avalon.cssHooks = {
        }, oc = [
            '',
            '-webkit-',
            '-o-',
            '-moz-',
            '-ms-'
        ], pc = {
            'float': Na ? 'cssFloat' : 'styleFloat'
        }; if (avalon.cssNumber = f('columnCount,order,fillOpacity,fontWeight,lineHeight,opacity,orphans,widows,zIndex,zoom'), avalon.cssName = function (a, b, c) {
            if (pc[a]) return pc[a];
            b = b || Oa.style;
            for (var d = 0, e = oc.length; e > d; d++) if (c = X(oc[d] + a), c in b) return pc[a] = c;
            return null
        }, nc['@:set'] = function (a, b, c) {
            try {
                a.style[b] = c
            } catch (d) {
            }
        }, b.getComputedStyle) nc['@:get'] = function (a, b) {
            if (!a || !a.style) throw new Error('getComputedStyle要求传入一个节点 ' + a);
            var c,
            d = getComputedStyle(a, null);
            return d && (c = 'filter' === b ? d.getPropertyValue(b)  : d[b], '' === c && (c = a.style[b])),
            c
        }, nc['opacity:get'] = function (a) {
            var b = nc['@:get'](a, 'opacity');
            return '' === b ? '1' : b
        };  else {
            var qc = /^-?(?:\d*\.)?\d+(?!px)[^\d\s]+$/i,
            rc = /^(top|right|bottom|left)$/,
            sc = /alpha\([^)]*\)/i,
            tc = !!b.XDomainRequest,
            uc = 'DXImageTransform.Microsoft.Alpha',
            vc = {
                thin: tc ? '1px' : '2px',
                medium: tc ? '3px' : '4px',
                thick: tc ? '5px' : '6px'
            };
            nc['@:get'] = function (a, b) {
                var c = a.currentStyle,
                d = c[b];
                if (qc.test(d) && !rc.test(d)) {
                    var e = a.style,
                    f = e.left,
                    g = a.runtimeStyle.left;
                    a.runtimeStyle.left = c.left,
                    e.left = 'fontSize' === b ? '1em' : d || 0,
                    d = e.pixelLeft + 'px',
                    e.left = f,
                    a.runtimeStyle.left = g
                }
                return 'medium' === d && (b = b.replace('Width', 'Style'), 'none' === c[b] && (d = '0px')),
                '' === d ? 'auto' : vc[d] || d
            },
            nc['opacity:set'] = function (a, b, c) {
                var d = a.style,
                e = isFinite(c) && 1 >= c ? 'alpha(opacity=' + 100 * c + ')' : '',
                f = d.filter || '';
                d.zoom = 1,
                d.filter = (sc.test(f) ? f.replace(sc, e)  : f + ' ' + e).trim(),
                d.filter || d.removeAttribute('filter')
            },
            nc['opacity:get'] = function (a) {
                var b = a.filters.alpha || a.filters[uc],
                c = b && b.enabled ? b.opacity : 100;
                return c / 100 + ''
            }
        }
        'top,left'.replace(Da, function (a) {
            nc[a + ':get'] = function (b) {
                var c = nc['@:get'](b, a);
                return /px$/.test(c) ? c : avalon(b).position() [a] + 'px'
            }
        }); var wc = {
            position: 'absolute',
            visibility: 'hidden',
            display: 'block'
        }, xc = /^(none|table(?!-c[ea]).+)/; 'Width,Height'.replace(Da, function (a) {
            var b = a.toLowerCase(),
            c = 'client' + a,
            d = 'scroll' + a,
            e = 'offset' + a;
            nc[b + ':get'] = function (b, c, d) {
                var f = - 4;
                'number' == typeof d && (f = d),
                c = 'Width' === a ? [
                    'Left',
                    'Right'
                ] : [
                    'Top',
                    'Bottom'
                ];
                var g = b[e];
                return 2 === f ? g + avalon.css(b, 'margin' + c[0], !0) + avalon.css(b, 'margin' + c[1], !0)  : (0 > f && (g = g - avalon.css(b, 'border' + c[0] + 'Width', !0) - avalon.css(b, 'border' + c[1] + 'Width', !0)), - 4 === f && (g = g - avalon.css(b, 'padding' + c[0], !0) - avalon.css(b, 'padding' + c[1], !0)), g)
            },
            nc[b + '&get'] = function (a) {
                var c = [
                ];
                _(a, c);
                for (var d, e = nc[b + ':get'](a), f = 0; d = c[f++]; ) {
                    a = d.node;
                    for (var g in d) 'string' == typeof d[g] && (a.style[g] = d[g])
                }
                return e
            },
            avalon.fn[b] = function (f) {
                var g = this[0];
                if (0 === arguments.length) {
                    if (g.setTimeout) return g['inner' + a] || g.document.documentElement[c];
                    if (9 === g.nodeType) {
                        var h = g.documentElement;
                        return Math.max(g.body[d], h[d], g.body[e], h[e], h[c])
                    }
                    return nc[b + '&get'](g)
                }
                return this.css(b, f)
            },
            avalon.fn['inner' + a] = function () {
                return nc[b + ':get'](this[0], void 0, - 2)
            },
            avalon.fn['outer' + a] = function (a) {
                return nc[b + ':get'](this[0], void 0, a === !0 ? 2 : 0)
            }
        }), avalon.fn.offset = function () {
            var a = this[0],
            b = {
                left: 0,
                top: 0
            };
            if (!a || !a.tagName || !a.ownerDocument) return b;
            var c = a.ownerDocument,
            d = c.body,
            e = c.documentElement,
            f = c.defaultView || c.parentWindow;
            if (!avalon.contains(e, a)) return b;
            a.getBoundingClientRect && (b = a.getBoundingClientRect());
            var g = e.clientTop || d.clientTop,
            h = e.clientLeft || d.clientLeft,
            i = Math.max(f.pageYOffset || 0, e.scrollTop, d.scrollTop),
            j = Math.max(f.pageXOffset || 0, e.scrollLeft, d.scrollLeft);
            return {
                top: b.top + i - g,
                left: b.left + j - h
            }
        }; var yc = /^<option(?:\s+\w+(?:\s*=\s*(?:"[^"]*"|'[^']*'|[^\s>]+))?)*\s+value[\s=]/i, zc = {
            'option:get': Ta ? function (a) {
                return yc.test(a.outerHTML) ? a.value : a.text.trim()
            }
             : function (a) {
                return a.value
            },
            'select:get': function (a, b) {
                for (var c, d = a.options, e = a.selectedIndex, f = zc['option:get'], g = 'select-one' === a.type || 0 > e, h = g ? null : [
                ], i = g ? e + 1 : d.length, j = 0 > e ? i : g ? e : 0; i > j; j++) if (c = d[j], (c.selected || j === e) && !c.disabled) {
                    if (b = f(c), g) return b;
                    h.push(b)
                }
                return h
            },
            'select:set': function (a, b, c) {
                b = [
                ].concat(b);
                for (var d, e = zc['option:get'], f = 0; d = a.options[f++]; ) (d.selected = b.indexOf(e(d)) > - 1) && (c = !0);
                c || (a.selectedIndex = - 1)
            }
        }, Ac = {
            '': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"': '\\"',
            '\\': '\\\\'
        }, Bc = b.JSON && JSON.stringify || function (a) {
            return '"' + a.replace(/[\\\"\x00-\x1f]/g, function (a) {
                var b = Ac[a];
                return 'string' == typeof b ? b : '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice( - 4)
            }) + '"'
        }, Cc = 'break,case,catch,continue,debugger,default,delete,do,else,false,finally,for,function,if,in,instanceof,new,null,return,switch,this,throw,true,try,typeof,var,void,while,with,abstract,boolean,byte,char,class,const,double,enum,export,extends,final,float,goto,implements,import,int,interface,long,native,package,private,protected,public,short,static,super,synchronized,throws,transient,volatile,arguments,let,yield,undefined', Dc = /\/\*[\w\W]*?\*\/|\/\/[^\n]*\n|\/\/[^\n]*$|"(?:[^"\\]|\\[\w\W])*"|'(?:[^'\\]|\\[\w\W])*'|[\s\t\n]*\.[\s\t\n]*[$\w\.]+/g, Ec = /[^\w$]+/g, Fc = new RegExp(['\\b' + Cc.replace(/,/g, '\\b|\\b') + '\\b'].join('|'), 'g'), Gc = /\b\d[^,]*/g, Hc = /^,+|,+$/g, Ic = new Za(512), Jc = function (a) {
            var b = ',' + a.trim(),
            c = Ic.get(b);
            if (c) return c;
            var d = a.replace(Dc, '').replace(Ec, ',').replace(Fc, '').replace(Gc, '').replace(Hc, '').split(/^$|,+/);
            return Ic.put(b, ca(d))
        }, Kc = new Za(128), Lc = /\w\[.*\]|\w\.\w/, Mc = /(\$proxy\$[a-z]+)\d+$/, Nc = /\)\s*$/, Oc = /\)\s*\|/g, Pc = /\|\s*([$\w]+)/g, Qc = /"\s*\["/g, Rc = /"\s*\(/g; avalon.parseExprProxy = fa; var Sc = 'autofocus,autoplay,async,allowTransparency,checked,controls,declare,disabled,defer,defaultChecked,defaultSelectedcontentEditable,isMap,loop,multiple,noHref,noResize,noShade,open,readOnly,selected', Tc = {
        }; Sc.replace(Da, function (a) {
            Tc[a.toLowerCase()] = a
        }); var Uc = {
            'accept-charset': 'acceptCharset',
            'char': 'ch',
            charoff: 'chOff',
            'class': 'className',
            'for': 'htmlFor',
            'http-equiv': 'httpEquiv'
        }, Vc = 'accessKey,bgColor,cellPadding,cellSpacing,codeBase,codeType,colSpan,dateTime,defaultValue,frameBorder,longDesc,maxLength,marginWidth,marginHeight,rowSpan,tabIndex,useMap,vSpace,valueType,vAlign'; Vc.replace(Da, function (a) {
            Uc[a.toLowerCase()] = a
        }); var Wc = /<noscript.*?>(?:[\s\S]+?)<\/noscript>/gim, Xc = /<noscript.*?>([\s\S]+?)<\/noscript>/im, Yc = function () {
            return new (b.XMLHttpRequest || ActiveXObject) ('Microsoft.XMLHTTP')
        }, Zc = avalon.templateCache = {
        }; Xa.attr = function (a, b) {
            var c = a.value.trim(),
            d = !0;
            if (c.indexOf(jb) > - 1 && c.indexOf(kb) > 2 && (d = !1, lb.test(c) && '' === RegExp.rightContext && '' === RegExp.leftContext && (d = !0, c = RegExp.$1)), 'include' === a.type) {
                var e = a.element;
                a.includeRendered = Rb(e, 'data-include-rendered', b),
                a.includeLoaded = Rb(e, 'data-include-loaded', b);
                var f = a.includeReplaced = !!avalon(e).data('includeReplace');
                a.startInclude = va.createComment('ms-include'),
                a.endInclude = va.createComment('ms-include-end'),
                f ? (a.element = a.startInclude, e.parentNode.insertBefore(a.startInclude, e), e.parentNode.insertBefore(a.endInclude, e.nextSibling))  : (e.insertBefore(a.startInclude, e.firstChild), e.appendChild(a.endInclude))
            }
            a.handlerName = 'attr',
            fa(c, b, a, d ? 0 : U(a.value))
        }, Ya.attr = function (a, c, d) {
            var e = d.type,
            f = d.param;
            if ('css' === e) avalon(c).css(f, a);
             else if ('attr' === e) {
                if (Tc[f]) {
                    var g = Tc[f];
                    if ('boolean' == typeof c[g]) return c[g] = !!a
                }
                var h = a === !1 || null === a || void 0 === a;
                if (!Na && Uc[f] && (f = Uc[f]), h) return c.removeAttribute(f);
                var i = Fa.test(c) ? !1 : va.namespaces && I(c) ? !0 : f in c.cloneNode(!1);
                i ? c[f] = a : c.setAttribute(f, a)
            } else if ('include' === e && a) {
                var j = d.vmodels,
                k = d.includeRendered,
                l = d.includeLoaded,
                m = d.includeReplaced,
                n = m ? c.parentNode : c,
                o = function (a) {
                    for (l && (a = l.apply(n, [
                        a
                    ].concat(j))), k && K(n, function () {
                        k.call(n)
                    }, 0 / 0); ; ) {
                        var b = d.startInclude.nextSibling;
                        if (!b || b === d.endInclude) break;
                        n.removeChild(b)
                    }
                    var c = avalon.parseHTML(a),
                    e = avalon.slice(c.childNodes);
                    n.insertBefore(c, d.endInclude),
                    Q(e, j)
                };
                if ('src' === d.param) if (Zc[a]) avalon.nextTick(function () {
                    o(Zc[a])
                });
                 else {
                    var p = Yc();
                    p.onreadystatechange = function () {
                        if (4 === p.readyState) {
                            var b = p.status;
                            (b >= 200 && 300 > b || 304 === b || 1223 === b) && o(Zc[a] = p.responseText)
                        }
                    },
                    p.open('GET', a, !0),
                    'withCredentials' in p && (p.withCredentials = !0),
                    p.setRequestHeader('X-Requested-With', 'XMLHttpRequest'),
                    p.send(null)
                } else {
                    var q = a && 1 === a.nodeType ? a : va.getElementById(a);
                    if (q) {
                        if ('NOSCRIPT' === q.tagName && !q.innerHTML && !q.fixIE78) {
                            p = Yc(),
                            p.open('GET', location, !1),
                            p.send(null);
                            for (var r = va.getElementsByTagName('noscript'), s = (p.responseText || '').match(Wc) || [], t = s.length, u = 0; t > u; u++) {
                                var v = r[u];
                                v && (v.style.display = 'none', v.fixIE78 = (s[u].match(Xc) || ['',
                                '&nbsp;']) [1])
                            }
                        }
                        avalon.nextTick(function () {
                            o(q.fixIE78 || q.value || q.innerText || q.innerHTML)
                        })
                    }
                }
            } else if (Oa.hasAttribute || 'string' != typeof a || 'src' !== e && 'href' !== e || (a = a.replace(/&amp;/g, '&')), c[e] = a, b.chrome && 'EMBED' === c.tagName) {
                var w = c.parentNode,
                x = document.createComment('ms-src');
                w.replaceChild(x, c),
                w.replaceChild(c, x)
            }
        }, 'title,alt,src,value,css,include,href'.replace(Da, function (a) {
            Xa[a] = Xa.attr
        }), Xa['class'] = function (a, b) {
            var c,
            e = a.param,
            f = a.value;
            if (a.handlerName = 'class', !e || isFinite(e)) {
                a.param = '';
                var g = f.replace(mb, function (a) {
                    return a.replace(/./g, '0')
                }),
                h = g.indexOf(':');
                if ( - 1 === h) var i = f;
                 else {
                    if (i = f.slice(0, h), c = f.slice(h + 1), ea(c, b, a), !a.evaluator) return d('debug: ms-class \'' + (c || '').trim() + '\' 不存在于VM中'),
                    !1;
                    a._evaluator = a.evaluator,
                    a._args = a.args
                }
                var j = lb.test(i);
                j || (a.immobileClass = i),
                fa('', b, a, j ? U(i)  : 0)
            } else a.immobileClass = a.oldStyle = a.param,
            fa(f, b, a)
        }, Ya['class'] = function (a, b, c) {
            var d = avalon(b),
            e = c.type;
            if ('class' === e && c.oldStyle) d.toggleClass(c.oldStyle, !!a);
             else switch (c.toggleClass = c._evaluator ? !!c._evaluator.apply(b, c._args)  : !0, c.newClass = c.immobileClass || a, c.oldClass && c.newClass !== c.oldClass && d.removeClass(c.oldClass), c.oldClass = c.newClass, e) {
                case 'class':
                    d.toggleClass(c.newClass, c.toggleClass);
                    break;
                case 'hover':
                case 'active':
                    if (!c.hasBindEvent) {
                        var f = 'mouseenter',
                        g = 'mouseleave';
                        if ('active' === e) {
                            b.tabIndex = b.tabIndex || - 1,
                            f = 'mousedown',
                            g = 'mouseup';
                            var h = d.bind('mouseleave', function () {
                                c.toggleClass && d.removeClass(c.newClass)
                            })
                        }
                        var i = d.bind(f, function () {
                            c.toggleClass && d.addClass(c.newClass)
                        }),
                        j = d.bind(g, function () {
                            c.toggleClass && d.removeClass(c.newClass)
                        });
                        c.rollback = function () {
                            d.unbind('mouseleave', h),
                            d.unbind(f, i),
                            d.unbind(g, j)
                        },
                        c.hasBindEvent = !0
                    }
            }
        },
        'hover,active'.replace(Da, function (a) {
            Xa[a] = Xa['class']
        }),
        Ya.data = function (a, b, c) {
            var d = 'data-' + c.param;
            a && 'object' == typeof a ? b[d] = a : b.setAttribute(d, String(a))
        };
        var $c = Xa.duplex = function (a, b) {
            var c,
            g = a.element;
            if (fa(a.value, b, a, 0, 1), a.changed = Rb(g, 'data-duplex-changed', b) || e, a.evaluator && a.args) {
                var h = [
                ],
                i = f('string,number,boolean,checked');
                'radio' === g.type && '' === a.param && (a.param = 'checked'),
                g.msData && (g.msData['ms-duplex'] = a.value),
                a.param.replace(/\w+/g, function (b) {
                    /^(checkbox|radio)$/.test(g.type) && /^(radio|checked)$/.test(b) && ('radio' === b && d('ms-duplex-radio已经更名为ms-duplex-checked'), b = 'checked', a.isChecked = !0),
                    'bool' === b ? (b = 'boolean', d('ms-duplex-bool已经更名为ms-duplex-boolean'))  : 'text' === b && (b = 'string', d('ms-duplex-text已经更名为ms-duplex-string')),
                    i[b] && (c = !0),
                    avalon.Array.ensure(h, b)
                }),
                c || h.push('string'),
                a.param = h.join('-'),
                a.bound = function (b, c) {
                    g.addEventListener ? g.addEventListener(b, c, !1)  : g.attachEvent('on' + b, c);
                    var d = a.rollback;
                    a.rollback = function () {
                        g.avalonSetter = null,
                        avalon.unbind(g, b, c),
                        d && d()
                    }
                };
                for (var j in avalon.vmodels) {
                    var k = avalon.vmodels[j];
                    k.$fire('avalon-ms-duplex-init', a)
                }
                var l = a.pipe || (a.pipe = ha);
                l(null, a, 'init');
                var m = g.tagName;
                $c[m] && $c[m](g, a.evaluator.apply(null, a.args), a)
            }
        };
        avalon.duplexHooks = {
            checked: {
                get: function (a, b) {
                    return !b.element.oldValue
                }
            },
            string: {
                get: function (a) {
                    return a
                },
                set: ga
            },
            'boolean': {
                get: function (a) {
                    return 'true' === a
                },
                set: ga
            },
            number: {
                get: function (a) {
                    return isFinite(a) ? parseFloat(a) || 0 : a
                },
                set: ga
            }
        };
        var _c,
        ad = [
        ];
        avalon.tick = function (a) {
            1 === ad.push(a) && (_c = setInterval(ia, 60))
        };
        var bd = e;
        !new function () {
            function a(a) {
                avalon.contains(Oa, this) && (b[this.tagName].call(this, a), !this.msFocus && this.avalonSetter && this.avalonSetter())
            }
            try {
                var b = {
                },
                c = HTMLInputElement.prototype,
                d = HTMLTextAreaElement.prototype,
                e = HTMLInputElement.prototype;
                Object.getOwnPropertyNames(e),
                b.INPUT = Object.getOwnPropertyDescriptor(c, 'value').set,
                Object.defineProperty(c, 'value', {
                    set: a
                }),
                b.TEXTAREA = Object.getOwnPropertyDescriptor(d, 'value').set,
                Object.defineProperty(d, 'value', {
                    set: a
                })
            } catch (f) {
                bd = avalon.tick
            }
        },
        Ta && avalon.bind(va, 'selectionchange', function (a) {
            var b = va.activeElement;
            b && 'function' == typeof b.avalonSetter && b.avalonSetter()
        }),
        $c.INPUT = function (a, b, c) {
            function e(a) {
                c.changed.call(this, a, c)
            }
            function f() {
                l = !0
            }
            function g() {
                l = !1
            }
            function h(a) {
                setTimeout(function () {
                    m(a)
                })
            }
            var i = a.type,
            j = c.bound,
            k = avalon(a),
            l = !1,
            m = function () {
                if (!l) {
                    var d = a.oldValue = a.value,
                    f = c.pipe(d, c, 'get');
                    k.data('duplex-observe') !== !1 && (b(f), e.call(a, f), k.data('duplex-focus') && avalon.nextTick(function () {
                        a.focus()
                    }))
                }
            };
            if (c.handler = function () {
                var d = c.pipe(b(), c, 'set') + '';
                d !== a.oldValue && (a.value = d)
            }, c.isChecked || 'radio' === i) {
                var n = 6 === Ta;
                m = function () {
                    if (k.data('duplex-observe') !== !1) {
                        var d = c.pipe(a.value, c, 'get');
                        b(d),
                        e.call(a, d)
                    }
                },
                c.handler = function () {
                    var d = b(),
                    e = c.isChecked ? !!d : d + '' === a.value;
                    a.oldValue = e,
                    n ? setTimeout(function () {
                        a.defaultChecked = e,
                        a.checked = e
                    }, 31)  : a.checked = e
                },
                j('click', m)
            } else if ('checkbox' === i) m = function () {
                if (k.data('duplex-observe') !== !1) {
                    var f = a.checked ? 'ensure' : 'remove',
                    g = b();
                    Array.isArray(g) || (d('ms-duplex应用于checkbox上要对应一个数组'), g = [
                        g
                    ]),
                    avalon.Array[f](g, c.pipe(a.value, c, 'get')),
                    e.call(a, g)
                }
            },
            c.handler = function () {
                var d = [
                ].concat(b());
                a.checked = d.indexOf(c.pipe(a.value, c, 'get')) > - 1
            },
            j(Na ? 'change' : 'click', m);
             else {
                var o = a.getAttribute('data-duplex-event') || 'input';
                a.attributes['data-event'] && d('data-event指令已经废弃，请改用data-duplex-event'),
                o.replace(Da, function (a) {
                    switch (a) {
                        case 'input':
                            Ta ? (Ta > 8 ? j('input', m)  : j('propertychange', function (a) {
                                'value' === a.propertyName && m()
                            }), j('dragend', h))  : (j('input', m), j('compositionstart', f), j('compositionend', g), j('DOMAutoComplete', m));
                            break;
                        default:
                            j(a, m)
                    }
                })
            }
            j('focus', function () {
                a.msFocus = !0
            }),
            j('blur', function () {
                a.msFocus = !1
            }),
            /text|password|hidden/.test(i) && bd(function () {
                if (Oa.contains(a)) a.msFocus || a.oldValue === a.value || m();
                 else if (!a.msRetain) return !1
            }),
            a.avalonSetter = m,
            a.oldValue = a.value,
            B(c),
            e.call(a, a.value)
        },
        $c.TEXTAREA = $c.INPUT,
        $c.SELECT = function (a, b, c) {
            function e() {
                if (f.data('duplex-observe') !== !1) {
                    var d = f.val();
                    d = Array.isArray(d) ? d.map(function (a) {
                        return c.pipe(a, c, 'get')
                    })  : c.pipe(d, c, 'get'),
                    d + '' !== a.oldValue && b(d),
                    c.changed.call(a, d, c)
                }
            }
            var f = avalon(a);
            c.handler = function () {
                var c = b();
                c = c && c.$model || c,
                Array.isArray(c) ? a.multiple || d('ms-duplex在<select multiple=true>上要求对应一个数组')  : a.multiple && d('ms-duplex在<select multiple=false>不能对应一个数组'),
                c = Array.isArray(c) ? c.map(String)  : c + '',
                c + '' !== a.oldValue && (f.val(c), a.oldValue = c + '')
            },
            c.bound('change', e),
            K(a, function () {
                B(c),
                c.changed.call(a, b(), c)
            }, 0 / 0)
        },
        Ya.html = function (a, b, c) {
            a = null == a ? '' : a;
            var d = 'group' in c,
            e = d ? b.parentNode : b;
            if (e) {
                if (11 === a.nodeType) var f = a;
                 else if (1 === a.nodeType || a.item) {
                    var g = 1 === a.nodeType ? a.childNodes : a.item ? a : [
                    ];
                    for (f = Pa.cloneNode(!0); g[0]; ) f.appendChild(g[0])
                } else f = avalon.parseHTML(a);
                var h = va.createComment('ms-html');
                if (d) {
                    e.insertBefore(h, b);
                    for (var i = c.group, j = 1; i > j; ) {
                        var k = b.nextSibling;
                        k && (e.removeChild(k), j++)
                    }
                    e.removeChild(b),
                    c.element = h
                } else avalon.clearHTML(e).appendChild(h);
                d && (c.group = f.childNodes.length || 1),
                g = avalon.slice(f.childNodes),
                g[0] && (h.parentNode && h.parentNode.replaceChild(f, h), d && (c.element = g[0])),
                Q(g, c.vmodels)
            }
        },
        Xa['if'] = Xa.data = Xa.text = Xa.html = function (a, b) {
            fa(a.value, b, a)
        },
        Ya['if'] = function (a, b, c) {
            if (a) 8 === b.nodeType && (b.parentNode.replaceChild(c.template, b), b = c.element = c.template),
            b.getAttribute(c.name) && (b.removeAttribute(c.name), O(b, c.vmodels)),
            c.rollback = null;
             else if (1 === b.nodeType) {
                var d = c.element = va.createComment('ms-if');
                b.parentNode.replaceChild(d, b),
                c.template = b,
                xa.appendChild(b),
                c.rollback = function () {
                    b.parentNode === xa && xa.removeChild(b)
                }
            }
        };
        var cd = /\(([^)]*)\)/;
        Xa.on = function (a, b) {
            var c = a.value;
            a.type = 'on';
            var d = a.param.replace(/-\d+$/, '');
            if ('function' == typeof Xa.on[d + 'Hook'] && Xa.on[d + 'Hook'](a), c.indexOf('(') > 0 && c.indexOf(')') > - 1) {
                var e = (c.match(cd) || ['',
                '']) [1].trim();
                ('' === e || '$event' === e) && (c = c.replace(cd, ''))
            }
            fa(c, b, a)
        },
        Ya.on = function (a, b, c) {
            a = function (a) {
                var b = c.evaluator || e;
                return b.apply(this, c.args.concat(a))
            };
            var d = c.param.replace(/-\d+$/, '');
            if ('scan' === d) a.call(b, {
                type: d
            });
             else if ('function' == typeof c.specialBind) c.specialBind(b, a);
             else var f = avalon.bind(b, d, a);
            c.rollback = function () {
                'function' == typeof c.specialUnbind ? c.specialUnbind()  : avalon.unbind(b, d, f)
            }
        },
        Xa.repeat = function (a, b) {
            var c = a.type;
            fa(a.value, b, a, 0, 1),
            a.proxies = [
            ];
            var d = !1;
            try {
                var e = a.$repeat = a.evaluator.apply(0, a.args || []),
                f = avalon.type(e);
                'object' !== f && 'array' !== f && (d = !0, avalon.log('warning:' + a.value + '对应类型不正确'))
            } catch (g) {
                d = !0,
                avalon.log('warning:' + a.value + '编译出错')
            }
            var h = a.value.split('.') || [];
            if (h.length > 1) {
                h.pop();
                for (var i, j = h[0], k = 0; i = b[k++]; ) if (i && i.hasOwnProperty(j)) {
                    var l = i[j].$events || {
                    };
                    l[za] = l[za] || [],
                    l[za].push(a);
                    break
                }
            }
            var m = a.element;
            m.removeAttribute(a.name),
            a.sortedCallback = Rb(m, 'data-with-sorted', b),
            a.renderedCallback = Rb(m, 'data-' + c + '-rendered', b);
            var n = Sa(c),
            o = a.element = va.createComment(n + ':end');
            if (a.clone = va.createComment(n), Pa.appendChild(o), 'each' === c || 'with' === c ? (a.template = m.innerHTML.trim(), avalon.clearHTML(m).appendChild(o))  : (a.template = m.outerHTML.trim(), m.parentNode.replaceChild(o, m)), a.template = avalon.parseHTML(a.template), a.rollback = function () {
                var b = a.element;
                if (b) {
                    Ya.repeat.call(a, 'clear');
                    var c = b.parentNode,
                    d = a.template,
                    e = d.firstChild;
                    c.replaceChild(d, b);
                    var f = a.$stamp;
                    f && f.parentNode && f.parentNode.removeChild(f),
                    e = a.element = 'repeat' === a.type ? e : c
                }
            }, !d) {
                a.handler = Ya.repeat,
                a.$outer = {
                };
                var p = '$key',
                q = '$val';
                for (Array.isArray(e) && (p = '$first', q = '$last'), k = 0; i = b[k++]; ) if (i.hasOwnProperty(p) && i.hasOwnProperty(q)) {
                    a.$outer = i;
                    break
                }
                var r = e.$events,
                s = (r || {
                }) [za];
                if (s && avalon.Array.ensure(s, a) && D(a, s), 'object' === f) {
                    a.$with = !0;
                    var t = r ? r.$withProxyPool || (r.$withProxyPool = {
                    })  : {
                    };
                    a.handler('append', e, t)
                } else e.length && a.handler('add', 0, e.length)
            }
        },
        Ya.repeat = function (a, b, c) {
            if (a) {
                var d = this,
                f = d.element,
                g = f.parentNode,
                h = d.proxies,
                i = Pa.cloneNode(!1);
                switch (a) {
                    case 'add':
                        for (var j, k = b + c, l = d.$repeat, m = l.length - 1, n = [
                        ], o = ka(d, b), p = b; k > p; p++) {
                            var q = na(p, d);
                            h.splice(p, 0, q),
                            ja(d, i, q, n)
                        }
                        for (g.insertBefore(i, o), p = 0; j = n[p++]; ) Q(j.nodes, j.vmodels),
                        j.nodes = j.vmodels = null;
                        break;
                    case 'del':
                        o = h[b].$stamp,
                        f = ka(d, b + c),
                        la(o, f);
                        var r = h.splice(b, c);
                        qa(r, 'each');
                        break;
                    case 'clear':
                        var s = d.$stamp || h[0];
                        s && (o = s.$stamp || s, la(o, f)),
                        qa(h, 'each');
                        break;
                    case 'move':
                        o = h[0].$stamp;
                        var t,
                        u = o.nodeValue,
                        v = [
                        ],
                        w = [
                        ];
                        for (la(o, f, function () {
                            w.unshift(this),
                            this.nodeValue === u && (v.unshift(w), w = [
                            ])
                        }), A(h, b), A(v, b); w = v.shift(); ) for (; t = w.shift(); ) i.appendChild(t);
                        g.insertBefore(i, f);
                        break;
                    case 'index':
                        for (m = h.length - 1; c = h[b]; b++) c.$index = b,
                        c.$first = 0 === b,
                        c.$last = b === m;
                        return;
                    case 'set':
                        return q = h[b],
                        void (q && H(q.$events.$index));
                    case 'append':
                        var x = c,
                        y = [
                        ];
                        n = [
                        ];
                        for (var z in b) b.hasOwnProperty(z) && 'hasOwnProperty' !== z && y.push(z);
                        if (d.sortedCallback) {
                            var B = d.sortedCallback.call(g, y);
                            B && Array.isArray(B) && B.length && (y = B)
                        }
                        for (p = 0; z = y[p++]; ) 'hasOwnProperty' !== z && (x[z] || (x[z] = pa(z, d)), ja(d, i, x[z], n));
                        var C = d.$stamp = d.clone;
                        for (g.insertBefore(C, f), g.insertBefore(i, f), p = 0; j = n[p++]; ) Q(j.nodes, j.vmodels),
                        j.nodes = j.vmodels = null
                }
                'clear' === a && (a = 'del');
                var D = d.renderedCallback || e,
                E = arguments;
                K(g, function () {
                    D.apply(g, E),
                    g.oldValue && 'SELECT' === g.tagName && avalon(g).val(g.oldValue.split(','))
                }, 0 / 0)
            }
        },
        'with,each'.replace(Da, function (a) {
            Xa[a] = Xa.repeat
        });
        var dd = [
        ],
        ed = [
        ];
        Ya.text = function (a, b) {
            if (a = null == a ? '' : a, 3 === b.nodeType) try {
                b.data = a
            } catch (c) {
            } else 'textContent' in b ? b.textContent = a : b.innerText = a
        },
        avalon.parseDisplay = ra,
        Xa.visible = function (a, b) {
            var c = avalon(a.element),
            d = c.css('display');
            if ('none' === d) {
                var e = c[0].style,
                f = /visibility/i.test(e.cssText),
                g = c.css('visibility');
                e.display = '',
                e.visibility = 'hidden',
                d = c.css('display'),
                'none' === d && (d = ra(c[0].nodeName)),
                e.visibility = f ? g : ''
            }
            a.display = d,
            fa(a.value, b, a)
        },
        Ya.visible = function (a, b, c) {
            b.style.display = a ? c.display : 'none'
        },
        Xa.widget = function (a, c) {
            var d = a.value.match(Da),
            f = a.element,
            g = d[0],
            h = d[1];
            h && '$' !== h || (h = Sa(g));
            var i = d[2] || g,
            j = avalon.ui[g];
            if ('function' == typeof j) {
                c = f.vmodels || c;
                for (var k, l = 0; k = c[l++]; ) if (k.hasOwnProperty(i) && 'object' == typeof k[i]) {
                    var m = k[i];
                    m = m.$model || m;
                    break
                }
                if (m) {
                    var n = m[g + 'Id'];
                    'string' == typeof n && (h = n)
                }
                var o = avalon.getWidgetData(f, g);
                a.value = [
                    g,
                    h,
                    i
                ].join(','),
                a[g + 'Id'] = h,
                a.evaluator = e,
                f.msData['ms-widget-id'] = h;
                var p = a[g + 'Options'] = avalon.mix({
                }, j.defaults, m || {
                }, o);
                f.removeAttribute('ms-widget');
                var q = j(f, a, c) || {
                };
                q.$id ? (avalon.vmodels[h] = q, L(f, q), q.hasOwnProperty('$init') && q.$init(function () {
                    avalon.scan(f, [
                        q
                    ].concat(c)),
                    'function' == typeof p.onInit && p.onInit.call(f, q, p, c)
                }), a.rollback = function () {
                    try {
                        q.widgetElement = null,
                        q.$remove()
                    } catch (a) {
                    }
                    f.msData = {
                    },
                    delete avalon.vmodels[q.$id]
                }, D(a, fd), b.chrome && f.addEventListener('DOMNodeRemovedFromDocument', function () {
                    setTimeout(G)
                }))  : avalon.scan(f, c)
            } else c.length && (f.vmodels = c)
        };
        var fd = [
        ],
        gd = /<script[^>]*>([\S\s]*?)<\/script\s*>/gim,
        hd = /\s+(on[^=\s]+)(?:=("[^"]*"|'[^']*'|[^\s>]+))?/g,
        id = /<\w+\b(?:(["'])[^"]*?(\1)|[^>])*>/gi,
        jd = {
            a: /\b(href)\=("javascript[^"]*"|'javascript[^']*')/gi,
            img: /\b(src)\=("javascript[^"]*"|'javascript[^']*')/gi,
            form: /\b(action)\=("javascript[^"]*"|'javascript[^']*')/gi
        },
        kd = /[\uD800-\uDBFF][\uDC00-\uDFFF]/g,
        ld = /([^\#-~| |!])/g,
        md = avalon.filters = {
            uppercase: function (a) {
                return a.toUpperCase()
            },
            lowercase: function (a) {
                return a.toLowerCase()
            },
            truncate: function (a, b, c) {
                return b = b || 30,
                c = void 0 === c ? '...' : c,
                a.length > b ? a.slice(0, b - c.length) + c : String(a)
            },
            $filter: function (a) {
                for (var b = 1, c = arguments.length; c > b; b++) {
                    var d = arguments[b],
                    e = avalon.filters[d.shift()];
                    if ('function' == typeof e) {
                        var f = [
                            a
                        ].concat(d);
                        a = e.apply(null, f)
                    }
                }
                return a
            },
            camelize: X,
            sanitize: function (a) {
                return a.replace(gd, '').replace(id, function (a, b) {
                    var c = a.toLowerCase().match(/<(\w+)\s/);
                    if (c) {
                        var d = jd[c[1]];
                        d && (a = a.replace(d, function (a, b, c) {
                            var d = c.charAt(0);
                            return b + '=' + d + 'javascript:void(0)' + d
                        }))
                    }
                    return a.replace(hd, ' ').replace(/\s+/g, ' ')
                })
            },
            escape: function (a) {
                return String(a).replace(/&/g, '&amp;').replace(kd, function (a) {
                    var b = a.charCodeAt(0),
                    c = a.charCodeAt(1);
                    return '&#' + (1024 * (b - 55296) + (c - 56320) + 65536) + ';'
                }).replace(ld, function (a) {
                    return '&#' + a.charCodeAt(0) + ';'
                }).replace(/</g, '&lt;').replace(/>/g, '&gt;')
            },
            currency: function (a, b, c) {
                return (b || '￥') + sa(a, isFinite(c) ? c : 2)
            },
            number: sa
        };
        !new function () {
            function a(a) {
                return parseInt(a, 10) || 0
            }
            function b(a, b, c) {
                var d = '';
                for (0 > a && (d = '-', a = - a), a = '' + a; a.length < b; ) a = '0' + a;
                return c && (a = a.substr(a.length - b)),
                d + a
            }
            function c(a, c, d, e) {
                return function (f) {
                    var g = f['get' + a]();
                    return (d > 0 || g > - d) && (g += d),
                    0 === g && - 12 === d && (g = 12),
                    b(g, c, e)
                }
            }
            function d(a, b) {
                return function (c, d) {
                    var e = c['get' + a](),
                    f = (b ? 'SHORT' + a : a).toUpperCase();
                    return d[f][e]
                }
            }
            function e(a) {
                var c = - 1 * a.getTimezoneOffset(),
                d = c >= 0 ? '+' : '';
                return d += b(Math[c > 0 ? 'floor' : 'ceil'](c / 60), 2) + b(Math.abs(c % 60), 2)
            }
            function f(a, b) {
                return a.getHours() < 12 ? b.AMPMS[0] : b.AMPMS[1]
            }
            var g = {
                yyyy: c('FullYear', 4),
                yy: c('FullYear', 2, 0, !0),
                y: c('FullYear', 1),
                MMMM: d('Month'),
                MMM: d('Month', !0),
                MM: c('Month', 2, 1),
                M: c('Month', 1, 1),
                dd: c('Date', 2),
                d: c('Date', 1),
                HH: c('Hours', 2),
                H: c('Hours', 1),
                hh: c('Hours', 2, - 12),
                h: c('Hours', 1, - 12),
                mm: c('Minutes', 2),
                m: c('Minutes', 1),
                ss: c('Seconds', 2),
                s: c('Seconds', 1),
                sss: c('Milliseconds', 3),
                EEEE: d('Day'),
                EEE: d('Day', !0),
                a: f,
                Z: e
            },
            h = /((?:[^yMdHhmsaZE']+)|(?:'(?:[^']|'')*')|(?:E+|y+|M+|d+|H+|h+|m+|s+|a|Z))(.*)/,
            i = /^\/Date\((\d+)\)\/$/;
            md.date = function (b, c) {
                var d,
                e,
                f = md.date.locate,
                j = '',
                k = [
                ];
                if (c = c || 'mediumDate', c = f[c] || c, 'string' == typeof b) if (/^\d+$/.test(b)) b = a(b);
                 else if (i.test(b)) b = + RegExp.$1;
                 else {
                    var l = b.trim(),
                    m = [
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0
                    ],
                    n = new Date(0);
                    l = l.replace(/^(\d+)\D(\d+)\D(\d+)/, function (b, c, d, e) {
                        var f = 4 === e.length ? [
                            e,
                            c,
                            d
                        ] : [
                            c,
                            d,
                            e
                        ];
                        return m[0] = a(f[0]),
                        m[1] = a(f[1]) - 1,
                        m[2] = a(f[2]),
                        ''
                    });
                    var o = n.setFullYear,
                    p = n.setHours;
                    l = l.replace(/[T\s](\d+):(\d+):?(\d+)?\.?(\d)?/, function (b, c, d, e, f) {
                        return m[3] = a(c),
                        m[4] = a(d),
                        m[5] = a(e),
                        f && (m[6] = Math.round(1000 * parseFloat('0.' + f))),
                        ''
                    });
                    var q = 0,
                    r = 0;
                    l = l.replace(/Z|([+-])(\d\d):?(\d\d)/, function (b, c, d, e) {
                        return o = n.setUTCFullYear,
                        p = n.setUTCHours,
                        c && (q = a(c + d), r = a(c + e)),
                        ''
                    }),
                    m[3] -= q,
                    m[4] -= r,
                    o.apply(n, m.slice(0, 3)),
                    p.apply(n, m.slice(3)),
                    b = n
                }
                if ('number' == typeof b && (b = new Date(b)), 'date' === avalon.type(b)) {
                    for (; c; ) e = h.exec(c),
                    e ? (k = k.concat(e.slice(1)), c = k.pop())  : (k.push(c), c = null);
                    return k.forEach(function (a) {
                        d = g[a],
                        j += d ? d(b, f)  : a.replace(/(^'|'$)/g, '').replace(/''/g, '\'')
                    }),
                    j
                }
            };
            var j = {
                AMPMS: {
                    0: '上午',
                    1: '下午'
                },
                DAY: {
                    0: '星期日',
                    1: '星期一',
                    2: '星期二',
                    3: '星期三',
                    4: '星期四',
                    5: '星期五',
                    6: '星期六'
                },
                MONTH: {
                    0: '1月',
                    1: '2月',
                    2: '3月',
                    3: '4月',
                    4: '5月',
                    5: '6月',
                    6: '7月',
                    7: '8月',
                    8: '9月',
                    9: '10月',
                    10: '11月',
                    11: '12月'
                },
                SHORTDAY: {
                    0: '周日',
                    1: '周一',
                    2: '周二',
                    3: '周三',
                    4: '周四',
                    5: '周五',
                    6: '周六'
                },
                fullDate: 'y年M月d日EEEE',
                longDate: 'y年M月d日',
                medium: 'yyyy-M-d H:mm:ss',
                mediumDate: 'yyyy-M-d',
                mediumTime: 'H:mm:ss',
                'short': 'yy-M-d ah:mm',
                shortDate: 'yy-M-d',
                shortTime: 'ah:mm'
            };
            j.SHORTMONTH = j.MONTH,
            md.date.locate = j
        };
        var nd = avalon.modules = {
            'domReady!': {
                exports: avalon,
                state: 3
            },
            avalon: {
                exports: avalon,
                state: 4
            }
        };
        nd.exports = nd.avalon,
        new function () {
            function c(a, b) {
                var c = 'js';
                a = a.replace(/^(\w+)\!/, function (a, b) {
                    return c = b,
                    ''
                }),
                'ready' === c && (d('debug: ready!已经被废弃，请使用domReady!'), c = 'domReady');
                var e = '';
                a = a.replace(L, function (a) {
                    return e = a,
                    ''
                });
                var f = '.' + c,
                g = /js|css/.test(f) ? f : '';
                a = a.replace(/\.[a-z0-9]+$/g, function (a) {
                    return a === f ? (g = a, '')  : a
                });
                var h = avalon.mix({
                    query: e,
                    ext: g,
                    res: c,
                    name: a,
                    toUrl: p
                }, b);
                return h.toUrl(a),
                h
            }
            function f(a) {
                var b = a.name,
                c = a.res,
                d = nd[b],
                e = b && a.urlNoQuery;
                if (d && d.state >= 3) return b;
                if (d = nd[e], d && d.state >= 3) return pb(d.deps || [], d.factory, e),
                e;
                if (b && !d) {
                    d = nd[e] = {
                        id: e,
                        state: 1
                    };
                    var f = function (e) {
                        K[c] = e,
                        e.load(b, a, function (a) {
                            arguments.length && void 0 !== a && (d.exports = a),
                            d.state = 4,
                            i()
                        })
                    };
                    K[c] ? f(K[c])  : pb([c], f)
                }
                return b ? e : c + '!'
            }
            function g(a, b) {
                for (var c, d = 0; c = a[d++]; ) if (4 !== nd[c].state && (c === b || g(nd[c].deps, b))) return !0
            }
            function h(a, b, c) {
                var e = k(a.src);
                return a.onload = a.onreadystatechange = a.onerror = null,
                b || c && nd[e] && !nd[e].state ? (setTimeout(function () {
                    wa.removeChild(a),
                    a = null
                }), void d('debug: 加载 ' + e + ' 失败' + b + ' ' + !nd[e].state))  : !0
            }
            function i() {
                a: for (var a, b = z.length; a = z[--b]; ) {
                    var c = nd[a],
                    d = c.deps;
                    if (d) {
                        for (var e, f = 0; e = d[f]; f++) {
                            var g = C[e];
                            if (g && (e = d[f] = g), 4 !== Object(nd[e]).state) continue a
                        }
                        4 !== c.state && (z.splice(b, 1), o(c.id, c.deps, c.factory), i())
                    }
                }
            }
            function j(a, b, c) {
                function e() {
                    if (!'1'[0] && !g) return g = setTimeout(e, 150);
                    if (j || J.test(f.readyState)) {
                        clearTimeout(g);
                        var k = A.pop();
                        k && k.require(b),
                        c && c(),
                        h(f, !1, !j) && (d('debug: 已成功加载 ' + a), b && z.push(b), i())
                    }
                }
                var f = va.createElement('script');
                f.className = za;
                var g,
                j = 'onload' in f,
                k = j ? 'onload' : 'onreadystatechange';
                f[k] = e,
                f.onerror = function () {
                    h(f, !0)
                },
                wa.insertBefore(f, wa.firstChild),
                f.src = a,
                d('debug: 正准备加载 ' + a)
            }
            function k(a) {
                return (a || '').replace(L, '')
            }
            function l(a) {
                return /^(?:[a-z]+:)?\/\//i.test(String(a))
            }
            function m(a, b) {
                return '1'[0] ? a[b] : a.getAttribute(b, 4)
            }
            function n() {
                var c;
                try {
                    a.b.c()
                } catch (d) {
                    c = d.stack,
                    !c && b.opera && (c = (String(d).match(/of linked script \S+/g) || []).join(' '))
                }
                if (c) return c = c.split(/[@ ]/g).pop(),
                c = '(' === c[0] ? c.slice(1, - 1)  : c.replace(/\s/, ''),
                k(c.replace(/(:\d+)?:\d+$/i, ''));
                for (var e, f = wa.getElementsByTagName('script'), g = f.length; e = f[--g]; ) if (e.className === za && 'interactive' === e.readyState) {
                    var h = m(e, 'src');
                    return e.className = k(h)
                }
            }
            function o(a, c, d) {
                var e = Object(nd[a]);
                e.state = 4;
                for (var f, g = 0, h = [
                ]; f = c[g++]; ) if (f = C[f] || f, 'exports' === f) {
                    var i = e.exports || (e.exports = {
                    });
                    h.push(i)
                } else h.push(nd[f].exports);
                var j = d.apply(b, h);
                return void 0 !== j && (e.exports = j),
                delete e.factory,
                j
            }
            function p(a) {
                0 === a.indexOf(this.res + '!') && (a = a.slice(this.res.length + 1));
                var b = a,
                c = 0,
                d = this.baseUrl,
                e = this.parentUrl || d;
                v(a, q.paths, function (a, d) {
                    b = b.replace(d, a),
                    c = 1
                }),
                c || v(a, q.packages, function (a, c, d) {
                    b = b.replace(d.name, d.location)
                }),
                this.mapUrl && v(this.mapUrl, q.map, function (a) {
                    v(b, a, function (a, c) {
                        b = b.replace(c, a),
                        e = d
                    })
                });
                var f = this.ext;
                f && c && b.slice( - f.length) === f && (b = b.slice(0, - f.length)),
                l(b) || (e = this.built || /^\w/.test(b) ? d : e, b = x(e, b));
                var g = b + f;
                return b = g + this.query,
                v(a, q.urlArgs, function (a) {
                    b += ( - 1 === b.indexOf('?') ? '?' : '&') + a
                }),
                this.url = b,
                this.urlNoQuery = g
            }
            function r(a, b, c) {
                var d = u(a, b, c);
                return d.sort(w),
                d
            }
            function s(a) {
                return new RegExp('^' + a + '(/|$)')
            }
            function t(a) {
                return function () {
                    var c;
                    return a.init && (c = a.init.apply(b, arguments)),
                    c || a.exports && y(a.exports)
                }
            }
            function u(a, b, c) {
                var d = [
                ];
                for (var e in a) if (Ia.call(a, e)) {
                    var f = {
                        name: e,
                        val: a[e]
                    };
                    d.push(f),
                    f.reg = '*' === e && b ? /^/ : s(e),
                    c && '*' !== e && (f.reg = new RegExp('/' + e.replace(/^\//, '') + '(/|$)'))
                }
                return d
            }
            function v(a, b, c) {
                b = b || [];
                for (var d, e = 0; d = b[e++]; ) if (d.reg.test(a)) return c(d.val, d.name, d),
                !1
            }
            function w(a, b) {
                var c = a.name,
                d = b.name;
                return '*' === d ? - 1 : '*' === c ? 1 : d.length - c.length
            }
            function x(a, b) {
                if ('/' !== a.charAt(a.length - 1) && (a += '/'), './' === b.slice(0, 2)) return a + b.slice(2);
                if ('..' === b.slice(0, 2)) {
                    for (a += b; M.test(a); ) a = a.replace(M, '');
                    return a
                }
                return '/' === b.slice(0, 1) ? a + b.slice(1)  : a + b
            }
            function y(a) {
                if (!a) return a;
                var c = b;
                return a.split('.').forEach(function (a) {
                    c = c[a]
                }),
                c
            }
            var z = [
            ],
            A = [
            ],
            B = /\.js$/i,
            C = {
            },
            D = [
            ],
            E = !1;
            pb = avalon.require = function (a, b, d, g) {
                if (E) {
                    Array.isArray(a) || avalon.error('require方法的第一个参数应为数组 ' + a);
                    var h = [
                    ],
                    j = {
                    },
                    k = d || 'callback' + setTimeout('1');
                    g = g || {
                    },
                    g.baseUrl = q.baseUrl;
                    var l = !!g.built;
                    if (d && (g.parentUrl = d.substr(0, d.lastIndexOf('/')), g.mapUrl = d.replace(B, '')), l) {
                        var m = c(g.defineName, g);
                        k = m.urlNoQuery
                    } else a.forEach(function (a) {
                        var b = c(a, g),
                        d = f(b);
                        d && (j[d] || (h.push(d), j[d] = '司徒正美'))
                    });
                    var n = nd[k];
                    n && 4 === n.state || (nd[k] = {
                        id: k,
                        deps: l ? a.concat()  : h,
                        factory: b || e,
                        state: 3
                    }),
                    n || z.push(k),
                    i()
                } else if (D.push(avalon.slice(arguments)), arguments.length <= 2) {
                    E = !0;
                    for (var o, p = D.splice(0, D.length); o = p.shift(); ) pb.apply(null, o)
                }
            },
            pb.define = function (a, b, c) {
                'string' != typeof a && (c = b, b = a, a = 'anonymous'),
                Array.isArray(b) || (c = b, b = [
                ]);
                var d = {
                    built: !E,
                    defineName: a
                },
                e = [
                    b,
                    c,
                    d
                ];
                c.require = function (a) {
                    if (e.splice(2, 0, a), nd[a]) {
                        nd[a].state = 3;
                        var b = !1;
                        try {
                            b = g(nd[a].deps, a)
                        } catch (d) {
                        }
                        b && avalon.error(a + '模块与之前的模块存在循环依赖，请不要直接用script标签引入' + a + '模块')
                    }
                    delete c.require,
                    pb.apply(null, e)
                };
                var f = d.built ? 'unknown' : n();
                if (f) {
                    var h = nd[f];
                    h && (h.state = 2),
                    c.require(f)
                } else A.push(c)
            },
            pb.config = q,
            pb.define.amd = nd;
            var F = q['orig.paths'] = {
            },
            G = q['orig.map'] = {
            },
            H = q.packages = [
            ],
            I = q['orig.args'] = {
            };
            avalon.mix(qb, {
                paths: function (a) {
                    avalon.mix(F, a),
                    q.paths = r(F)
                },
                map: function (a) {
                    avalon.mix(G, a);
                    var b = r(G, 1, 1);
                    avalon.each(b, function (a, b) {
                        b.val = r(b.val)
                    }),
                    q.map = b
                },
                packages: function (a) {
                    a = a.concat(H);
                    for (var b, c = {
                    }, d = [
                    ], e = 0; b = a[e++]; ) {
                        b = 'string' == typeof b ? {
                            name: b
                        }
                         : b;
                        var f = b.name;
                        if (!c[f]) {
                            var g = b.location ? b.location : x(f, b.main || 'main');
                            g = g.replace(B, ''),
                            d.push(b),
                            c[f] = b.location = g,
                            b.reg = s(f)
                        }
                    }
                    q.packages = d.sort()
                },
                urlArgs: function (a) {
                    'string' == typeof a && (a = {
                        '*': a
                    }),
                    avalon.mix(I, a),
                    q.urlArgs = r(I, 1)
                },
                baseUrl: function (a) {
                    if (!l(a)) {
                        var b = wa.getElementsByTagName('base') [0];
                        b && wa.removeChild(b);
                        var c = va.createElement('a');
                        c.href = a,
                        a = m(c, 'href'),
                        b && wa.insertBefore(b, wa.firstChild)
                    }
                    a.length > 3 && (q.baseUrl = a)
                },
                shim: function (a) {
                    for (var b in a) {
                        var c = a[b];
                        Array.isArray(c) && (c = a[b] = {
                            deps: c
                        }),
                        c.exportsFn || !c.exports && !c.init || (c.exportsFn = t(c))
                    }
                    q.shim = a
                }
            });
            var J = va.documentMode >= 8 ? /loaded/ : /complete|loaded/,
            K = pb.plugins = {
                ready: {
                    load: e
                },
                js: {
                    load: function (a, b, c) {
                        var d = b.url,
                        e = b.urlNoQuery,
                        f = q.shim[a.replace(B, '')];
                        f ? pb(f.deps || [], function () {
                            var a = avalon.slice(arguments);
                            j(d, e, function () {
                                c(f.exportsFn ? f.exportsFn.apply(0, a)  : void 0)
                            })
                        })  : j(d, e)
                    }
                },
                css: {
                    load: function (a, b, c) {
                        var e = b.url,
                        f = va.createElement('link');
                        f.rel = 'stylesheet',
                        f.href = e,
                        wa.insertBefore(f, wa.firstChild),
                        d('debug: 已成功加载 ' + e),
                        c()
                    }
                },
                text: {
                    load: function (a, b, c) {
                        var e = b.url,
                        f = Yc();
                        f.onreadystatechange = function () {
                            if (4 === f.readyState) {
                                var a = f.status;
                                a > 399 && 600 > a ? avalon.error(e + ' 对应资源不存在或没有开启 CORS')  : (d('debug: 已成功加载 ' + e), c(f.responseText))
                            }
                        },
                        f.open('GET', e, !0),
                        'withCredentials' in f && (f.withCredentials = !0),
                        f.setRequestHeader('X-Requested-With', 'XMLHttpRequest'),
                        f.send(),
                        d('debug: 正准备加载 ' + e)
                    }
                }
            };
            pb.checkDeps = i;
            var L = /(\?[^#]*)$/,
            M = /\/\w+\/\.\./,
            N = va.scripts[va.scripts.length - 1],
            O = N.getAttribute('data-main');
            if (O) {
                qb.baseUrl(O);
                var P = q.baseUrl;
                q.baseUrl = P.slice(0, P.lastIndexOf('/') + 1),
                j(P.replace(B, '') + '.js')
            } else {
                var Q = k(m(N, 'src'));
                q.baseUrl = Q.slice(0, Q.lastIndexOf('/') + 1)
            }
        };
        var od,
        pd = [
        ],
        qd = function () {
            od || (od = !0, pb && (nd['domReady!'].state = 4, pb.checkDeps()), pd.forEach(function (a) {
                a(avalon)
            }))
        };
        if ('complete' === va.readyState) setTimeout(qd);
         else if (Na) va.addEventListener('DOMContentLoaded', qd);
         else {
            va.attachEvent('onreadystatechange', function () {
                'complete' === va.readyState && qd()
            });
            try {
                var rd = null === b.frameElement
            } catch (Ab) {
            }
            Oa.doScroll && rd && b.external && ta()
        }
        avalon.bind(b, 'load', qd),
        avalon.ready = function (a) {
            od ? a(avalon)  : pd.push(a)
        },
        avalon.config({
            loader: !1
        }),
        avalon.ready(function () {
            avalon.scan(va.body)
        }),
        'function' == typeof define && define.amd && define('avalon', [
        ], function () {
            return avalon
        });
        b.avalon;
        return avalon.noConflict = function (a) {
            return a && b.avalon === avalon && (b.avalon = avalon),
            avalon
        },
        void 0 === c && (b.avalon = avalon),
        avalon
    })
});
