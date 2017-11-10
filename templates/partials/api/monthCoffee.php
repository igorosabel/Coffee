<?php foreach ($values['list'] as $i => $coffee): ?>
  {
    "d": <?php echo $coffee->get('d') ?>,
    "id_person": <?php echo $coffee->getPaid()->get('id') ?>,
    "people": [<?php echo implode(',', $coffee->getPeopleIds()) ?>]
  }<?php if ($i<count($values['list'])-1): ?>,<?php endif ?>
<?php endforeach ?>