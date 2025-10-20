<?php
class editpictures
{
    private $s3 = null;
    private $profile = null;
    private $profile_id = null;
    public function __construct($s3, $profile, $profile_id)
    {
        $this->s3 = $s3;
        $this->profile = $profile;
        $this->profile_id = $profile_id;
    }

    /**
     * Extracts files from the $_FILES global array.
     * 
     * @param array $fields     The fields to extract files from.
     * @param array $filesData  The $_FILES array containing file upload data.
     * @param bool $isMultiple  Whether to handle multiple file uploads.
     * 
     * @return array  An array of extracted files.
     */
    public function extractFiles(array $fields, array $filesData, bool $isMultiple = false): array
    {
        $result = [];

        foreach ($fields as $field) {
            if (isset($filesData[$field])) {
                if ($isMultiple) {
                    $fileCount = count($filesData[$field]['name']);
                    $files = [];
                    for ($i = 0; $i < $fileCount; $i++) {
                        if ($filesData[$field]['error'][$i] !== UPLOAD_ERR_OK) {
                            continue;
                        }
                        $files[] = [
                            'name' => $filesData[$field]['name'][$i],
                            'full_path' => $filesData[$field]['full_path'][$i],
                            'type' => $filesData[$field]['type'][$i],
                            'tmp_name' => $filesData[$field]['tmp_name'][$i],
                            'error' => $filesData[$field]['error'][$i],
                            'size' => $filesData[$field]['size'][$i],
                        ];
                    }
                    if (!empty($files)) {
                        $result[$field] = $files;
                    }
                } else {
                    if ($filesData[$field]['error'] !== UPLOAD_ERR_OK) {
                        $result[$field] = '';
                    } else if (isset($filesData[$field])) {
                        $result[$field] = $filesData[$field];
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Processes image updates by comparing the current and uploaded images.
     * 
     * @param array $uploadedImages      Array of uploaded image files.
     * @param array $uploadedImageURLs   Array of uploaded image URLs.
     * @param array $currentImageURLs    Array of current image URLs from the database.
     * 
     * @return array                     Array of updated image URLs.
     */
    public function processImageUpdates(array $uploadedImages, array $uploadedImageURLs, array $currentImageURLs): array
    {
        $newImagesURLs = array_diff($uploadedImageURLs, $currentImageURLs);
        $deletedImagesURLs = array_diff($currentImageURLs, $uploadedImageURLs);

        if (count($newImagesURLs)) {
            $newImagesFiles = array_intersect_key($uploadedImages, $newImagesURLs);
            $newImagesURLsAfterUpload = $this->uploadImages($newImagesFiles);

            $oldToNewUrlMap = array_combine($newImagesURLs, $newImagesURLsAfterUpload);
            foreach ($uploadedImageURLs as $index => &$url) {
                if (isset($oldToNewUrlMap[$url])) {
                    $url = $oldToNewUrlMap[$url];
                }
            }
        }

        if (count($deletedImagesURLs)) {
            $this->deleteImages($deletedImagesURLs);

            $uploadedImageURLs = array_diff($uploadedImageURLs, $deletedImagesURLs);
        }

        return $uploadedImageURLs;
    }

    /**
     * Uploads images to storage.
     * 
     * @param array $files   Array of files to be uploaded.
     * 
     * @return array         Array of filenames after uploading.
     */
    public function uploadImages(array $files): array
    {

        $fileNames = [];
        foreach ($files as $file) {
            $link = $this->s3->addImage($file);
            $this->profile->add('profile_pictures', [
                'profile_id' => $this->profile_id,
                'url' => $link
            ]);
            $fileNames[] = $link;
        }
        return $fileNames;
    }
    /**
     * Deletes images from storage.
     * 
     * @param array $imageURLs   Array of image URLs to be deleted.
     */
    public function deleteImages(array $imageURLs): void
    {
        foreach ($imageURLs as $imageURL) {
            $this->s3->deleteImage($imageURL);
            $this->profile->delete('profile_pictures', [
                'profile_id' => ['=', $this->profile_id],
                'url' => ['=', $imageURL]
            ]);
        }
    }
}
