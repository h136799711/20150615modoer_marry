define('lib/artTemplate', function (a, b, c) {
    !function () {
        function a(a) {
            return a.replace(v, '').replace(w, ',').replace(x, '').replace(y, '').replace(z, '').split(A)
        }
        function d(a) {
            return '\'' + a.replace(/('|\\)/g, '\\$1').replace(/\r/g, '\\r').replace(/\n/g, '\\n') + '\''
        }
        function e(b, c) {
            function e(a) {
                return m += a.split(/\n/).length - 1,
                k && (a = a.replace(/\s+/g, ' ').replace(/<!--[\w\W]*?-->/g, '')),
                a && (a = r[1] + d(a) + r[2] + '\n'),
                a
            }
            function f(b) {
                var d = m;
                if (j ? b = j(b, c)  : g && (b = b.replace(/\n/g, function () {
                    return m++,
                    '$line=' + m + ';'
                })), 0 === b.indexOf('=')) {
                    var e = l && !/^=[=#]/.test(b);
                    if (b = b.replace(/^=[=#]?|[\s;]*$/g, ''), e) {
                        var f = b.replace(/\s*\([^\)]+\)/, '');
                        p[f] || /^(include|print)$/.test(f) || (b = '$escape(' + b + ')')
                    } else b = '$string(' + b + ')';
                    b = r[1] + b + r[2]
                }
                return g && (b = '$line=' + d + ';' + b),
                t(a(b), function (a) {
                    if (a && !n[a]) {
                        var b;
                        b = 'print' === a ? u : 'include' === a ? v : p[a] ? '$utils.' + a : q[a] ? '$helpers.' + a : '$data.' + a,
                        w += a + '=' + b + ',',
                        n[a] = !0
                    }
                }),
                b + '\n'
            }
            var g = c.debug,
            h = c.openTag,
            i = c.closeTag,
            j = c.parser,
            k = c.compress,
            l = c.escape,
            m = 1,
            n = {
                $data: 1,
                $filename: 1,
                $utils: 1,
                $helpers: 1,
                $out: 1,
                $line: 1
            },
            o = ''.trim,
            r = o ? [
                '$out=\'\';',
                '$out+=',
                ';',
                '$out'
            ] : [
                '$out=[];',
                '$out.push(',
                ');',
                '$out.join(\'\')'
            ],
            s = o ? '$out+=text;return $out;' : '$out.push(text);',
            u = 'function(){var text=\'\'.concat.apply(\'\',arguments);' + s + '}',
            v = 'function(filename,data){data=data||$data;var text=$utils.$include(filename,data,$filename);' + s + '}',
            w = '\'use strict\';var $utils=this,$helpers=$utils.$helpers,' + (g ? '$line=0,' : ''),
            x = r[0],
            y = 'return new String(' + r[3] + ');';
            t(b.split(h), function (a) {
                a = a.split(i);
                var b = a[0],
                c = a[1];
                1 === a.length ? x += e(b)  : (x += f(b), c && (x += e(c)))
            });
            var z = w + x + y;
            g && (z = 'try{' + z + '}catch(e){throw {filename:$filename,name:\'Render Error\',message:e.message,line:$line,source:' + d(b) + '.split(/\\n/)[$line-1].replace(/^\\s+/,\'\')};}');
            try {
                var A = new Function('$data', '$filename', z);
                return A.prototype = p,
                A
            } catch (B) {
                throw B.temp = 'function anonymous($data,$filename) {' + z + '}',
                B
            }
        }
        var f = function (a, b) {
            return 'string' == typeof b ? s(b, {
                filename: a
            })  : i(a, b)
        };
        f.version = '3.0.0',
        f.config = function (a, b) {
            g[a] = b
        };
        var g = f.defaults = {
            openTag: '<%',
            closeTag: '%>',
            escape: !0,
            cache: !0,
            compress: !1,
            parser: null
        },
        h = f.cache = {
        };
        f.render = function (a, b) {
            return s(a, b)
        };
        var i = f.renderFile = function (a, b) {
            var c = f.get(a) || r({
                filename: a,
                name: 'Render Error',
                message: 'Template not found'
            });
            return b ? c(b)  : c
        };
        f.get = function (a) {
            var b;
            if (h[a]) b = h[a];
             else if ('object' == typeof document) {
                var c = document.getElementById(a);
                if (c) {
                    var d = (c.value || c.innerHTML).replace(/^\s*|\s*$/g, '');
                    b = s(d, {
                        filename: a
                    })
                }
            }
            return b
        };
        var j = function (a, b) {
            return 'string' != typeof a && (b = typeof a, 'number' === b ? a += '' : a = 'function' === b ? j(a.call(a))  : ''),
            a
        },
        k = {
            '<': '&#60;',
            '>': '&#62;',
            '"': '&#34;',
            '\'': '&#39;',
            '&': '&#38;'
        },
        l = function (a) {
            return k[a]
        },
        m = function (a) {
            return j(a).replace(/&(?![\w#]+;)|[<>"']/g, l)
        },
        n = Array.isArray || function (a) {
            return '[object Array]' === {
            }.toString.call(a)
        },
        o = function (a, b) {
            var c,
            d;
            if (n(a)) for (c = 0, d = a.length; d > c; c++) b.call(a, a[c], c, a);
             else for (c in a) b.call(a, a[c], c)
        },
        p = f.utils = {
            $helpers: {
            },
            $include: i,
            $string: j,
            $escape: m,
            $each: o
        };
        f.helper = function (a, b) {
            q[a] = b
        };
        var q = f.helpers = p.$helpers;
        f.onerror = function (a) {
            var b = 'Template Error\n\n';
            for (var c in a) b += '<' + c + '>\n' + a[c] + '\n\n';
            'object' == typeof console && console.error(b)
        };
        var r = function (a) {
            return f.onerror(a),
            function () {
                return '{Template Error}'
            }
        },
        s = f.compile = function (a, b) {
            function c(c) {
                try {
                    return new i(c, f) + ''
                } catch (d) {
                    return b.debug ? r(d) ()  : (b.debug = !0, s(a, b) (c))
                }
            }
            b = b || {
            };
            for (var d in g) void 0 === b[d] && (b[d] = g[d]);
            var f = b.filename;
            try {
                var i = e(a, b)
            } catch (j) {
                return j.filename = f || 'anonymous',
                j.name = 'Syntax Error',
                r(j)
            }
            return c.prototype = i.prototype,
            c.toString = function () {
                return i.toString()
            },
            f && b.cache && (h[f] = c),
            c
        },
        t = p.$each,
        u = 'break,case,catch,continue,debugger,default,delete,do,else,false,finally,for,function,if,in,instanceof,new,null,return,switch,this,throw,true,try,typeof,var,void,while,with,abstract,boolean,byte,char,class,const,double,enum,export,extends,final,float,goto,implements,import,int,interface,long,native,package,private,protected,public,short,static,super,synchronized,throws,transient,volatile,arguments,let,yield,undefined',
        v = /\/\*[\w\W]*?\*\/|\/\/[^\n]*\n|\/\/[^\n]*$|"(?:[^"\\]|\\[\w\W])*"|'(?:[^'\\]|\\[\w\W])*'|\s*\.\s*[$\w\.]+/g,
        w = /[^\w$]+/g,
        x = new RegExp(['\\b' + u.replace(/,/g, '\\b|\\b') + '\\b'].join('|'), 'g'),
        y = /^\d[^,]*|,\d[^,]*/g,
        z = /^,+|,+$/g,
        A = /^$|,+/;
        g.openTag = '{{',
        g.closeTag = '}}';
        var B = function (a, b) {
            var c = b.split(':'),
            d = c.shift(),
            e = c.join(':') || '';
            return e && (e = ', ' + e),
            '$helpers.' + d + '(' + a + e + ')'
        };
        g.parser = function (a) {
            a = a.replace(/^\s/, '');
            var b = a.split(' '),
            c = b.shift(),
            d = b.join(' ');
            switch (c) {
                case 'if':
                    a = 'if(' + d + '){';
                    break;
                case 'else':
                    b = 'if' === b.shift() ? ' if(' + b.join(' ') + ')' : '',
                    a = '}else' + b + '{';
                    break;
                case '/if':
                    a = '}';
                    break;
                case 'each':
                    var e = b[0] || '$data',
                    g = b[1] || 'as',
                    h = b[2] || '$value',
                    i = b[3] || '$index',
                    j = h + ',' + i;
                    'as' !== g && (e = '[]'),
                    a = '$each(' + e + ',function(' + j + '){';
                    break;
                case '/each':
                    a = '});';
                    break;
                case 'echo':
                    a = 'print(' + d + ');';
                    break;
                case 'print':
                case 'include':
                    a = c + '(' + b.join(',') + ');';
                    break;
                default:
                    if (/^\s*\|\s*[\w\$]/.test(d)) {
                        var k = !0;
                        0 === a.indexOf('#') && (a = a.substr(1), k = !1);
                        for (var l = 0, m = a.split('|'), n = m.length, o = m[l++]; n > l; l++) o = B(o, m[l]);
                        a = (k ? '=' : '=#') + o
                    } else a = f.helpers[c] ? '=#' + c + '(' + b.join(',') + ');' : '=' + a
            }
            return a
        },
        'function' == typeof define ? c.exports = f : 'undefined' != typeof b ? c.exports = f : this.template = f
    }()
});
