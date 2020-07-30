<?php $cont = 0; ?>
<?php foreach ($values['people'] as $person): ?>
	"person_<?php echo $person->get('id') ?>": {
		"id": <?php echo $person->get('id') ?>,
		"name": "<?php echo urlencode($person->get('name')) ?>",
		"num_coffee": <?php echo $person->get('num_coffee') ?>,
		"num_pay": <?php echo $person->get('num_pay') ?>,
		"num_special": <?php echo $person->get('num_special') ?>,
		"num_special_pay": <?php echo $person->get('num_special_pay') ?>,
		"color": "<?php echo $person->get('color') ?>",
		"did_go": <?php echo $person->getDidGo() ? 'true' : 'false' ?>
	}<?php if ($cont<count($values['people'])-1): ?>,<?php endif ?>
<?php $cont++; ?>
<?php endforeach ?>