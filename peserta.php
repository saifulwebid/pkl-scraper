<?php

require('vendor/autoload.php');
require('app/config.php');
require('app/sheets.php');

$client = getGoogleClient();
$service = getSheetsService($client);
$participant = getParticipantProfile($service, $_GET['id']);

include('tpl/header.php');

?>

<p>Nama: <strong><?php echo $participant['name']; ?></strong></p>

<table border="1">
	<thead>
		<tr>
			<th rowspan="2">Prioritas</th>
			<th colspan="2">Perusahaan Tujuan</th>
			<th rowspan="2">Prerequisites yang dimiliki</th>
		</tr>
		<tr>
			<th>Nama Perusahaan</th>
			<th>Prerequisites</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($participant['options'] as $option) : ?>
		<tr>
			<td align="center"><?php echo $option['priority']; ?></td>
			<td>
				<?php printf('<a href="perusahaan.php?id=%d">%s</a>',
					$option['company']['index'],
					$option['company']['name']); ?>
			</td>
			<td><?php echo $option['company']['prerequisites']; ?></td>
			<td><?php echo $option['skillset']; ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php

include('tpl/footer.php');
