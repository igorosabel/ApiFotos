<?php if (is_null($tag)): ?>
null
<?php else: ?>
{
	"id": <?php echo $tag->id ?>,
	"tag": "<?php echo urlencode($tag->tag) ?>",
	"slug": "<?php echo urlencode($tag->slug) ?>",
	"createdAt": "<?php echo $tag->get('created_at', 'd/m/Y H:i:s') ?>",
	"updatedAt": "<?php echo is_null($tag->updated_at) ? 'null' : $tag->get('updated_at', 'd/m/Y H:i:s') ?>"
}
<?php endif ?>
