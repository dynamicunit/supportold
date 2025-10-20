<?php
require(__DIR__ . '/../vendor/autoload.php');

class s3bucket
{
    private $_db;
    private $s3;
    private $accessKeyId;
    private $secretAccessKey;
    private $region;
    private $bucket;

    public function __construct()
    {
        $this->_db = db::getinstanace();
        $com = new common();

        $this->accessKeyId = $com->get_value('accessKeyId');
        $this->secretAccessKey = $com->get_value('secretAccessKey');
        $this->region = $com->get_value('region');
        $this->bucket = $com->get_value('bucket');

        $this->s3 = new \Aws\S3\S3Client([
            'version' => 'latest',
            'region' => $this->region,
            'credentials' => [
                'key' => $this->accessKeyId,
                'secret' => $this->secretAccessKey
            ]
        ]);
    }

    public function addImage($uploadFiles)
    {
        $function = new functions();
        try {
            $tmpFilePath = $uploadFiles['tmp_name'];
            $fileExtension = pathinfo($uploadFiles['name'], PATHINFO_EXTENSION);
            $fileName = $function->gen_filename() . '.' . $fileExtension;

            $result = $this->s3->putObject([
                'Bucket' => $this->bucket,
                'Key' => $fileName,
                'Body' => fopen($tmpFilePath, 'rb'),
                // 'ACL' => 'public-read' // Remove this line
            ]);

            return $result['ObjectURL'];
        } catch (\Aws\S3\Exception\S3Exception $e) {
            error_log('Error uploading file to S3: ' . $e->getMessage());
            return 'Error uploading file: ' . $e->getMessage();
        }
    }

    public function deleteImage($url)
    {
        $urlParts = parse_url($url);
        $key = ltrim($urlParts['path'], '/');

        try {
            $result = $this->s3->deleteObject([
                'Bucket' => $this->bucket,
                'Key' => $key
            ]);

            // Log the result of the delete operation
            error_log('Delete result: ' . print_r($result, true));
            return true;
        } catch (\Aws\S3\Exception\S3Exception $e) {
            error_log('Error deleting file from S3: ' . $e->getMessage());
            return 'Error deleting file: ' . $e->getMessage();
        }
    }
}
