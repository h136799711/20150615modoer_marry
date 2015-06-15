!function (a) {
    function b(a) {
        var b = a.length;
        if (0 != b) {
            r.comboSyntax && (t = r.comboSyntax),
            r.comboMaxLength && (u = r.comboMaxLength),
            o = r.comboExcludes;
            for (var c = [
            ], e = 0; b > e; e++) {
                var f = a[e];
                if (!s[f]) {
                    var g = p.get(f);
                    g.status < q && !m(f) && !n(f) && c.push(f)
                }
            }
            c.length > 0 && h(d(c))
        }
    }
    function c(a) {
        a.requestUri = s[a.uri] || a.uri
    }
    function d(a) {
        return 1 == a.length && a.push('http://127.0.0.1/mijia/static/javascript/t.js'),
        f(e(a))
    }
    function e(a) {
        for (var b = {
            __KEYS: [
            ]
        }, c = 0, d = a.length; d > c; c++) for (var e = a[c].replace('://', '__').split('/'), f = b, g = 0, h = e.length; h > g; g++) {
            var i = e[g];
            f[i] || (f[i] = {
                __KEYS: [
                ]
            }, f.__KEYS.push(i)),
            f = f[i]
        }
        return b
    }
    function f(a) {
        for (var b = [
        ], c = a.__KEYS, d = 0, e = c.length; e > d; d++) {
            for (var f = c[d], h = f, i = a[f], j = i.__KEYS; 1 === j.length; ) h += '/' + j[0],
            i = i[j[0]],
            j = i.__KEYS;
            j.length && b.push([h.replace('__', '://'),
            g(i)])
        }
        return b
    }
    function g(a) {
        for (var b = [
        ], c = a.__KEYS, d = 0, e = c.length; e > d; d++) {
            var f = c[d],
            h = g(a[f]),
            i = h.length;
            if (i) for (var j = 0; i > j; j++) b.push(f + '/' + h[j]);
             else b.push(f)
        }
        return b
    }
    function h(a) {
        for (var b = 0, c = a.length; c > b; b++) for (var d = a[b], e = d[0] + '/', f = k(d[1]), g = 0, h = f.length; h > g; g++) i(e, f[g]);
        return s
    }
    function i(a, b) {
        var c = a + t[0] + b.join(t[1]),
        d = c.length > u;
        if (b.length > 1 && d) {
            var e = j(b, u - (a + t[0]).length);
            i(a, e[0]),
            i(a, e[1])
        } else {
            if (d) throw new Error('The combo url is too long: ' + c);
            for (var f = 0, g = b.length; g > f; f++) s[a + b[f]] = c + '?t=' + r.timestamp
        }
    }
    function j(a, b) {
        for (var c = t[1], d = a[0], e = 1, f = a.length; f > e; e++) if (d += c + a[e], d.length > b) return [a.splice(0, e),
        a]
    }
    function k(a) {
        for (var b = [
        ], c = {
        }, d = 0, e = a.length; e > d; d++) {
            var f = a[d],
            g = l(f);
            g && (c[g] || (c[g] = [
            ])).push(f)
        }
        for (var h in c) c.hasOwnProperty(h) && b.push(c[h]);
        return b
    }
    function l(a) {
        var b = a.lastIndexOf('.');
        return b >= 0 ? a.substring(b)  : ''
    }
    function m(a) {
        return o ? o.test ? o.test(a)  : o(a)  : void 0
    }
    function n(a) {
        var b = r.comboSyntax || ['??',
        ','],
        c = b[0],
        d = b[1];
        return c && a.indexOf(c) > 0 || d && a.indexOf(d) > 0
    }
    var o,
    p = a.Module,
    q = p.STATUS.FETCHING,
    r = a.data,
    s = r.comboHash = {
    },
    t = [
        '??',
        ','
    ],
    u = 2000;
    if (a.on('load', b), a.on('fetch', c), r.test) {
        var v = a.test || (a.test = {
        });
        v.uris2paths = d,
        v.paths2hash = h
    }
    define('seajs-combo', [
    ], {
    })
}(seajs);
