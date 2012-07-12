<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    <?php echo Asset::css(array('bootstrap.min.css', 'bootstrap-responsive.min.css')); ?>
    <style>
        body { margin: 50px; }
        footer { padding: 50px 0 20px; }
    </style>
    <?php echo Asset::js(array(
    'http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js',
    'bootstrap.js'
)); ?>
    <script>
        $(function(){ $('.topbar').dropdown(); });
    </script>
</head>
<body>

<?php if ($current_user): ?>
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="#">Application name</a>
            <div class="nav-collapse">
                <ul class="nav">
                    <li class="<?php echo Uri::segment(2) == '' ? 'active' : '' ?>">
                        <?php echo Html::anchor('admin', 'Dashboard') ?>
                    </li>
                    <?php foreach (glob(APPPATH.'classes/controller/admin/*.php') as $controller): ?>

                    <?php
                    $section_segment = basename($controller, '.php');
                    $section_title = Inflector::humanize($section_segment);
                    ?>

                    <li class="<?php echo Uri::segment(2) == $section_segment ? 'active' : '' ?>">
                        <?php echo Html::anchor('admin/'.$section_segment, $section_title) ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div><!--/.nav-collapse -->
            <ul class="nav pull-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $current_user['first_name'] . ' ' . $current_user['last_name']?><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><?php echo Html::anchor('admin/logout', 'Logout') ?></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
    <?php endif; ?>

<div class="container">
    <div class="row">
        <div class="span12">
            <h1><?php echo $title; ?></h1>
            <hr>
            <?php if (Session::get_flash('success')): ?>
            <div class="alert alert-success">
                <?php echo Session::get_flash('success'); ?>
            </div>
            <?php endif; ?>
            <?php if (Session::get_flash('info')): ?>
            <div class="alert alert-info">
                <?php echo Session::get_flash('info'); ?>
            </div>
            <?php endif; ?>
            <?php if (Session::get_flash('error')): ?>
            <div class="alert alert-error">
                <?php echo Session::get_flash('error'); ?>
            </div>
            <?php endif; ?>
        </div>
        <div class="span12">
            <?php echo $content; ?>
        </div>
    </div>
    <footer>
        <p class="pull-right">Page rendered in {exec_time}s using {mem_usage}mb of memory.</p>
        <p>
            Built by <?php echo Html::mail_to_safe('dave@dganley.net', 'Dave Ganley'); ?> for <?php echo Html::anchor('http://#', 'Client name', array('target' => '_blank')); ?>
        </p>
    </footer>
</div>
</body>
</html>
