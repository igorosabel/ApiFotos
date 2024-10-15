<?php if (is_null($photo)): ?>
null
<?php else: ?>
{
	"id": <?php echo $photo->get('id') ?>,
	"thumb": "<?php echo $photo->getThumbUrl() ?>",
	"img": "<?php echo $photo->getPhotoUrl() ?>",
	"date": <?php echo strtotime($photo->get('when', 'j F Y H:i:s')) ?>,
	"tags": [
		<?php foreach ($photo->getTags() as $j => $tag): ?>
			{
				"id": <?php echo $tag->get('id') ?>,
				"tag": "<?php echo urlencode($tag->get('tag')) ?>"
			}<?php if ($j<count($photo->getTags()) -1): ?>,<?php endif ?>
		<?php endforeach ?>
		]
}
<?php endif ?>
