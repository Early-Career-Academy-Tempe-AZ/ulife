<? if ($action == "cancel" && $action_status == TRUE):
alert(ALERT_LEVEL_SUCCESS, "Die Ausbildung eines Bürgers zur Einheit wurde abgebrochen!");
elseif ($action == "cancel" && $action_status == FALSE):
alert(ALERT_LEVEL_DANGER, "Die Ausbildung eines Bürgers zur Einheit konnte nicht abgebrochen werden!");
endif;
if (empty($units)): // no units message
alert(ALERT_LEVEL_WARNING, "Derzeit werden keine Einheiten ausgebildet, bitte bilde Einheiten über die Auswahl aus!");
else: ?>
<!-- training table -->
<table class="table">
  <thead>
    <tr>
      <th>Titel</th>
      <th>Volumen</th>
      <th>Fortschritt</th>
      <th>Restzeit</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <? foreach ($units as $id => $unit):
        if (!empty($unit)):
          $time = ($unit["end_round"] - $round_number) * $update_interval;
          $round_diff = $unit["end_round"] - $unit["start_round"];
          if ($round_diff > 0):
            $progress = 100 -
              (100/$round_diff) * ($unit["end_round"] - $round_number);
          else:
            $progress = 100;
          endif;
          if ($progress < 0):
            $progress = 0;
          endif;
        else:
          $progress = 0;
          $time = 0;
        endif; ?>
    <tr>
      <td><?=$unit["title"]?> [<?=$unit["number"]?>] <abbr title="<?=$unit["text"]?>">?</abbr></td>
      <td><?=get_numeric_value($unit["volume"])?></td>
      <td><div class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="<?=$progress?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$progress?>%;">
          <?=$progress?>%
        </div>
      </div></td>
      <td><?=get_numeric_time_value($time)?></td>
      <td><a href="<?=base_url()?>units/training/cancel/<?=$id?>">Abbrechen</a></td>
    </tr>
    <? endforeach; ?>
  </tbody>
</table>
<? endif; ?>