<?php

define('SHEET_NAME', 'Prioritas Perusahaan');

function getGoogleClient()
{
	$client = new Google_Client();
	$client->setDeveloperKey(API_KEY);
	return $client;
}

function getSheetsService(Google_Client $client)
{
	$service = new Google_Service_Sheets($client);
	return $service;
}

function getCompanyList(Google_Service_Sheets $service, $with_stats = false)
{
	$response = $service->spreadsheets_values
		->get(SPREADSHEET_ID, SHEET_NAME . '!B:'
			. ($with_stats == true ? 'BM' : 'I'));
	$values = $response->getValues();

	unset($values[0]);
	unset($values[1]);

	$values = array_values($values);

	if ($with_stats)
	{
		foreach ($values as $key => $value)
		{
			for ($i = 1; $i <= count($values); $i++)
				$result[$key]['stats'][$i] = 0;
			$result[$key]['stats'][999] = 0;
		}
	}

	$index = 1;
	foreach ($values as $key => $value)
	{
		$result[$key]['index'] = $index++;
		$result[$key]['name'] = @$value[0];
		$result[$key]['location'] = @$value[1];
		$result[$key]['project'] = @$value[2];
		$result[$key]['quota'] = @$value[3];
		$result[$key]['prerequisites'] = @$value[4];
		$result[$key]['knowledgeArea'] = @$value[5];
		$result[$key]['coach'] = @$value[6];
		$result[$key]['facilities'] = @$value[7];

		if ($with_stats)
		{
			for ($i = 0; $i < 28; $i++)
			{
				if (empty(@$value[8 + ($i * 2)]))
					$result[$key]['stats'][999]++;
				else
					$result[$key]['stats'][@$value[8 + ($i * 2)]]++;
			}
		}
	}

	return $result;
}

function getSheetsColumnFromIndex($index)
{
	$column = "";
	while ($index > 0)
	{
		$temp = ($index - 1) % 26;
		$column = chr($temp + ord('A')) . $column;
		$index = ($index - $temp - 1) / 26;
	}
	return $column;
}

function getCompanyInfo(Google_Service_Sheets $service, $companyIndex)
{
	$companyIndex += 2;

	$response = $service->spreadsheets_values->get(SPREADSHEET_ID,
		SHEET_NAME . '!B' . $companyIndex . ':I' . $companyIndex);
	$company = $response->getValues()[0];

	$result['name'] = @$company[0];
	$result['location'] = @$company[1];
	$result['project'] = @$company[2];
	$result['quota'] = @$company[3];
	$result['prerequisites'] = @$company[4];
	$result['knowledgeArea'] = @$company[5];
	$result['coach'] = @$company[6];
	$result['facilities'] = @$company[7];

	return $result;
}

function getCompanyParticipantList(Google_Service_Sheets $service, $companyIndex)
{
	$companyIndex += 2;

	$response = $service->spreadsheets_values->get(SPREADSHEET_ID,
		SHEET_NAME . '!J1:BM1');
	$values = $response->getValues()[0];
	for ($i = 0; $i < count($values); $i += 2)
	{
		$participants[$i / 2]['name'] = $values[$i];
		$participants[$i / 2]['index'] = $i / 2 + 1;
	}

	$response = $service->spreadsheets_values->get(SPREADSHEET_ID,
		SHEET_NAME . '!J' . $companyIndex . ':BM' . $companyIndex);
	$values = $response->getValues()[0];
	for ($i = 0; $i < count($values); $i += 2)
	{
		$participants[$i / 2]['priority'] = $values[$i];
		$participants[$i / 2]['skillset'] = $values[$i + 1];
	}

	foreach ($participants as $key => $row)
	{
		$priority[$key] = $row['priority'];
		if (empty($priority[$key]))
			$priority[$key] = 999;

		$name[$key] = $row['name'];
	}
	array_multisort($priority, SORT_ASC, $name, SORT_ASC, $participants);

	return $participants;
}

function getParticipantProfile(Google_Service_Sheets $service, $participantIndex)
{
	$companies = getCompanyList($service);

	$participantIndex--;

	$range = SHEET_NAME . '!'
		. getSheetsColumnFromIndex($participantIndex * 2 + 10)
		. '1:' . getSheetsColumnFromIndex($participantIndex * 2 + 11)
		. (count($companies) + 2);
	$response = $service->spreadsheets_values->get(SPREADSHEET_ID, $range);
	$values = $response->getValues();

	$profile['name'] = $values[0][0];

	unset($values[0]);
	unset($values[1]);

	$values = array_values($values);
	for ($i = 0; $i < count($companies); $i++)
	{
		$profile['options'][$i]['company'] = $companies[$i];
		$profile['options'][$i]['priority'] = @$values[$i][0];
		$profile['options'][$i]['skillset'] = @$values[$i][1];

		$priority[$i] = $profile['options'][$i]['priority'];
		if (empty($priority[$i]))
			$priority[$i] = 999;
	}
	array_multisort($priority, SORT_ASC, $profile['options']);

	return $profile;
}

function sortCompanyByStats($list, $key)
{
	for ($i = 0; $i < count($list); $i++)
	{
		$priority[$i] = $list[$i]['stats'][$key];
		$index[$i] = $list[$i]['index'];
	}
	array_multisort($priority, SORT_DESC, $index, SORT_ASC, $list);

	return $list;
}
