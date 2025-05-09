<?php
session_start(); 

if (isset($_SESSION['mensaje_exito'])): ?>
<div class="alert alert-success alert-dismissible fade show text-cenetr fw-bold" role="alert">
    <?php echo $_SESSION['mensaje_exito']; ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php unset($_SESSION['mensaje_exito']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['mensaje_error'])): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo $_SESSION['mensaje_error']; ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php unset($_SESSION['mensaje_error']); ?>
<?php endif; ?>