<?php

require('vendor/autoload.php');
require('app/config.php');
require('app/sheets.php');

$client = getGoogleClient();
$service = getSheetsService($client);
$companies = getCompanyList($service, true);

?>

<table border="1">
	<thead>
		<tr>
			<th rowspan="2">No.</th>
			<th rowspan="2">Nama Perusahaan</th>
			<th colspan="<?php echo count($companies) + 1; ?>">Prioritas</th>
			<th rowspan="2">Opsi</th>
		</tr>
		<tr>
			<?php for ($i = 1; $i <= count($companies); $i++) : ?>
			<th><?php echo $i; ?></th>
			<?php endfor; ?>
			<th>Kosong</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($companies as $company) : ?>
		<tr>
			<td align="center"><?php echo $company['index']; ?></td>
			<td><?php echo $company['name']; ?></td>
			<?php for ($i = 1; $i <= count($companies); $i++) : ?>
			<td align="center" width="30"><?php echo $company['stats'][$i]; ?></td>
			<?php endfor; ?>
			<td align="center" width="30"><?php echo $company['stats'][999]; ?></td>
			<td><a href="perusahaan.php?id=<?php echo $company['index']; ?>">Lihat peminat</a></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

