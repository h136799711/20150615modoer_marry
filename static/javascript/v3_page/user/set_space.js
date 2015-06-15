define('v3_page/user/set_space', [
    'avalon',
    'mmRouter',
    'artTemplate',
    'dialog',
    'plugins/upload.min',
    'lib/avalon/scrollbar/avalon.scrollbar',
    'lib/avalon/notice/notice',
    'plugins/map',
    'plugins/timeCircle',
    'lib/audiojs/audiojs/audio',
    'modules/search-result2'
], function (c, b, d) {
    c('avalon');
    c('dialog');
    c('mmRouter');
    c('plugins/upload.min');
    c('lib/avalon/scrollbar/avalon.scrollbar');
    c('lib/avalon/notice/notice');
    c('lib/audiojs/audiojs/audio');
    c('editTheme').render();
    var e = c('storyAdmin'),
    a = c('inviteManage');
    a = new a({
        h: 343
    });
    function f(g) {
        this.config = g;
        this.audio = audiojs.createAll() [0];
        if(config.music == null) {
        	$(this.audio.wrapper).find('.scrubber').css('display','block');
        	$(this.audio.wrapper).find('.time').css('display','block');
        	this.audio.wrapper.style.visibility = 'hidden';
        }
    }
    f.prototype = {
        playMusic: function () {
            var l = $('audio'),
            n = l.get(0),
            j = this.audio,
            o = $('#musicBtn'),
            q = o.find('i'),
            k = [
                'Moz',
                'webkit',
                'ms',
                'O'
            ],
            g = $('#info-menu'),
            h;
            for (var m = 0; m < k.length; m++) {
                if ((k[m] + 'Animation') in n.style) {
                    h = k[m]
                }
            }
            if (!l.attr('src')) {
                p(false);
                o.hide();
                g.css({
                    'margin-left': 20
                })
            } else {
                p(true)
            }
            o.on('click', function () {
                if (l.attr('src')) {
                    p(q.data('play'))
                }
            });
            function p(i) {
                if (i) {
                    q.get(0).style[h + 'AnimationPlayState'] = 'running';
                    q.data('play', false).html('').html('&#xe65f;');
                    j.play()
                } else {
                    q.get(0).style[h + 'AnimationPlayState'] = 'paused';
                    q.data('play', true).html('').html('&#xe660;');
                    j.playPause()
                }
            }
        },
        init: function () {
            var g = this;
            $('._theme-set').click(function (i) {
                var h = avalon.vmodels['vmSetBar'];
                h ? h.toggle = !h.toggle : c.async(['v3_page/user/theme'], function (j) {
                	 g.config['audio'] = g.audio;
                    j.render(g.config);
                    h = avalon.vmodels['vmSetBar'];
                    h.toggle = true
                })
            });
            g.playMusic();
            $('#info-menu').on('click', '._invite', function (h) {
                a.showBox()
            });
            $('#info-menu').on('click', '._write', function (i) {
                var h = avalon.vmodels['vMmask'];
                h.controll = 'story';
                h.setNav('story');
                h.showMask();
                avalon.vmodels['vmSetStory'] || e.render()
            })
        }
    };
    d.exports = {
        render: function (g) {
            new f(g).init()
        }
    }
});
define('inviteManage', [
], function (d, b, e) {
    var a = e.exports = function a(i) {
        this.config = i || {
        };
        var j = siteConfig.host;
        this.api = {
            invite: j + Url('space/new/do/get_invite'),
            sendSMS: j + Url('space/new/do/sendsms')
        };
        this.csrf = document.getElementById('csrf').value;
        this.tpl = '<div id="_vmInvite" class="mask-box-main" ms-controller="vmInvite">' + '<div class="mask-close text-right"><a href="javascript:;" ms-click="closeBox"><i class="iconfont">&#xe622;</i></a></div>' + '<div class="_box-body container-fluid">MS_CONTENT</div>' + '</div>';
        return this._init()
    };
    a.prototype._init = function () {
        var i = this,
        j = avalon.define({
            $id: 'vmInvite',
            inviteList: [
            ],
            phoneList: '',
            is_send: false,
            is_load: true,
            page: 1,
            closeBox: function () {
                i._closeBox()
            },
            showBox: function () {
                i._showBox(j)
            },
            pageCtr: function (k) {
                (k > 0) ? j.page++ : j.page--;
                i._getInvite(j)
            },
            sendSMS: function (k) {
                i._sendSMS(k, j)
            }
        });
        return j
    };
    a.prototype._msgTip = function c(k, i, j) {
        var l = avalon.vmodels['sendTip'];
        if (k.status == 1001) {
            l.type = 'success';
            l.content = i
        } else {
            l.type = 'error';
            l.content = j
        }
        l.toggle = true
    };
    a.prototype._sendSMS = function c(i, k) {
        var j = this;
        if (i.length < 11) {
            j._msgTip({
                status: 4004
            }, '', '请输入手机号!')
        } else {
            var i = i.split('\n');
            var l = [
            ];
            $.each(i, function (n, o) {
                if (!/^(13[0-9]|14[0-9]|15[0-9]|18[0-9])\d{8}$/i.test(o)) {
                    l.push(n + 1)
                }
            });
            if (l.length > 0) {
                j._msgTip({
                    status: 4004
                }, '', '第 ' + l.join(',') + ' 条手机格式有误,请修正!');
                return
            }
            if (i.length > 50) {
                j._msgTip({
                    status: 4004
                }, '', '一次最多只能发送给50位朋友!');
                return
            }
            var m = {
                data: JSON.stringify(i),
                csrfmiddlewaretoken: j.csrf
            };
            $.post(j.api.sendSMS, m, function (n) {
            	if(n.message){alert(n.message);}
                j._msgTip(n, '恭喜！成功发送给' + i.length + '位朋友', '发送失败');
                k.is_send = false
            }, 'json')
        }
    };
    a.prototype._getInvite = function h(j) {
        var i = this;
        $.getJSON(i.api.invite, {
            page: j.page
        }, function (k) {
            if (k.status == 1001) {
                j.inviteList = k.data;
                j.is_load = true
            } else {
                j.is_load = false;
                j.page--
            }
        })
    };
    a.prototype._showBox = function f(k) {
        var j = this;
        var i = function () {
            $('.mask-box-main').animate({
                width: '100%',
                opacity: 0.98
            }, 350)
        };
        if ($('#_vmInvite').size()) {
            i()
        } else {
            d.async(['modules/qrcode'], function (o) {
                var l = '<div class="row"><div class="col-md-3 col-sm-4 bor-r"><div class="invite-box"><div class="sms-box"><div class="form-group"><label for="disabledTextInput">邀请人手机号</label><textarea class="form-control" ms-duplex="phoneList" rows="8" style="height: 250px" placeholder="每行一个手机号"></textarea></div><div ms-widget="notice,sendTip" data-notice-timer=2000 data-notice-title=""></div><div class="text-center"><button ms-click="sendSMS(phoneList)" ms-attr-disabled="is_send" class="btn btn-primary" ms-text="is_send ? \'发送中……\' : \'发送\'">发送</button></div></div><div class="text-center pt20"><p class="color9">分享到微信</p><div id="_qrcode" class="qrcode mt20"></div></div></div></div><div class="col-md-9 col-sm-8"><div class="invite-box" ><div ms-if="inviteList.length"><table class="table table-hover text-left"><thead><tr><th>姓名</th><th>手机号</th><th>留言</th><th>人数</th><th>出席</th></tr></thead><tbody><tr ms-repeat="inviteList"><th ms-text="el.name"></th><td ms-text="el.phone"></td><td ms-text="el.content"></td><td ms-text="el.part_num"></td><td ms-text="el.is_part"></td></tr></tbody></table></div><div ms-if="!inviteList.length" class="text-center mt60"><h1 class="color9">还没有朋友回应您的婚礼</h1></div><div class="mt30" ms-visible="inviteList"><nav><ul class="pager"><li ms-visible="page > 1"><a href="javascript:;" ms-click="pageCtr(-1)" >&larr; 上一页</a></li><li ms-visible="is_load && inviteList.length"><a href="javascript:;" ms-click="pageCtr(1)" >下一页 &rarr;</a></li></ul></nav></div></div></div></div>';
                var m = j.tpl.replace('MS_CONTENT', l);
                $('body').append(m);
                var n = (siteConfig.user.domain) ? 'http://' + siteConfig.user.domain + '.'+ sitedomain.replace('www.','') : siteConfig.host + Url('space/new/uid/'+siteConfig.user.id);
                var p = {
                    text: n,
                    width: 200,
                    height: 200,
                    render: 'canvas',
                    background: '#ffffff',
                    foreground: '#6B575C'
                };
                $('#_qrcode').html(new o(p));
                avalon.scan(document.getElementById('_vmInvite'), k);
                j._getInvite(k);
                i()
            })
        }
    };
    a.prototype._closeBox = function g() {
        $('.mask-box-main').animate({
            width: '0',
            opacity: 0
        }, 100)
    }
});
define('storyAdmin', [
], function (c, b, d) {
    function a(e) {
        this.config = e;
        var f = siteConfig.host;
        this.api = {
            get_story: Url('space/new/do/get_story'),
            set_story: Url('space/new/do/set_story'),
            set_shoot: Url('space/new/do/set_story'),
            get_parse_video: f + 'api/video_parse/',
            del_card_media: f + 'card/ajax/g/delete_media/',
            upload_img: Url('space/new/do/upload_img')
        };
        this.scene_text = [
            '慢慢熟悉，慢慢相爱，慢慢嫌弃，慢慢习惯，慢慢变老...慢慢其实很快，有你在真好。',
            '时光荏苒，那么多的事情都已随着时间消逝，但是我们的爱却历久弥新，始终不曾失掉它最有活力的岁月。',
            '一生最重要的三个日子：世界上有你的那天，世界上有我的那天，“我” 和 “你”成为 “我们”的那一天。',
            '我向来不相信什么永远，可我是真的想过要和你一起走下去。不管将来怎样，我要你明白：你开心时，我在。你难过时，我也在。',
            '春水初生，春林初盛，春风十里，不如你。',
            '愿无岁月可回头，且以深情共白首。',
            '我想和你虚度时光，比如低头看鱼，比如把茶杯留在桌子上，离开，浪费它们好看的阴影。我还想连落日一起浪费，比如散步，一直消磨到星光满天。',
            '永恒的定义太多，对于我的永恒，只不过是见你的每个瞬间。',
            '其实全世界最美好的童话，就是一起度过柴米油盐的岁月。',
            '人生最大的幸福，是发现自己爱的人正好也爱着自己。',
            '爱情终究要归于平淡，唯有那些动人瞬间让人永生难忘。我和你在一起，每一秒都很动人。',
            'Nothing’s gonna change my love for you，愿爱与时间一样，一分一秒走着，不断重复，永不褪去。',
            '再多的浮华也抵不过陪伴，有句话不是说“陪伴是最长情的告白”吗？我只想一直陪你走下去，做你的刃，做你的盾。你可以勇敢的往前走，因为我会一直在你身边。',
            '我见过春日夏风秋叶冬雪，也踏遍南水北山东麓西岭，可这四季春秋，苍山泱水，都不及你冲我展眉一眺。',
            '你像牙刷、像毛巾、像热水、像我每天出门要戴的隐形眼镜，融在我的日常里，是生活，是我的一部分，也是我的全部…',
            '你是我去年睡前想说话的最后一个人，也是我今年醒来想见到的第一个人。',
            '我想一直陪你走很久很久。久到那时的我都记不清你的名字，却还用宝贝一直称呼你。',
            '所有对未来的不确定里，你是我唯一的笃定。',
            '我这一辈子走过许多地方的路，行过许多地方的桥，看过许多次数的云，喝过许多种类的酒，却只爱过一个正当年龄的人，而那人，就是你。',
            '我想写封情书给你，纪念最单纯、真诚的我。爱有时，忘有时，真难得爱过你这么些年，都是唯一。',
            '从小到大，我一直相信，是我的总会是我的，从未努力替自己去争取什么。只有你，我第一次想去努力，用力，尽力的去争取。所幸，现在，你是我的了。',
            '爱是一件小事，以至于脑袋里装的全是你，对你的爱不多不少，只是希望今后的人生有你的陪伴，可以由青丝到白头，不管流言蜚语，只是希望最后牵我手的人还是你。',
            '我本想把日子过成诗，时而简单，时而精致。不料却过成了我们的歌，时而不靠谱，时而不着调...但是，有什么关系呢，咱们来日方长，以后的日子慢慢过。',
            '想为你揉揉肩膀，帮你扫去一天的疲惫，想在漆黑的夜里紧紧地抱着你，在明亮的早晨偷偷地吻醒你，想为你做很多事情，在未来的日子里...',
            '爱情，一共是21笔。亲情，一共是20笔。当我以为我之间的爱少了什么的时候，原来，是我们结婚了。',
            '我知道，我们的爱是彼此生命里一笔真正珍贵的财富，一笔永远只会太少而不会太多的财富。',
            '想到你就会满眼笑意，这就是幸福吧。',
            '最美的事情就是携着你的手，一起去看这个世界的美景。',
            '时间会告诉我们 ：简单的喜欢，最长远；平凡中的陪伴，最心安；懂你的人，最温暖；彼此相爱就是幸福。',
            '如果我们的爱情是一本书，我们会在开篇相遇，而结尾最后一章诉说的，都是我如此感激我们也携手共度一生的话语...',
            '感谢最好的年纪里，遇见你。',
            '时间是让人猝不及防的东西，晴时有风阴有时雨，争不过朝夕，又念着往昔，偷走了青丝却留住一个你。岁月是一场有去无回的旅行，好的坏的都是风景，别怪我贪心，只是不愿醒，因为你，只为你愿和我一起，看云淡风轻...',
            '择一城终老，遇一人白首。'
        ];
        this.csrf = document.getElementById('csrf').value
    }
    a.prototype = {
        msgTip: function (g, e, f) {
            var h = avalon.vmodels['saveTip'];
            if (g.status == 1001) {
                h.type = 'success';
                h.content = e
            } else {
                h.type = 'error';
                h.content = f
            }
            h.toggle = true
        },
        random: function (f, e) {
            return Math.floor(f + Math.random() * (e - f))
        },
        uploadImg: function (f, h, j) {
            var e = this,
            g = $('#_shoot-box');
            var i = new plupload.Uploader({
                runtimes: 'html5, flash',
                browse_button: document.getElementById(j),
                container: document.getElementById(h),
                max_file_size: '5mb',
                multi_selection: true,
                url: e.api.upload_img,
                flash_swf_url: '/static/js/plugins/upload.swf',
                filters: [
                    {
                        title: 'Image files',
                        extensions: 'jpg,jpeg,gif,png'
                    }
                ],
                headers: {
                    'X-CSRFToken': e.csrf,
                    'bucket': 'mt-card'
                }
            });
            i.init();
            i.bind('FilesAdded', function (k, m) {
                var l = [
                ];
                $.each(m, function (o, n) {
                    l.push('<div class="progress" id=', n.id, '>', '<div class="progress-bar" role="progressbar">', '</div></div>')
                });
                g.append(l.join(''));
                avalon.vmodels['$' + f.action + 'Box'].scrollTo(0, 90000);
                i.start()
            });
            i.bind('UploadProgress', function (k, l) {
                $('#' + l.id + ' .progress-bar').width(l.percent + '%').html(l.percent + '%')
            });
            i.bind('Error', function (k, l) {
                if (l.code == - 600) {
                    e.msgTip({
                        status: 4004
                    }, '', '[' + l.file.name + '] 上传失败,文件太大,请控制在5M以内!')
                }
            });
            i.bind('FileUploaded', function (l, o, k) {
                var m = $.parseJSON(k.response);
                var n = m.data.path;
                if (f.action == 'story') {
                    f.postList.push({
                        id: 0,
                        path: n,
                        content: e.scene_text[e.random(0, 32)]
                    })
                } else {
                    f.photoList.push({
                        id: 0,
                        path: n,
                        content: e.scene_text[e.random(0, 32)],
                        media_type: 1
                    })
                }
                avalon.vmodels['$' + f.action + 'Box'].scrollTo(0, 90000);
                $('#' + o.id).remove()
            });
            return i
        },
        renderTpl: function (i) {
            var e = this;
            var f = '<div class="row" ms-controll="vmSetStory" id="_vmSetStory"><div id="_edit-box"  class="col-md-6 col-md-offset-3"><div ms-visible="action==\'shoot\'"><div ms-widget="scrollbar,$shootBox" id="_shootBox" class="oni-helper-reset nobarbg mt10" style="height:500px;width: 100%;overflow:hidden;" data-scrollbar-position="right" data-scrollbar-show="scrolling" data-scrollbar-show-bar-header="false"><div class="oni-scrollbar-scroller" id="_shoot-box"><div ms-repeat="photoList" class="row mt20" data-repeat-rendered="upShoot"><div class="col-xs-4"><div class="story-pic h120"><a href="javascript:;" class="del pos-l-t" ms-click="delPic(el,$remove)"><i class="iconfont">&#xe621;</i></a><img class="img-responsive" ms-src="el.path+\'\'" /></div></div><div class="col-xs-8"><div class="story-desc"><textarea class="form-control h120" ms-duplex="el.content" rows="5"></textarea></div></div></div></div></div></div><div ms-visible="action==\'video\'"><div ms-if="!is_video"><div class="form-group"><input type="text" class="form-control" id="video" ms-duplex="video.path" placeholder="请输入视频地址,暂时只支持优酷"></div><div class="mt30 text-center"><a class="btn btn-primary" href="javascript:;" ms-click="parseVideo(video.path)">确定</a></div></div><div ms-if="is_video"><div class="video-box"><img ms-attr-src="video.content"/></div><div class="mt30 text-center"><a class="btn btn-default" href="javascript:;" ms-click="editVideo">修改</a></div></div></div><div class="mt10" ms-visible="action !=\'video\'"><div ms-widget="notice,saveTip,$saveConf" data-notice-timer=2000></div><div id="_photo-box"><div class="mt30"><a class="add_pic" id="_upload-photo" href="javascript:;"><span class="colorf58">上传照片</span></a></div></div><div class="mt30 text-center"><a class="btn btn-primary" ms-attr-disabled="is_load" ms-text="is_load ? \'发送中\' : \'确定\'" href="javascript:;" ms-click="setInfo(action)"></a></div></div></div></div>';
            if ($('#_vmSetStory').size()) {
                $('#_vmSetStory').show()
            } else {
                $('#_mask-main').append('<div ms-visible="controll == \'story\'" id="_vmStoryBox">');
                var h = avalon.vmodels['vMmask'];
                h.controll = 'story';
                avalon.scan(document.getElementById('_vmStoryBox'), h);
                $('#_vmStoryBox').append(f);
                avalon.scan(document.getElementById('_vmSetStory'), i)
            }
            var g = e.uploadImg(i, '_photo-box', '_upload-photo');
            $.getJSON(e.api.get_story, {
            }, function (k) {
                var l = k.data;
                if (k.status == 1001) {
                    i.photoList = l.image;
                    i.video = l.video;
                    if (l.video.path) {
                        i.is_video = true
                    }
                    $('#_shoot-box').animate({
                        width: '100%'
                    }, 800);
                    var j = $('.mask-box-main').height();
                    $('#_shootBox').height(j - 280);
                    i.upShoot()
                }
            })
        },
        set_callback: function () {
            var f = this.params.video,
            g = avalon.vmodels.vmSetStory,
            e = (f == 'story') ? 'share' : 'card';
            avalon.vmodels.vMmask ? avalon.vmodels.vMmask.action = f : '';
            g.action = f;
            avalon.vmodels['$shootBox'] && avalon.vmodels['$shootBox'].update()
        },
        renderVm: function () {
            var e = this,
            f = avalon.define({
                $id: 'vmSetStory',
                photoList: [
                ],
                video: {
                    id: 0,
                    path: '',
                    content: '',
                    media_type: 3
                },
                action: 'shoot',
                is_load: false,
                is_video: false,
                delPic: function (h, g) {
                    if (h.id) {
                        var i = {
                            id: h.id,
                            csrfmiddlewaretoken: e.csrf
                        };
                        $.post(e.api.del_card_media, i, function (j) {
                        	if(j.message){alert(j.message);}
                        }, 'json')
                    }
                    g()
                },
                $saveConf: {
                    title: '',
                    successClass: 'oni-notice-info'
                },
                setInfo: function (h) {
                    var g;
                    if (h == 'shoot') {
                        g = JSON.stringify(f.photoList.$model)
                    } else {
                        g = f.video
                    }
                    var i = {
                        data: g,
                        csrfmiddlewaretoken: e.csrf
                    };
                    f.is_load = true;
                    $.post(e.api['set_' + h], i, function (j) {
                    	if(j.message){alert(j.message);}
                        var k = j.data;
                        if (j.status == 1001) {
                            f.is_load = false;
                            f.photoList = k
                        }
                        e.msgTip(j, '信息保存成功', '信息设置失败')
                    }, 'json')
                },
                editVideo: function () {
                    f.is_video = false;
                    f.video.path = '';
                    f.video.content = ''
                },
                parseVideo: function (h) {
                    var h = $.trim(h),
                    g;
                    if (h.indexOf('youku.com') > - 1) {
                        $.getJSON(e.api.get_parse_video, {
                            url: h
                        }, function (i) {
                            if (!i.error) {
                                if (f.video.id) {
                                    g = f.video.id
                                } else {
                                    g = 0
                                }
                                var j = {
                                    path: 'http://player.youku.com/player.php/sid/' + i.id + '/v.swf',
                                    content: i.cover,
                                    id: g,
                                    media_type: 3
                                };
                                f.video = j;
                                var k = {
                                    data: JSON.stringify(j),
                                    csrfmiddlewaretoken: e.csrf
                                };
                                $.post(e.api.set_shoot, k, function (l) {
                                	if(l.message){alert(l.message);}
                                }, 'json');
                                f.is_video = true
                            } else {
                                alert(i.msg)
                            }
                        })
                    } else {
                    }
                },
                scrollbar: {
                },
                $skipArray: [
                    'scrollbar'
                ],
                upShoot: function () {
                    avalon.vmodels['$shootBox'] && avalon.vmodels['$shootBox'].update()
                }
            });
            return f
        },
        init: function () {
            var e = this;
            var g = e.renderVm();
            e.renderTpl(g);
            var f = function () {
                avalon.router.get('/set/:video', e.set_callback);
                avalon.router.get('/set/:shoot', e.set_callback);
                avalon.history.stop();
                avalon.history.start({
                    basepath: location.pathname
                })
            };
            f();
            avalon.router.navigate('/set/shoot')
        }
    };
    d.exports = {
        render: function (e) {
            new a(e).init()
        }
    }
});
define('editTheme', function (f, h, d) {
    var g = {
    },
    m,
    j,
    c;
    m = f('plugins/map');
    c = f('storyAdmin'),
    j = f('modules/search-result2');
    f('plugins/timeCircle');
    
    
    function i() {
        var q = [
            '',
            '#ef6e68',
            '#ffce98',
            '#b84e98',
            '#9bd8b6',
            '#78bfc9',
            '#403c48',
            '#bcd7b9',
            '#f8a796',
            '#fda694',
            '#f6735a'
        ],
        o = q[parseInt($('#home').data('theme_id'), 10)],
        s = $('#timeCircle'),
        p = false,
        r = $('#time_show').text();
        if (r) {
            s.data('date', b(r))
        }
        s.TimeCircles({
            circle_bg_color: o,
            fg_width: 0.045,
            bg_width: 1,
            endTime: function () {
                if (!p) {
                    p = true;
                    //$('#cutdonwTxt').text('我们已经结婚了')
                }
            },
            time: {
                Days: {
                    color: '#fff',
                    text: '天'
                },
                Hours: {
                    color: '#fff',
                    text: '时'
                },
                Minutes: {
                    color: '#fff',
                    text: '分'
                },
                Seconds: {
                    color: '#fff',
                    text: '秒'
                }
            }
        })
    }
    i();
    var e = f('artTemplate'),
    n = siteConfig.host;
    themeEdit = {
        api: {
            set_wedding: Url('space/new/do/update'),
            upload_img: Url('space/new/do/upload_img'),
            relevant: n + 'u/ajax/add_Wedding_relevant/'
        },
        attrs: {
            editDom: $('._theme-edit'),
            $limit: null,
            role: '',
            csrf: document.getElementById('csrf').value,
            iCur: {
                type: '',
                $dom: '',
                url: ''
            },
            uploader: null,
            tit: {
                my_name: '',
                other_name: '',
                my_des: '',
                other_des: '',
                hotel: '婚礼酒店',
                wedding_timestamp: '婚礼时间',
                wedding_address: '婚礼地点',
                cover: '合照背景图',
                co_photo: '上传合照',
                other_avatar: '头像',
                bridegroom_avatar: '头像'
            }
        },
        origin: {
            '0': {
                name: '服务分类'
            },
            '6': {
                name: '婚礼策划'
            },
            '7': {
                name: '婚礼摄影'
            },
            '8': {
                name: '化妆造型'
            },
            '9': {
                name: '婚纱礼服'
            },
            '10': {
                name: '花艺设计'
            },
            '11': {
                name: '婚纱写真'
            },
            '12': {
                name: '婚礼摄像'
            },
            '14': {
                name: '婚礼主持'
            },
            '17': {
                name: '甜品婚品'
            },
            '22': {
                name: '海外婚礼'
            }
        },
        init: function () {
            this.attrs.role = $('#theme-box').data('gender');
            this.spitTime($('#wed-time').data('time'));
            this._getTitle();
            this.initDom();
            this.initVm();
            this._editEvt();
            this.initMap('fir', 'mapBox', '')
        },
        initDom: function () {
            var o = e('editDialog-tpl', {
            });
            $('body').append(o);
            this.attrs.$limit = $('._limit');
            this.markEdit()
        },
        initVm: function () {
            var o = this,
            r = avalon.define({
                $id: 'dialogCtr',
                $opt: {
                    width: 476,
                    getFooter: function () {
                        return '<p class="confirm-ft"><a class="btn btn-primary mt10 mb30" href="javascript:;" ms-click="sendData">保存</a></p>'
                    },
                    onClose: function () {
                        o._resetIcur()
                    },
                    zIndex: 202
                },
                sendData: function () {
                    o._handleData()
                },
                $update: function () {
                    var s = $('._theme-edit');
                    s.append('<b class="edit-bg"><i class="iconfont _edit-icon">&#xe627;</i></b>');
                    o.spitTime($('#wed-time').data('time'));
                    i();
                    avalon.scan(document.getElementById('relevantCtr'), p);
                    o._updateVm(p, 'relevant');
                    o._updateVm(avalon.vmodels.editCtr);
                    o.markEdit(s);
                    o.initMap('fir', 'mapBox', '')
                }
            });
            avalon.scan(document.getElementById('editDialog'), r);
            var q = avalon.define({
                $id: 'editCtr',
                my_name: '',
                my_name_s: false,
                other_name: '',
                other_name_s: false,
                wedding_timestamp: '',
                wedding_timestamp_s: false,
                my_des: '',
                my_des_s: false,
                other_des: '',
                other_des_s: false,
                wedding_address: '',
                wedding_address_s: false,
                relevant_id: 0,
                relevant_name: '',
                relevant_su: [
                ],
                relevant_su_s: false,
                relevant_hotel: {
                },
                relevant_hotel_s: false,
                hotel: '',
                hotel_s: false,
                img_s: false,
                addRole: function () {
                    if (!q.relevant_id) {
                        alert('请选择服务类别');
                        return
                    }
                    var s = {
                        relevant_id: 0,
                        relevant_name: q.relevant_name,
                        relevant_type: q.relevant_id,
                        relevant_role: o.origin[q.relevant_id + ''].name
                    };
                    q.relevant_su.push(s)
                },
                confirm: function (t) {
                    if (t.type == 'keyup' && t.which !== 13) {
                        return
                    }
                    var s = q.wedding_address;
                    g['sec'].MarkerPoint('', false, s)
                }
            });
            avalon.scan(document.getElementById('themeEdit'), q);
            q.$watch('my_des', function (s) {
                o._limitContent(s, 1, 'my_des')
            });
            q.$watch('other_des', function (s) {
                o._limitContent(s, 2, 'other_des')
            });
            o._updateVm(q);
            var p = avalon.define({
                $id: 'relevantCtr',
                relevant_su: [
                ],
                relevant_hotel: {
                }
            });
            avalon.scan(document.getElementById('relevantCtr'), p);
            o._updateVm(p, 'relevant')
        },
        initMap: function (r, q, t) {
            var p,
            s,
            o;
            s = $('#map_address').find('em');
            p = s.text();
            o = s.data('point');
            if (!p) {
                p = '杭州市'
            }
            g[r] = m.render({
                mapbox: q,
                autocomplete: t,
                address: p,
                drag: false,
                point: o
            })
        },
        _resetIcur: function () {
            var p = this.attrs.iCur,
            o = avalon.vmodels.editCtr;
            o[p.type + '_s'] = false;
            if (k(p.type)) {
                o.img_s = false
            }
            p.type = '';
            p.url = ''
        },
        markEdit: function (o) {
            var p;
            if (o) {
                p = o
            } else {
                p = $('._theme-edit')
            }
            p.each(function (q, r) {
                $(r).addClass('flash')
            })
        },
        uploadImg: function (r, q) {
            var o = this,
            p = o.attrs;
            p.uploader = new plupload.Uploader({
                runtimes: 'html5, flash',
                browse_button: q,
                container: r,
                max_file_size: '5mb',
                multi_selection: false,
                url: o.api.upload_img,
                flash_swf_url: '/static/js/plugins/upload.swf',
                filters: [
                    {
                        title: 'Image files',
                        extensions: 'jpg,jpeg,gif,png'
                    }
                ],
                headers: {
                    'X-CSRFToken': o.attrs.csrf,
                    'bucket': 'mt-card'
                }
            });
            p.uploader.init();
            p.uploader.bind('FilesAdded', function (s, t) {
                p.uploader.start();
                $('#progress').fadeIn()
            });
            p.uploader.bind('UploadProgress', function (s, t) {
                $('#progress').width(t.percent + '%')
            });
            p.uploader.bind('Error', function (s, t) {
                if (t.code == - 600) {
                    alert('[' + t.file.name + '] 上传失败,文件太大,请控制在5M以内!')
                }
            });
            p.uploader.bind('FileUploaded', function (t, v, s) {
                var u = $.parseJSON(s.response),
                w = u.data.path;
                $('#progress').fadeOut();
                $('#imgUrl').attr('src', w);
                o.attrs.iCur.url = w
            })
        },
        _limitContent: function (q, p, r) {
            var o = $('#_limit' + p);
            q = q + '';
            if (q) {
                if (q.length > 150) {
                    o.text(0);
                    avalon.vmodels.editCtr[r] = q.substr(0, 150)
                } else {
                    o.text((150 - q.length))
                }
            }
        },
        _editEvt: function () {
            var p = this,
            o = $('._theme-edit');
            o.append('<b class="edit-bg"><i class="iconfont _edit-icon">&#xe627;</i></b>');
            $(document).delegate('._edit-icon', 'click', function (w) {
                var q = $(this).parents('._theme-edit'),
                s = $(this).parent(),
                u = q.data('type'),
                v = k(u),
                r = $('#imgUrl'),
                x;
                if (u == 'story') {
                    p.showSiderBar();
                    return
                }
                avalon.vmodels.edit_theme.title = p.attrs.tit[u];
                if (u.indexOf('des') > 0) {
                    x = q.prev().text()
                } else {
                    if (v) {
                        r.attr('src', '');
                        if (u == 'cover') {
                            x = q.data('val')
                        } else {
                            x = s.prev().attr('src')
                        }
                    } else {
                        x = s.prev().text()
                    }
                }
                var t = avalon.vmodels.editCtr;
                if (v) {
                    t['img_s'] = true;
                    if (x) {
                        r.attr('src', x);
                        p.attrs.iCur.url = x
                    }
                    if (!p.attrs.uploader) {
                        p.uploadImg(document.getElementById('upload-box'), document.getElementById('upload'))
                    }
                } else {
                    if (u.indexOf('relevant') > - 1) {
                        x = a(u);
                        if (u == 'relevant_hotel') {
                            avalon.vmodels.search_list.name = x.relevant_name
                        }
                    }
                    if (u == 'wedding_timestamp') {
                        x = s.prev().data('time')
                        if (!x) {
                            x = (new Date()).toLocaleDateString().split('/').join('.')
                        }
                    }
                    if (x) {
                        t[u] = x
                    }
                    t[u + '_s'] = true;
                    if (u == 'wedding_timestamp') {
                        if (!$('#datetimepicker').data('DateTimePicker')) {
                            $('#datetimepicker').datetimepicker({
                                language: 'zh-cn'
                            })
                        }
                    }
                }
                p.attrs.iCur.type = u;
                p.attrs.iCur.$dom = s;
                avalon.vmodels.edit_theme.toggle = true;
                if (!g['sec'] && u == 'wedding_address') {
                    p.initMap('sec', 'mapcontain', 'map-autocomplete2')
                }
            })
        },
        showSiderBar: function () {
            var o = avalon.vmodels['vMmask'];
            o.controll = 'story';
            o.setNav('story');
            o.showMask();
            avalon.vmodels['vmSetStory'] || c.render()
        },
        _handleData: function () {
            var o = this,
            s = o.attrs.iCur,
            q = s.type,
            r;
            if (k(q)) {
                r = l(s.url);
                if (!r) {
                    alert('您还没有上传图片')
                }
            } else {
                if (q == 'wedding_timestamp') {
                    r = $('#datetimepicker').val()
                } else {
                    var p = avalon.vmodels.editCtr;
                    if ((typeof p[q] == 'string') && !$.trim(p[q])) {
                        alert('您没有填写任何消息');
                        return
                    }
                    if (q == 'wedding_address') {
                        g['sec'].parseAddress(p[q], function (t) {
                            r = [
                                t.lng,
                                t.lat
                            ];
                            o._sendData(q, r, 'point')
                        })
                    }
                    r = p[q]
                }
            }
            o._sendData(q, r)
        },
        _sendData: function (r, s, o) {
            var p = this,
            t,
            u,
            q;
            if (r == 'wedding_address' && o) {
                r = 'wedding_map_point'
            }
            if (r.indexOf('relevant') > - 1) {
                if (r == 'relevant_hotel') {
                    s = j.getData().postData;
                    u = {
                        data: JSON.stringify({
                            relevant_id: s.id,
                            relevant_name: s.name,
                            relevant_type: 2
                        }),
                        csrfmiddlewaretoken: this.attrs.csrf
                    }
                } else {
                    u = {
                        data: JSON.stringify(s.$model),
                        csrfmiddlewaretoken: this.attrs.csrf
                    }
                }
                q = p.api.relevant
            } else {
                //u = 'keyword=' + r + '&' + 'value=' + s + '&csrfmiddlewaretoken=' + this.attrs.csrf;
                u = r+'='+s;
                q = p.api.set_wedding
            }
            $.post(q, u, function (v) {
            	if(v.message){
					alert(v.message);
				}
				if (v.status == 1001) {
                    p._updateView(r, s);
                    avalon.vmodels.edit_theme.toggle = false;
                    if (o) {
                        return
                    }
                    t = p.attrs.iCur.$dom.parents('._theme-edit');
                    if (t.hasClass('flash')) {
                        t.removeClass('flash')
                    }
                }
            }, 'json')
        },
        _updateView: function (r, t) {
            var q = this.attrs.iCur.$dom,
            o = this;
            if (k(r)) {
                if (r == 'cover') {
                    $('#cover_bg').css('background-image', 'url(' + t + ')')
                } else {
                    var u = r.indexOf('avatar') > 0 ? '250x250' : '220x220';
                    //q.prev().attr('src', t + '!' + u)
                    q.prev().attr('src', t )
                }
            } else {
                if (r == 'wedding_timestamp') {
                    $('#timeCircle').data('date', b(t)).TimeCircles().restart();
                    $('#time_show').text(t);
                    o.spitTime(t);
                    return
                }
                if (r.indexOf('relevant') > - 1) {
                    var p;
                    if (r == 'relevant_hotel') {
                        p = {
                            relevant_id: t.id,
                            relevant_name: t.name,
                            relevant_type: 2
                        }
                    } else {
                        p = t
                    }
                    avalon.vmodels.relevantCtr[r] = p;
                    return
                }
                if (r.indexOf('des') > 0) {
                    $('#' + r).text(t)
                } else {
                    if (r == 'wedding_map_point') {
                        var s = new BMap.Point(parseFloat(t[0]), parseFloat(t[1]));
                        g['fir'].MarkerPoint(s, true)
                    } else {
                        q.prev().text(t)
                    }
                }
            }
            if (r.indexOf('name') > 0) {
                $('#' + r).text(t)
            }
        },
        spitTime: function (o) {
            if (o) {
                $('#wed-time').text(o.split(' ') [0])
            }
        },
        _getTitle: function () {
            var o = this.attrs.tit,
            p = this.attrs.role;
            o.my_name = p == 'm' ? '另一半' : '我';
            o.other_name = p == 'm' ? '我' : '另一半';
            o.my_des = p == 'm' ? '另一半的描述' : '我的描述';
            o.other_des = p == 'm' ? '我的描述' : '另一半的描述'
        },
        _updateVm: function (q, p) {
            if (!p) {
                if (q.wedding_timestamp) {
                    q.wedding_timestamp = ''
                }
                q.wedding_timestamp = $('#wed-time').text();
                if (!avalon.vmodels['_hotel-wrap']) {
                    j.render({
                        id: '_hotel-wrap',
                        url: '/search/hotel/',
                        vm_attr: [
                            {
                                name: '$id',
                                value: 'search_list'
                            },
                            {
                                name: 'name',
                                value: ''
                            },
                            {
                                name: 'id',
                                value: 0
                            },
                            {
                                name: 'type',
                                value: 'hotel'
                            }
                        ]
                    })
                }
            } else {
                var o = $('#relevant');
                q.relevant_su = o.data('relevant_su');
                if (o.data('relevant_hotel')) {
                    q.relevant_hotel = o.data('relevant_hotel')
                }
            }
        }
    };
    function a(o) {
        return avalon.vmodels.relevantCtr[o].$model
    }
    function b(p) {
        var r = p.split(' '),
        o = r[0],
        q = r[1];
        o = o.split('.').join('-');
        q = r[1] + ':00';
        return o + ' ' + q
    }
    function k(o) {
        if (o.indexOf('avatar') > 0 || o == 'cover') {
            return true
        }
    }
    function l(p) {
        var o = p.indexOf('!');
        if (o > 0) {
            p = p.substr(0, p.indexOf('!'))
        }
        return p
    }
    d.exports = {
        render: function () {
            themeEdit.init()
        }
    }
});
