<?php if (is_null($values['Photo'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['Photo']->get('id') ?>,
	"thumb": "<?php echo $values['Photo']->getThumbUrl() ?>",
	"img": "<?php echo $values['Photo']->getPhotoUrl() ?>",
	"date": <?php echo strtotime($values['Photo']->get('when', 'j F Y H:i:s')) ?>,
	"tags": [
		<?php foreach ($values['Photo']->getTags() as $j => $tag): ?>
			{
				"id": <?php echo $tag->get('id') ?>,
				"tag": "<?php echo urlencode($tag->get('tag')) ?>"
			}<?php if ($j<count($values['Photo']->getTags()) -1): ?>,<?php endif ?>
		<?php endforeach ?>
		]
}
<?php endif ?>
