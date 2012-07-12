<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo isset($title) ? $title : 'Page title'; ?></title>
    <?php echo Asset::css('style.css'); ?>
    <?php echo Asset::js('modernizr-2.5.3.min.js'); ?>
</head>
<body>
<?php if(isset($content)) echo $content; ?>
<div id="fb-root"></div>
<script src="//connect.facebook.net/en_US/all.js"></script>
<script>
    var BASE_URL = '<?php echo Config::get('base_url'); ?>';
    var FB_APP_URL = '<?php echo Input::protocol() . '://facebook.com/' . Config::get('application.facebook.page') . '/app_' . Config::get('application.facebook.app_id'); ?>';
    var FB_APP_ID = '<?php echo Config::get('application.facebook.app_id'); ?>';
    var FB_APP_PERMS = '<?php echo Config::get('application.facebook.perms'); ?>';
    var FB_APP_NAMESPACE = '<?php echo Config::get('application.facebook.namespace'); ?>';
</script>
<?php
$js_files = array(
    '//ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js',
    'plugins.js',
    'script.js'
);
echo Asset::js($js_files); ?>
<script>
    FB.init({
        appId: FB_APP_ID,
        status: true,
        cookie: true,
        xfbml: true,
        oauth : true
    });

    FB.Canvas.setAutoGrow();

    /*
    FB.Event.subscribe('edge.create', function(href, widget) {
        window.top.location = '<?php //echo Input::protocol() . '://facebook.com/' . Config::get('facebook.page') . '/app_' . Config::get('application.facebook.app_id'); ?>';
    });
    */
    var _gaq=[['_setAccount','<?php echo Config::get('application.google.ua'); ?>'],['_trackPageview']];
    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
    g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
    s.parentNode.insertBefore(g,s)}(document,'script'));
</script>
</body>
</html>
