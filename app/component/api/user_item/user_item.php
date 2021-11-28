<?php if (is_null($values['user'])): ?>
null
<?php else: ?>
	{
		"id": <?php echo $values['user']->get('id') ?>,
		"username": "<?php echo urlencode($values['user']->get('username')) ?>",
		"name": "<?php echo urlencode($values['user']->get('name')) ?>",
		"token": null,
		"isAdmin": <?php echo $values['user']->get('is_admin') ? 'true' : 'false' ?>
	}
<?php endif ?>