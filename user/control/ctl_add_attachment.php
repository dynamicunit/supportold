<?php
require(__DIR__ . '/../../core/init.php');
$user = new users();
$function = new functions();

$success = false; // success variable
$message = null; // for passing the error or success message

function send_response($success, $message, $id = '', $urls = '', $tiles = '')
{
    $response = [
        'status' => $success,
        'message' => $message,
        'ids' => $id,
        'urls' => $urls,
        'titles' => $tiles
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}


if (input::exists('post')) {
    if (token::check(input::get('token'))) {
        $validate = new validate();
        $validate->check(
            $_POST,
            array(
                'ticket_id' => array(
                    'required' => true
                ),
            )
        );
        if ($validate->get_passed()) {
            try {
                $s3 = new s3bucket();
                $user->begin_transaction();
                $ids = [];
                $urls = [];
                $titles = [];

                $existing_files = input::get('existing_files') ?? [];
                $deletedFiles = input::get('deleted_files') ? explode(',', trim(input::get('deleted_files'), ',')) : [];

                if (!empty($deletedFiles)) {
                    foreach ($deletedFiles as $fileId) {
                        $fileId = intval($fileId);
                        $condition = ['id' => ['=', $fileId]];
                        $file = $user->fetch('ticket_pictures', $condition);

                        if ($file) {
                            $s3->deleteImage($file->url);

                            $user->delete('ticket_pictures', $condition);
                        }
                    }
                }

                if ($existing_files) {
                    foreach ($existing_files as $picture_id) {
                        $picture_id = intval($picture_id);
                        $condition = ['id' => ['=', $picture_id]];

                        $file = $user->fetch('ticket_pictures', $condition);
                        if ($file) {
                            $title = input::get('doc_titleup')[$picture_id];
                            $sanitizeddata = sanitizer::sanitize([
                                'title' => $title
                            ]);

                            $user->update('ticket_pictures', $sanitizeddata, $condition);

                            $ids[] = $picture_id;
                            $urls[] = $file->url;
                            $titles[] = $title;
                        }
                    }
                }



                if (!empty($_FILES['doc_file']['name'][0])) {
                    foreach ($_FILES['doc_file']['name'] as $index => $name) {
                        if (empty($name)) {
                            continue;
                        }
                        $file = [
                            'name' => $_FILES['doc_file']['name'][$index],
                            'type' => $_FILES['doc_file']['type'][$index] ?? '',
                            'tmp_name' => $_FILES['doc_file']['tmp_name'][$index],
                            'error' => $_FILES['doc_file']['error'][$index],
                            'size' => $_FILES['doc_file']['size'][$index]
                        ];

                        // Upload to S3
                        $url = $s3->addImage($file);

                        $title = input::get('doc_title')[$index];

                        if (!empty($title)) {
                            $sanitizeddata = sanitizer::sanitize([
                                'website_id' => 1,
                                'ticket_id' => input::get('ticket_id'),
                                'title' => $title,
                                'url' => $url
                            ]);

                            $user->add('ticket_pictures', $sanitizeddata);
                            $last_id = $user->get_last_insert_id();

                            // Add to response
                            $ids[] = $last_id;
                            $urls[] = $url;
                            $titles[] = $title;
                        }
                    }
                }


                $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Attachments Updated successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
             </div>';
                $user->commit();
                send_response(true, $message, $ids, $urls, $titles);

            } catch (exception $e) {
                $user->roll_back();
                error_log($e->getMessage() . ': control contact form', 3, __DIR__ . '/errors.log');

                $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
               Error occurred while processing your request. Please try again later.
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
               </div>';
                send_response(false, $message);
            }
        } else {
            $message = '<div class="alert alert-danger" role="alert">';
            foreach ($validate->get_errors() as $error) {
                $message .= $error . '</br>';
            }
            $message .= '</div>';
            send_response(false, $message);
        }
    }
}
