<?php

require('vendor/autoload.php');
require('app/config.php');
require('app/sheets.php');

$client = getGoogleClient();
$service = getSheetsService($client);
$companies = getCompanyList($service);

include('tpl/header.php');

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
		<?php $index = 1; foreach ($companies as $company) : ?>
		<tr>
			<td align="center"><?php echo $index++; ?></td>
			<td><?php echo $company['name']; ?></td>
			<td><?php echo $company['location']; ?></td>
			<td><?php echo $company['project']; ?></td>
			<td align="center"><?php echo $company['quota']; ?></td>
			<td><?php echo $company['prerequisites']; ?></td>
			<td><?php echo $company['knowledgeArea']; ?></td>
			<td><?php echo $company['coach']; ?></td>
			<td><?php echo $company['facilities']; ?></td>
			<td><a href="perusahaan.php?id=<?php echo $company['']; ?>">Lihat peminat</a></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php

include('tpl/footer.php');
