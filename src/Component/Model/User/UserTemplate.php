<?php if (is_null($values['User'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['User']->get('id') ?>,
	"username": "<?php echo urlencode($values['User']->get('username')) ?>",
	"pass": "<?php echo urlencode($values['User']->get('pass')) ?>",
	"name": "<?php echo urlencode($values['User']->get('name')) ?>",
	"isAdmin": <?php echo $values['User']->get('is_admin') ? 'true' : 'false' ?>,
	"createdAt": "<?php echo $values['User']->get('created_at', 'd/m/Y H:i:s') ?>",
	"updatedAt": "<?php echo is_null($values['User']->get('updated_at')) ? 'null' : $values['User']->get('updated_at', 'd/m/Y H:i:s') ?>"
}
<?php endif ?>
