<?php $cont = 0; ?>
<?php foreach ($values['list'] as $i => $day): ?>
	{
		"d": <?php echo $i ?>,
		"list": [
<?php foreach ($day as $j => $coffee): ?>
			{
				"id": <?php echo $coffee->get('id') ?>,
				"id_person": <?php echo $coffee->getPaid()->get('id') ?>,
				"people": [<?php echo implode(',', $coffee->getPeopleIds()) ?>]
			}<?php if ($j<count($day)-1): ?>,<?php endif ?>
<?php endforeach ?>
		]
	}<?php if ($cont<count($values['list'])-1): ?>,<?php endif ?>
<?php $cont++; ?>
<?php endforeach ?>