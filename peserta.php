<?php

require('vendor/autoload.php');
require('app/config.php');
require('app/sheets.php');

$client = getGoogleClient();
$service = getSheetsService($client);
$participant = getParticipantProfile($service, $_GET['id']);

?>

<p>Nama: <strong><?php echo $participant['name']; ?></strong></p>

<table border="1">
	<thead>
		<tr>
			<th>Prioritas</th>
			<th>Perusahaan</th>
			<th>Prerequisites yang dimiliki</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($participant['options'] as $option) : ?>
		<tr>
			<td><?php echo $option['priority']; ?></td>
			<td>
				<?php printf('<a href="perusahaan.php?id=%d">%s</a>',
					$option['company']['index'],
					$option['company']['name']); ?>
			</td>
			<td><?php echo $option['skillset']; ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

