<?php echo Form::open(array()); ?>

<?php if (isset($_GET['destination'])): ?>
<?php echo Form::hidden('destination',$_GET['destination']); ?>
<?php endif; ?>

<div class="control-group<?php if ($val->errors('login')) echo ' error'; ?>">
    <label for="login">Login:</label>
    <div class="controls">
        <?php echo Form::input('login', Input::post('login')); ?>
        <?php if ($val->errors('login')): ?>
        <span class="help-inline"><?php echo $val->errors('login')->get_message(); ?></span>
        <?php endif; ?>
    </div>
</div>

<div class="control-group<?php if ($val->errors('email')) echo ' error'; ?>">
    <label for="email">Email:</label>
    <div class="controls">
        <?php echo Form::input('email', Input::post('email')); ?>
        <?php if ($val->errors('email')): ?>
        <span class="help-inline"><?php echo $val->errors('email')->get_message(); ?></span>
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

<div class="control-group<?php if ($val->errors('confirm_password')) echo ' error'; ?>">
    <label for="confirm_password">Confirm Password:</label>
    <div class="controls">
        <?php echo Form::password('confirm_password'); ?>
        <?php if ($val->errors('confirm_password')): ?>
        <span class="help-inline"><?php echo $val->errors('confirm_password')->get_message(); ?></span>
        <?php endif; ?>
    </div>
</div>

<?php echo Form::submit(array('value'=>'Register', 'name'=>'submit', 'class' => 'btn btn-primary')); ?>

<?php echo Form::close(); ?>