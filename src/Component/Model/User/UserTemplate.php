<?php if (is_null($user)): ?>
null
<?php else: ?>
{
	"id": <?php echo $user->get('id') ?>,
	"username": "<?php echo urlencode($user->get('username')) ?>",
	"pass": "<?php echo urlencode($user->get('pass')) ?>",
	"name": "<?php echo urlencode($user->get('name')) ?>",
	"isAdmin": <?php echo $user->get('is_admin') ? 'true' : 'false' ?>,
	"createdAt": "<?php echo $user->get('created_at', 'd/m/Y H:i:s') ?>",
	"updatedAt": "<?php echo is_null($user->get('updated_at')) ? 'null' : $user->get('updated_at', 'd/m/Y H:i:s') ?>"
}
<?php endif ?>
