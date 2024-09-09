<?php if (is_null($values['Tag'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['Tag']->get('id') ?>,
	"tag": "<?php echo urlencode($values['Tag']->get('tag')) ?>",
	"slug": "<?php echo urlencode($values['Tag']->get('slug')) ?>",
	"createdAt": "<?php echo $values['Tag']->get('created_at', 'd/m/Y H:i:s') ?>",
	"updatedAt": "<?php echo is_null($values['Tag']->get('updated_at')) ? 'null' : $values['Tag']->get('updated_at', 'd/m/Y H:i:s') ?>"
}
<?php endif ?>
