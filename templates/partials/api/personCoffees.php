<?php foreach ($values['list'] as $i => $coffee): ?>
  {
    "id": <?php echo $coffee->get('id') ?>,
    "d": <?php echo $coffee->get('d') ?>,
    "m": <?php echo $coffee->get('m') ?>,
    "y": <?php echo $coffee->get('y') ?>,
    "special": <?php echo $coffee->get('special') ? 'true' : 'false' ?>,
    "id_person": <?php echo $coffee->get('id_person') ?>
  }<?php if ($i<count($values['list'])-1): ?>,<?php endif ?>
<?php endforeach ?>