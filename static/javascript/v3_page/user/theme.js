define('v3_page/user/theme', [
    'avalon',
    'lib/avalon/scrollbar/avalon.scrollbar',
    'modules/noticeMsg'
], function (c, a, d) {
    c('avalon');
    c('lib/avalon/scrollbar/avalon.scrollbar');
    var e = c('modules/noticeMsg');
    function b(f) {
        var g = siteConfig.host;
        this.api = {
        	set_wedding: g + Url('space/new/do/update'),
            get_theme: g + 'card/ajax/g/get_theme_list',
            set_theme: g + 'card/ajax/p/set_theme',
            upload_image: g + 'site/ajax/upload_image/',
            set_rsvp: g + 'userhome/rsvp',
            set_domain: g + Url('space/new/do/set_domain'),
            theme_preview: g + 'userhome/theme_preview'
        };
        this.config = f;
        this.csrf = document.getElementById('csrf').value;
        this.geThemeData = function (h) {
        	//alert(1);
        //h.themes = document.getElementById('themes').innerHTML;
        //$('#themes').remove();
           /* $.getJSON(this.api.get_theme, function (j) {
                if (j.status == 1001) {
                    var k = j.data,
                    i = k.choice;
                    k.music.unshift({
                        'id': 0,
                        'name': '无',
                        'path': ''
                    });
                    h.check_theme = i.theme_id;
                    h.check_music = i.music_id;
                    h.themeList = k.theme;
                    h.musicList = k.music
                }
            })*/
        }
    }
    b.prototype = {
        save_theme: function (j, g, f) {
            var h = this,
            i = {
                id: j,
                theme_type: g,
                csrfmiddlewaretoken: h.csrf
            };
            $.post(h.api.set_theme, i, function (k) {
                if (k.status == 1001) {
                    f && f()
                }
            }, 'json')
        },
        checkDomain: function (f) {
            if (!/^[\\u4E00-\\u9FFF0-9_\-]+$/i.test(f)) {
                return false
            }
            return true
        },
        init: function () {
            var i = this,
            f = avalon.vmodels.vmSetBar;
            if (!f) {
                f = avalon.define({
                    $id: 'vmSetBar',
                    action: '',
                    domain: i.config.domain,
                    toggle: false,
                    show: false,
                    themes:'',
                    bar_h: 0,
                    check_theme: null,
                    check_music: '',
                    progress: '更换背景',
                    audio: '',
                    is_rsvp: i.config.is_rsvp,
                    selectTheme: function (s,m,o) {
                        u = 'theme='+s;
                        $(o).parent().parent().find('.active').removeClass('active');
                		$(o).addClass('active');
                        $.post(i.api.set_wedding, u, function (v) {
			                if (v.status == 1001) {
                       			 $('#themenow').attr('href',m);
			                }
			            }, 'json')
                    },
                    selectMusic: function (o, t) {
                        var q = $('audio'),
                        s = q.get(0),
                        u = $('#musicBtn'),
                        w = u.find('i'),
                        p = [
                            'Moz',
                            'webkit',
                            'ms',
                            'O'
                        ],
                        m = $('#info-menu'),
                        n;
                        for (var r = 0; r < p.length; r++) {
                            if ((p[r] + 'Animation') in s.style) {
                                n = p[r]
                            }
                        }
                        if ( !o) {
                            if (!o) {
                                m.css({
                                    'margin-left': 20
                                });
                                u.hide()
                        		f.audio.wrapper.style.visibility = 'hidden';
                            } else {
                                if (u.css('display') == 'none') {
                                    u.show();
                                    m.css({
                                        'margin-left': 68
                                    })
                                }
                            }
                            v(false)
                        } else {
                        	f.audio.wrapper.style.visibility = 'visible';
                            f.audio.load(t);
                            f.audio.play();
                            if (u.css('display') == 'none') {
                                u.show();
                                m.css({
                                    'margin-left': 68
                                })
                            }
                            v(true)
                        }
                        function v(x) {
                            if (x) {
                                w.get(0).style[n + 'AnimationPlayState'] = 'running';
                                w.data('play', false).html('').html('&#xe65f;')
                            } else {
                                w.get(0).style[n + 'AnimationPlayState'] = 'paused';
                                w.data('play', true).html('').html('&#xe660;');
                                f.audio.playPause();
                                s.pause()
                            }
                        }
                        f.check_music = o;
                        u = 'music='+o;
                        $.post(i.api.set_wedding, u, function (v) {
			                if (v.status == 1001) {
			                }
			            }, 'json')
                    },
                    scrollbar: {
                    },
                    $skipArray: [
                        'scrollbar'
                    ],
                    up: function (m) {
                        avalon.vmodels[m].update()
                    },
                    openMedia: function (m) {
                        $('.' + m + '-bar').height(370);
                        f.action = m;
                        f.up('$' + m + 'Bar')
                    },
                    setDomain: function (m) {
                        if (m && !i.checkDomain(m)) {
                            e.action = '0||域名必须是英文字符或数字及下划线|' + $.now();
                            return false
                        }
                        var n = {
                            domain: m,
                            csrfmiddlewaretoken: i.csrf
                        };
                        $.post(i.api.set_domain, n, function (o) {
                            if (o.status == 1001) {
                            	if(m){
									e.action = '1||域名设置成功|' + $.now()
								}else{
									e.action = '1||域名取消成功|' + $.now()
								}
                            } else {
                                e.action = '0||该域名已占用|' + $.now()
                            }
                        }, 'json')
                    },
                    setRsvp: function () {
                        f.is_rsvp = f.is_rsvp ? 0 : 1;
                        var m = 'is_rsvp='+ f.is_rsvp;
                        $.post(i.api.set_wedding, m, function (n) {
                            window.location.reload()
                        }, 'json')
                    }
                });
                var j = $('#_vmSetBar').find('.set-bar'),
                k = $('#headerVm'),
                h = $('#musicBtn');
                f.$watch('toggle', function (m) {
                    if (m) {
                        f.show = true;
                        g(60, true)
                    } else {
                        g(0, false, function () {
                            f.show = false
                        })
                    }
                })
            }
            function g(n, m, o) {
                j.animate({
                    height: n
                }, 200, function () {
                    o && o()
                });
                if (m) {
                    k.css('top', 60);
                    h.css('top', 90)
                } else {
                    k.css('top', 0);
                    h.css('top', 30)
                }
            }
            $(document).on('click', function (m) {
                if ($(m.target).parents('#_vmSetBar').size() == 0) {
                    f.action = null
                }
            });
            var l = function () {
                f.audio = i.config.audio;
                i.geThemeData(f);
                avalon.scan(document.getElementById('_vmSetBar'), f)
            };
            l()
        }
    };
    d.exports = {
        render: function (f) {
            new b(f).init()
        }
    }
});
