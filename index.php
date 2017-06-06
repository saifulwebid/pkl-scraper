<?php

require('vendor/autoload.php');
require('app/config.php');
require('app/sheets.php');

$client = getGoogleClient();
$service = getSheetsService($client);
$companies = getCompanyList($service);

?>

<table border="1">
	<thead>
		<tr>
			<th>No.</th>
			<th>Nama Perusahaan</th>
			<th>Lokasi Penempatan</th>
			<th>Penempatan/Proyek</th>
			<th>Kuota</th>
			<th>Prerequisites</th>
			<th>Knowledge Area</th>
			<th>Pembimbing</th>
			<th>Fasilitas</th>
			<th>Opsi</th>
		</tr>
	</thead>
	<tbody>
<?php

$index = 1;
foreach ($companies as $company)
{
	printf('
		<tr>
			<td align="center">%d</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td align="center">%d</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td><a href="perusahaan.php?id=%d">Lihat peminat</a></td>
		</tr>',
		$company['index'], $company['name'], $company['location'],
		$company['project'], $company['quota'], $company['prerequisites'],
		$company['knowledgeArea'], $company['coach'], $company['facilities'],
		$company['index']);
}

?>
	</tbody>
</table>

