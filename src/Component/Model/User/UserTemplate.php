<?php if (is_null($user)): ?>
null
<?php else: ?>
{
	"id": <?php echo $user->id ?>,
	"username": "<?php echo urlencode($user->username) ?>",
	"pass": "<?php echo urlencode($user->pass) ?>",
	"name": "<?php echo urlencode($user->name) ?>",
	"isAdmin": <?php echo $user->is_admin ? 'true' : 'false' ?>,
	"createdAt": "<?php echo $user->get('created_at', 'd/m/Y H:i:s') ?>",
	"updatedAt": "<?php echo is_null($user->updated_at) ? 'null' : $user->get('updated_at', 'd/m/Y H:i:s') ?>"
}
<?php endif ?>
