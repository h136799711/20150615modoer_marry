define('v3_page/home/pageView', [
    'avalon',
    'dialog',
    'plugins/skrollr'
], function (h, x, b) {
    h('avalon');
    h('dialog');
    var v = $('#wed-time'),
    l = v.data('time');
    if (l) {
        v.text(l.split(' ') [0])
    }
    var n = h('plugins/skrollr');
    var d = $('.header-nav-item'),
    m = [
        'home',
        'about',
        'story',
        'video',
        'invite'
    ],
    g = [
        'cover_bg',
        'about',
        'storyArea',
        'videoVm',
        'inviteVm'
    ],
    o = {
    };
    function i() {
        for (var A = 0; A < m.length; A++) {
            var z = $('#' + m[A]),
            y = $('#' + g[A]),
            B = z.offset();
            o[m[A]] = [
                B.top,
                B.top + y.outerHeight()
            ]
        }
    }
    i();
    $(document).delegate('.header-nav-item', 'click', function () {
        var y = $(this).data('action');
        d.removeClass('active');
        $(this).addClass('active');
        $('html,body').animate({
            scrollTop: o[y][0] - 100
        })
    });
    var r,
    p;
    $(document).on('scroll', function () {
        if (!r) {
            r = $('#head_nav');
            p = r.offset().top
        }
        if ($(this).scrollTop() > p) {
            r.css({
                'position': 'fixed',
                'top': 0,
                'z-index': 3
            })
        } else {
            r.css({
                'position': 'static'
            })
        }
        for (var y in o) {
            var B = o[y],
            A = $(this).scrollTop() + 200;
            if (A > B[0] && A < B[1]) {
                var z = r.find('[data-action=' + y + ']');
                if (!z.hasClass('active')) {
                    d.removeClass('active');
                    z.addClass('active')
                }
                return
            }
        }
    });
    var k = siteConfig.host,
    j = {
        invite: k + Url('space/new/do/invite')
    },
    q;
    q = avalon.define({
        $id: 'inviteCtr',
        is_part: true,
        postData: {
            name: '',
            phone: '',
            part_num: '',
            content: ''
        },
        sendMsg: function () {
            var y = q.postData.$model;
            y['csrfmiddlewaretoken'] = document.getElementById('csrf').value;
            y['uid'] = $(this).data('id');
            y['is_part'] = Number(q.is_part);
            if(!y.name){
				alert('忘了填姓名啦!');
				return;
			}
            $.post(j.invite, y, function (d) {
            	if(d.message){alert(d.message);}
            	if(d.status==1001){
	                alert('发送成功');
	                q.postData = {
	                    name: '',
	                    phone: '',
	                    part_num: '',
	                    content: ''
	                }
				}else{
					alert('发送失败');
				}
            }, 'json')
        }
    });
    avalon.scan(document.getElementById('wed_inviteVm'), q);
    var c = $('#playVideo'),
    e = c.data('video');
    if (e) {
        $('body').append('<div id="videoVm" ms-controller="videoCtr">' + '<div class="dialog-common dialog-video" ms-widget="dialog, videoDia, $videoOpt">' + '<div class="video-wrap"><embed src=" ' + e + ' " allowFullScreen="true" quality="high" width="' + (750 - 40) + ' " height="450" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash"></embed></div>' + '</div>' + '</div>');
        var s = avalon.define({
            $id: 'videoCtr',
            $videoOpt: {
                title: '',
                width: 750,
                getFooter: function () {
                    return ''
                }
            }
        });
        avalon.scan(document.getElementById('videoVm'), s);
        c.on('click', function () {
            avalon.vmodels.videoDia.toggle = true
        })
    }
    var f = $('#mid-holder'),
    a = f.prev(),
    w = f.next(),
    u = false,
    t = {
        attrs: {
            data: [
            ],
            all_data: [
            ],
            h_l: 100,
            h_r: 200
        },
        init: function (y) {
            this.attrs.data = y;
            this.initVm();
            this.handleData()
        },
        initVm: function () {
            var A = this,
            B = A.attrs,
            z,
            y = avalon.define({
                $id: 'galleryCtr',
                storyImgL: [
                ],
                storyImgR: [
                ],
                btn_t: 0,
                imgIndex: 0,
                page: 1,
                showBtn: false,
                iCurImg: '',
                iCurTit: '',
                iCurTxt: '',
                $iCurIndex: 0,
                showList: false,
                loading: true,
                status: '更多',
                $maxHeight: 0,
                $imgDiaOpt: {
                    title: '',
                    width: 720,
                    zIndex: 2,
                    closeIcon: '&#xe621;',
                    getFooter: function () {
                        return ''
                    }
                },
                addMore: function () {
                    var D = this,
                    C = $('#theme-box').data('id');
                    y.loading = true;
                    ere = k + Url('space/new/do/get_story');
                    $.getJSON(ere, {
                        user_id: C,
                        page: ++y.page
                    }, function (E) {
                        if (E.status == 1001) {
                            B.data = E.data.image;
                            A.handleData()
                        } 
                        if(E.nomore == 1){
							 $(D).remove()
						}
                    })
                },
                enlarge: function (G, F, D, E) {
                    var C = avalon.vmodels.imgDia;
                    var H = new Image();
                    H.onload = function () {
                        y.iCurImg = G;
                        y.iCurTit = F;
                        y.iCurTxt = D;
                        y.$iCurIndex = Number(E) - 1;
                        C.toggle = true
                    };
                    H.src = G + '!720'
                },
                tabImg: function (E) {
                    var G = B.all_data,
                    C = avalon.vmodels.imgDia,
                    D;
                    D = (y.$iCurIndex + E + G.length) % G.length;
                    var F = new Image();
                    F.onload = function () {
                        C.$update();
                        y.iCurImg = B.all_data[D].media
                    };
                    F.src = B.all_data[D].media + '!720';
                    y.$iCurIndex = D
                },
                $update: function () {
                    n.init();
                    i();
                    avalon.scan(document.getElementById('wed_inviteVm'), q);
                    avalon.scan(document.getElementById('galleryVm'), y);
                    c = $('#playVideo');
                    e = c.data('video');
                    f = $('#mid-holder');
                    a = f.prev();
                    w = f.next();
                    f.height(y.$maxHeight);
                    c.on('click', function () {
                        avalon.vmodels.videoDia.toggle = true
                    })
                }
            });
            avalon.scan(document.getElementById('galleryVm'), y);
            y.$watch('imgIndex', function (G) {
                var F = B.data;
                if (G >= F.length) {
                    var E = [
                    ],
                    H = [
                    ],
                    C;
                    if (!y.showList) {
                        y.showList = true
                    }
                    if (y.loading) {
                        y.loading = false
                    }
                    for (var D = 0; D < F.length; D++) {
                        if (D % 2 == 0) {
                            E.push(F[D])
                        } else {
                            H.push(F[D])
                        }
                    }
                    y.storyImgL.pushArray(E);
                    y.storyImgR.pushArray(H);
                    B.h_l = a.outerHeight();
                    B.h_r = w.outerHeight();
                    i();
                    C = Math.max(B.h_l, B.h_r);
                    y.$maxHeight = C;
                    f.height(C);
                    y.btn_t = C;
                    y.showBtn = true;
                    y.imgIndex = 0;
                    if (!u) {
                        z = n.init();
                        u = true
                    } else {
                        z.refresh($('#galleryVm').find('._trs-item'))
                    }
                    $('body').attr('style', '')
                }
            })
        },
        handleData: function () {
            var B,
            z = this.attrs,
            y = avalon.vmodels['galleryCtr'],
            C = z.data;
            if (C.length == 0) {
                y.loading = false;
                return
            }
            z.all_data = z.all_data.concat(C);
            for (var A = 0; A < C.length; A++) {
                B = new Image();
                B.index = A;
                B.onload = function () {
                	if(this.width>480) this.width= 480;
                    C[this.index].w = this.width;
                    C[this.index].h = this.height;
                    y.imgIndex++
                };
                if(C[A]){
	                if(!C[A].media){ C[A].media = C[A].path; }
	                B.src = siteConfig.siteurl + '/' + C[A].media ;
				}
            }
        }
    };
    b.exports = {
        render: function (y) {
            t.init(y)
        }
    }
});
