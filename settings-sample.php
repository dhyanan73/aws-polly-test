<?php

$awsAccessKeyId = 'XXXXXXXXXXXXXXXXXXXX';
$awsSecretKey   = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';
$pollyBucket = 'XXXXXXXXXXX';
$finalDestBucket = 'XXXXXXXXXX';
$finalDestFolder = ''; // Final '/' required
$s3region = 'eu-west-3';
$voiceId = 'Bianca';	// Italian: Bianca (neural) / Carla / Giorgio; British English: Emma (neural) / May (neural) / Brian (neural) / Arthur (only neural)
$engine = 'neural'; // Allowed values: "standard" / "neural"
$sampleRate = '24000'; // Allowed values: "8000" / "16000" / "22050" / "24000"
$loop = false;
$autoplay = true;
