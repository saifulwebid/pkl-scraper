<?php

require('vendor/autoload.php');
require('app/config.php');
require('app/sheets.php');

$client = getGoogleClient();
$service = getSheetsService($client);
$participants = getParticipantList($service);

include('tpl/header.php');

?>

<table border="1">
	<thead>
		<tr>
			<th>No.</th>
			<th>Nama Lengkap</th>
			<th colspan="2">Pilihan Perusahaan</th>
		</tr>
	</thead>
	<tbody>
		<?php 

		$index = 1;
		foreach ($participants as $participant) :

			if (!isset($participant['priority']))
				$rowspan = 1;
			else
				$rowspan = count($participant['priority']);

		?>
		<tr>
			<td align="center">
				<?php echo $index++; ?>
			</td>
			<td>
				<?php echo $participant['name']; ?>
			</td>
			<?php if (!isset($participant['priority'])) : ?>
			<td style="background: red; color: white" colspan="2">Belum memilih</td>
			<?php else : ?>
			<td>
				<?php

				$string = array();

				foreach ($participant['priority'] as $company) {
					$string[] = sprintf('
						%d.&nbsp;&nbsp;&nbsp;<a href="perusahaan.php?id=%d">%s</a>',
						$company['priority'], $company['index'], $company['name']);
				}

				echo implode($string, '<br>');

				?>
				</td>
			<?php endif; ?>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php

include('tpl/footer.php');
