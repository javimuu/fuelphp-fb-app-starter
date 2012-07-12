<?php echo Form::open(array()); ?>

<?php if (isset($_GET['destination'])): ?>
<?php echo Form::hidden('destination',$_GET['destination']); ?>
<?php endif; ?>

<div class="control-group<?php if ($val->error('login')) echo ' error'; ?>">
    <label for="login">Login:</label>
    <div class="controls">
        <?php echo Form::input('login', Input::post('login')); ?>
        <?php if ($val->error('login')): ?>
        <span class="help-inline"><?php echo $val->error('login')->get_message(); ?></span>
        <?php endif; ?>
    </div>
</div>

<div class="control-group<?php if ($val->error('password')) echo ' error'; ?>">
    <label for="password">Password:</label>
    <div class="controls">
        <?php echo Form::password('password'); ?>
        <?php if ($val->error('password')): ?>
        <span class="help-inline"><?php echo $val->error('password')->get_message(); ?></span>
        <?php endif; ?>
    </div>
</div>

<?php echo Form::submit(array('value'=>'Login', 'name'=>'submit', 'class' => 'btn btn-primary')); ?>

<?php echo Form::close(); ?>