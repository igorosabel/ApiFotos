<?php if (is_null($values['photo'])): ?>
null
<?php else: ?>
	{
		"id": <?php echo $values['photo']->get('id') ?>,
		"thumb": "<?php echo $values['photo']->getThumbUrl() ?>",
		"img": "<?php echo $values['photo']->getPhotoUrl() ?>",
		"tags": [
		<?php foreach ($values['photo']->getTags() as $j => $tag): ?>
			{
				"id": <?php echo $tag->get('id') ?>,
				"tag": "<?php echo urlencode($tag->get('tag')) ?>"
			}<?php if ($j<count($values['photo']->getTags()) -1): ?>,<?php endif ?>
		<?php endforeach ?>
		]
	}
<?php endif ?>