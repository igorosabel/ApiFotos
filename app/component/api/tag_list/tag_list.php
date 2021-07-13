<?php foreach ($values['list'] as $i => $tag): ?>
  {
    "id": <?php echo $tag->get('id') ?>,
    "tag": "<?php echo urlencode($tag->get('tag')) ?>"
  }<?php if ($i<count($values['list']) -1): ?>,<?php endif ?>
<?php endforeach ?>
