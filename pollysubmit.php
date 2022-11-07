<?php

try {
	require_once 'settings.php';
	require_once 'aws/aws-autoloader.php';

	$filename = time().'-polly.mp3';
	$credentials = new \Aws\Credentials\Credentials($awsAccessKeyId, $awsSecretKey);

	$client_polly = new \Aws\Polly\PollyClient([
		'version'     => '2016-06-10',
		'credentials' => $credentials,
		'region'      => $s3region
	]);
} catch(Exception $err) {
	echo '[Error] ' . $err->getMessage();
	exit;
}	

$result = '';

if (!empty($_POST['ptext'])) {
	try {
		$polly_text = trim($_POST['ptext']);
		$result_polly = $client_polly->startSpeechSynthesisTask([
			'OutputFormat' 			=> 'mp3',
			'Text'         			=> $polly_text,
			'TextType'     			=> 'text',
			'VoiceId'      			=> $voiceId,
			'Engine'      			=> $engine,
			'OutputS3BucketName'	=> $pollyBucket,
			'SampleRate'			=> $sampleRate
		]);
		$resultData_polly = $result_polly->get('SynthesisTask')['TaskId'];
		echo $resultData_polly;
	} catch(Exception $err) {
		echo '[Error] ' . $err->getMessage();
		exit;
	}	
}

if (!empty($_POST['taskid'])) {
	try {
		$taskid = trim($_POST['taskid']);
		$result_polly = $client_polly->getSpeechSynthesisTask([
		  'TaskId' 		=> $taskid
		]);
		$task_status = $result_polly->get('SynthesisTask')['TaskStatus'];
		if ($task_status === 'failed')
			$result = 'Error: getSpeechSynthesisTask failed.';
		if ($task_status === 'completed') {
			$client_s3 = new Aws\S3\S3Client([
				'version'     => 'latest',
				'credentials' => $credentials,
				'region'      => $s3region
			]);
			$result_s3 = $client_s3->copyObject([
				'ACL'			=> 'public-read',
				'Bucket'		=> $finalDestBucket,
				'ContentType'	=> 'audio/mpeg',
				'Key'			=> $finalDestFolder . $filename,
				'CopySource'	=> "$pollyBucket/$taskid.mp3"
			]);
			$result = $result_s3['ObjectURL'];
			$client_s3->deleteObject([
				'Bucket'	=> $pollyBucket,
				'Key'		=> "$taskid.mp3"
			]);
		}
	} catch(Exception $err) {
		$result = '[Error] ' . $err->getMessage();
	}	
	echo $result;
}

?>