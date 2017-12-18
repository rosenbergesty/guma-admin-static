<?php
  require __DIR__ . '/vendor/autoload.php';
  use Google\Cloud\Storage\StorageClient;

  // Google Projects
  $projectId = 'guma-construction-apps';

  # Instantiates a client
  $storage = new StorageClient([
      'projectId' => $projectId
  ]);

  # The name for the new bucket
  $bucketName = 'my-new-bucket';

  # Creates the new bucket
  $bucket = $storage->createBucket($bucketName);

  echo 'Bucket ' . $bucket->name() . ' created.';
?>