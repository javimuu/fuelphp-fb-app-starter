<?php echo Form::open(array()); ?>

<div class="control-group<?php if ($val->errors('login')) echo ' error'; ?>">
    <label for="login">Login:</label>
    <div class="controls">
        <?php echo Form::input('login', Input::post('login')); ?>
        <?php if ($val->errors('login')): ?>
        <span class="help-inline"><?php echo $val->errors('login')->get_message(); ?></span>
        <?php endif; ?>
    </div>
</div>

<div class="control-group<?php if ($val->errors('password')) echo ' error'; ?>">
    <label for="password">Password:</label>
    <div class="controls">
        <?php echo Form::password('password'); ?>
        <?php if ($val->errors('password')): ?>
        <span class="help-inline"><?php echo $val->errors('password')->get_message(); ?></span>
        <?php endif; ?>
    </div>
</div>

<?php echo Form::submit(array('value'=>'Reset', 'name'=>'submit', 'class' => 'btn btn-primary')); ?>

<?php echo Form::close(); ?>