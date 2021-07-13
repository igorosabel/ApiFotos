<?php foreach ($values['list'] as $i => $photo): ?>
  {
    "id": <?php echo $photo->get('id') ?>,
    "thumb": "<?php echo $photo->getThumbUrl() ?>",
    "img": "<?php echo $photo->getPhotoUrl() ?>",
    "tags": [
<?php foreach ($photo->getTags() as $j => $tag): ?>
      {
        "id": <?php echo $tag->get('id') ?>,
        "tag": "<?php echo urlencode($tag->get('tag')) ?>"
      }<?php if ($j<count($photo->getTags()) -1): ?>,<?php endif ?>
<?php endforeach ?>
    ]
  }<?php if ($i<count($values['list']) -1): ?>,<?php endif ?>
<?php endforeach ?>
