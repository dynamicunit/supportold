<?php include_once(__DIR__ . '/../inc_head.php') ?>
</head>

<body>

    <main class="page-wrapper">
        <?php include_once(__DIR__ . '/../inc_header.php') ?>
        <section class="container my-5">
            <div class="row">
                <div class="col">
                    <div class="d-flex align-items-center justify-content-between">
                        <h1>Email Templates</h1>
                        <div>
                            <a href="<?= $baseurl ?>/admin/manage-email-template"
                                class="btn btn-sm btn-outline-primary"><i class="icon_plus"></i> Add</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <?= $view->page_messages($validation_errors); ?>
                    <?= $view->page_messages(); ?>
                    <div class="table-responsive mt-1">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Code</th>
                                    <th>Subject</th>
                                    <th>Body</th>
                                    <th>Created On</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($render) {
                                    foreach ($render as $row): ?>
                                        <tr id="post_<?= $row['id'] ?>">

                                            <td><?= htmlspecialchars($row['id']) ?></td>
                                            <td><?= htmlspecialchars($row['text_id']) ?></td>
                                            <td><?= htmlspecialchars($row['email_subject']) ?></td>
                                            <td><?= substr(html_entity_decode($row['email_body']), 0, 30) ?>...</td>
                                            <td><?= htmlspecialchars($row['created_at']) ?></td>

                                            <td>
                                                <a class="btn btn-sm btn-outline-primary"
                                                    href="<?= $baseurl ?>/admin/manage-email-template/<?= $row['id'] ?>"><i
                                                        class="icon_pencil"></i></a>
                                                <a href="#" class="btn btn-sm btn-outline-primary delete-email"
                                                    data-email-id="<?= $row['id'] ?>"><i class="icon_trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach;
                                } else {
                                    echo '<tr><td colspan="6">No records found</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <nav aria-label="Page navigation">
                        <?= $render_pagination ?>
                    </nav>
                </div>
            </div>
        </section>
    </main>
    <!-- Delete email Modal -->
    <div class="modal fade" id="deletePostModal" tabindex="-1" aria-labelledby="deletePostModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletePostModalLabel">Delete Email template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this Email template?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeletePost">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <?php include_once(__DIR__ . '/../inc_footer.php') ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var deletePostId;
            var csrfToken;

            async function fetchCsrfToken() {

                const url = '<?= $baseurl ?>/user/control/_generate_token.php';
                try {

                    const response = await fetch(url);

                    if (!response.ok) {
                        throw new Error(`Response Error:${response.status}`);
                    }

                    const json = await response.json();

                    csrfToken = json.token;

                } catch (error) {
                    console.error(error);
                }

            }

            fetchCsrfToken();

            document.querySelectorAll('.delete-email').forEach(function (button) {
                button.addEventListener('click', function () {
                    deletePostId = this.getAttribute('data-email-id');
                    var deletePostModal = new bootstrap.Modal(document.getElementById('deletePostModal'));
                    deletePostModal.show();
                });
            });

            document.getElementById('confirmDeletePost').addEventListener('click', function () {
                const formData = new FormData();
                formData.append('id', deletePostId);
                formData.append('token', csrfToken);

                fetch('<?= $baseurl ?>/admin/control/delete-email-template.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => {
                        fetchCsrfToken();
                        if (!response.ok) {
                            throw new Error('Error: Could not delete the Post.');
                        }
                        return response.json();
                    })
                    .then(responseObject => {
                        if (responseObject.success) {
                            const postElement = document.getElementById('post_' + deletePostId);
                            if (postElement) {
                                postElement.remove();
                            }
                            const deletePostModal = bootstrap.Modal.getInstance(document.getElementById('deletePostModal'));
                            if (deletePostModal) {
                                deletePostModal.hide();
                            }
                            // Fetch new CSRF token
                        } else {
                            alert('Error: ' + responseObject.message);
                        }
                    })
                    .catch(error => {
                        alert(error.message);
                    });
            });

        });
    </script>


</body>

</html>