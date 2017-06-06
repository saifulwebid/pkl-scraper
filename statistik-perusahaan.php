<?php

require('vendor/autoload.php');
require('app/config.php');
require('app/sheets.php');

$client = getGoogleClient();
$service = getSheetsService($client);
$companies = getCompanyList($service, true);

if (isset($_GET['sort']))
{
	$companies = sortCompanyByStats($companies, $_GET['sort']);
}

include('tpl/header.php');

?>

<table border="1">
	<thead>
		<tr>
			<th rowspan="2">No.</th>
			<th rowspan="2">Nama Perusahaan</th>
			<th rowspan="2">Kuota</th>
			<th colspan="<?php echo count($companies) + 1; ?>">Prioritas</th>
			<th rowspan="2">Opsi</th>
		</tr>
		<tr>
			<?php for ($i = 1; $i <= count($companies); $i++) : ?>
			<th><a href="statistik-perusahaan.php?sort=<?php echo $i; ?>"><?php echo $i; ?></a></th>
			<?php endfor; ?>
			<th><a href="statistik-perusahaan.php?sort=999">Kosong</a></th>
		</tr>
	</thead>
	<tbody>
		<?php $index = 1; foreach ($companies as $company) : ?>
		<tr>
			<td align="center"><?php echo $index++; ?></td>
			<td><?php echo $company['name']; ?></td>
			<td align="center"><b><?php echo $company['quota']; ?></b></td>
			<?php for ($i = 1; $i <= count($companies); $i++) : ?>
			<td align="center" width="30">
				<?php echo $company['stats'][$i] == 0 ? '-' : $company['stats'][$i]; ?>
			</td>
			<?php endfor; ?>
			<td align="center" width="30">
				<?php echo $company['stats'][999] == 0 ? '-' : $company['stats'][999]; ?>
			</td>
			<td><a href="perusahaan.php?id=<?php echo $company['index']; ?>">Lihat peminat</a></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php

include('tpl/footer.php');
