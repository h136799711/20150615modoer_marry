seajs.config({
    base: 'http://www.5wed.com/static/javascript', 
    paths: {
    'love': 'http://s.lovewith.me/static/js',
    'here': 'http://www.5wed.com/static/javascript',
  	},
    timestamp: '201505212330',
    alias: {
        jquery: 'jquery/jquery.js',
        'jquery-ui': 'jquery/jquery-ui-min.js',
        handlebars: 'lib/handlebars/handlebars.min.js',
        datetimepicker: 'lib/bootstrap3-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
        moment: 'lib/moment/min/moment.min.js',
        collapse: 'lib/collapse.js',
        'perfect-scrollbar': 'plugins/perfect-scrollbar/perfect-scrollbar.min.js',
        avalon: 'lib/avalon/avalon.js',
        mmRouter: 'lib/avalon/mmRouter.js',
        mmHistory: 'lib/avalon/mmHistory.js',
        dialog: 'lib/avalon/dialog/avalon.dialog.js',
        artTemplate: 'lib/artTemplate.js'
    },
    debug: !0,
    preload: [
        'jquery'
    ],
    charset: 'utf-8',
    map: [
        [/^(.*\.(?:css|js))(.*)$/i,
        '$1?v=3.1.39']
    ]
});

