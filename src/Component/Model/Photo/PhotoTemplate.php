<?php if (is_null($photo)): ?>
null
<?php else: ?>
{
	"id": <?php echo $photo->id ?>,
	"thumb": "<?php echo $photo->getThumbUrl() ?>",
	"img": "<?php echo $photo->getPhotoUrl() ?>",
	"date": <?php echo strtotime($photo->get('when', 'j F Y H:i:s')) ?>,
	"tags": [
		<?php foreach ($photo->getTags() as $j => $tag): ?>
			{
				"id": <?php echo $tag->id ?>,
				"tag": "<?php echo urlencode($tag->tag) ?>"
			}<?php if ($j < count($photo->getTags()) -1): ?>,<?php endif ?>
		<?php endforeach ?>
		]
}
<?php endif ?>
