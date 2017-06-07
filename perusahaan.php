<?php

require('vendor/autoload.php');
require('app/config.php');
require('app/sheets.php');

$client = getGoogleClient();
$service = getSheetsService($client);
$company = getCompanyInfo($service, $_GET['id']);
$participants = getCompanyParticipantList($service, $_GET['id']);

$title = $company['name'];

include('tpl/header.php');

?>

<table border="1" style="margin-bottom: 1em">
	<tbody>
		<tr>
			<td>Nama Perusahaan</td>
			<td><?php echo $company['name']; ?></td>
		</tr>
		<tr>
			<td>Lokasi Penempatan</td>
			<td><?php echo $company['location']; ?></td>
		</tr>
		<tr>
			<td>Penempatan/Proyek</td>
			<td><?php echo $company['project']; ?></td>
		</tr>
		<tr>
			<td>Kuota</td>
			<td><?php echo $company['quota']; ?> orang</td>
		</tr>
		<tr>
			<td>Prerequisites</td>
			<td><?php echo $company['prerequisites']; ?></td>
		</tr>
		<tr>
			<td>Knowledge Area</td>
			<td><?php echo $company['knowledgeArea']; ?></td>
		</tr>
		<tr>
			<td>Pembimbing</td>
			<td><?php echo $company['coach']; ?></td>
		</tr>
		<tr>
			<td>Fasilitas</td>
			<td><?php echo $company['facilities']; ?></td>
		</tr>
	</tbody>
</table>

<table border="1">
	<thead>
		<tr>
			<th>Nama</th>
			<th>Prioritas</th>
			<th>Prerequisites yang dikuasai</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($participants as $participant) : ?>
		<tr>
			<td>
				<a href="peserta.php?id=<?php echo $participant['index']; ?>">
					<?php echo $participant['name']; ?>
				</a>
			</td>
			<td align="center"><?php echo $participant['priority']; ?></td>
			<td><?php echo nl2br($participant['skillset']); ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php

include('tpl/footer.php');
