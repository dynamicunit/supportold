<?php include_once(__DIR__ . '/../inc_head.php') ?>
</head>

<body>
    <main class="page-wrapper">
        <?php include_once(__DIR__ . '/../inc_header.php') ?>

        <section class="container my-5">
            <div class="row">
                <div class="col">
                    <div class="text-center">
                        <h1>Contact Form Messages</h1>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <?= $view->page_messages($validation_errors); ?>
                    <?= $view->page_messages(); ?>
                    <div class="table-responsive mt-1">
                        <table class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Body</th>
                                    <th>Created On</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($render) {
                                    foreach ($render as $row): ?>
                                        <tr id="post_<?= $row['id'] ?>">
                                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                                            <td><?= htmlspecialchars($row['email']) ?></td>
                                            <td><?= htmlspecialchars($row['message_body']) ?></td>
                                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                                            <td>
                                                <a class="btn btn-sm btn-outline-primary"
                                                    href="<?= $baseurl ?>/admin/manage-contact/<?= $row['id'] ?>"><i
                                                        class="icon_pencil"></i></a>
                                                <a href="#" class="btn btn-sm btn-outline-primary delete-post"
                                                    data-auth-id="<?= $row['id'] ?>"><i class="icon_trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach;
                                } else {
                                    echo '<tr><td colspan="5">No records found</td></tr>';
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
    <!-- Delete lookup Modal -->
    <div class="modal fade" id="deletePostModal" tabindex="-1" aria-labelledby="deletePostModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletePostModalLabel">Delete Contact Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this Contact Message?
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

            // Handle delete button click
            document.querySelectorAll('.delete-post').forEach(function (button) {
                button.addEventListener('click', function () {
                    deletePostId = this.getAttribute('data-auth-id');
                    var deletePostModal = new bootstrap.Modal(document.getElementById('deletePostModal'));
                    deletePostModal.show();
                });
            });

            // Handle confirmation button click
            document.getElementById('confirmDeletePost').addEventListener('click', function () {
                var formData = new FormData();
                formData.append('id', deletePostId);
                formData.append('token', csrfToken);

                fetch('<?= $baseurl ?>/admin/control/delete-contact.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => {
                        fetchCsrfToken();
                        if (!response.ok) {
                            throw new Error('Error: Could not delete the post.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            document.getElementById('post_' + deletePostId).remove();

                            var deletePostModal = bootstrap.Modal.getInstance(document.getElementById('deletePostModal'));
                            if (deletePostModal) {
                                deletePostModal.hide();
                            }

                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('Error: ' + error.message);
                    });
            });


        });
    </script>
</body>

</html>