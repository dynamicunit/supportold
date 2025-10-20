<?php include_once(__DIR__ . '/../inc_head.php') ?>
</head>

<body>
    <main class="page-wrapper">
        <?php include_once(__DIR__ . '/../inc_header.php') ?>
        <section class="container my-5">
            <div class="row">
                <div class="col">
                    <div class="d-flex align-items-center justify-content-between">
                        <h1>Website Pages</h1>
                        <div>
                            <a href="<?= $baseurl ?>/admin/manage-page" class="btn btn-sm btn-outline-primary"><i
                                    class="icon_plus"></i> Add</a>
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
                                    <th>Slug</th>
                                    <th>Meta Title</th>
                                    <th>Page Title</th>
                                    <th>Status</th>
                                    <th>Created On</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($render) {
                                    foreach ($render as $row): ?>
                                        <tr id="post_<?= $row['id'] ?>">
                                            <td><?= htmlspecialchars($row['id']) ?></td>
                                            <td><?= htmlspecialchars($row['slug']) ?></td>
                                            <td><?= htmlspecialchars($row['meta_title']) ?></td>
                                            <td><?= htmlspecialchars($row['page_title']) ?></td>
                                            <td><?= htmlspecialchars($row['is_active'] == 1 ? 'Active' : 'Not Active') ?></td>
                                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                                            <td>
                                                <a class="btn btn-sm btn-outline-primary"
                                                    href="<?= $baseurl ?>/admin/manage-page/<?= $row['id'] ?>"><i
                                                        class="icon_pencil"></i></a>
                                                <a href="#" class="btn btn-sm btn-outline-primary delete-page"
                                                    data-page-id="<?= $row['id'] ?>"><i class="icon_trash"></i></a>
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
    <!-- Delete page Modal -->
    <div class="modal fade" id="deletePostModal" tabindex="-1" aria-labelledby="deletePostModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletePostModalLabel">Delete Page</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this Page?
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

            function fetchCsrfToken() {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '<?= $baseurl ?>/user/control/_generate_token.php', false);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            try {
                                var responseObject = JSON.parse(xhr.responseText);
                                csrfToken = responseObject.token;
                            } catch (e) {
                                alert('Error: Could not parse CSRF token response.');
                            }
                        } else {
                            alert('Error: Could not fetch CSRF token.');
                        }
                    }
                };
                xhr.send();
            }

            // Initial fetch of CSRF token
            fetchCsrfToken();


            document.querySelectorAll('.delete-page').forEach(function (button) {
                button.addEventListener('click', function () {
                    deletePostId = this.getAttribute('data-page-id');
                    var deletePostModal = new bootstrap.Modal(document.getElementById('deletePostModal'));
                    deletePostModal.show();
                });
            });


            document.getElementById('confirmDeletePost').addEventListener('click', function () {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '<?= $baseurl ?>/admin/control/delete-page.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            try {
                                var responseObject = JSON.parse(xhr.responseText);
                                fetchCsrfToken();
                                if (responseObject.success) {
                                    var postElement = document.getElementById('post_' + deletePostId);
                                    if (postElement) {
                                        postElement.remove();
                                    }
                                    var deletePostModal = bootstrap.Modal.getInstance(document.getElementById('deletePostModal'));
                                    if (deletePostModal) {
                                        deletePostModal.hide();
                                    }
                                    // Fetch new CSRF token

                                } else {
                                    alert('Error: ' + responseObject.message);
                                }
                            } catch (e) {
                                alert('Error: Could not parse delete response.');
                            }
                        } else {
                            alert('Error: Could not delete the Post.');
                        }
                    }
                };
                xhr.send('id=' + encodeURIComponent(deletePostId) + '&token=' + encodeURIComponent(csrfToken));
            });
        });
    </script>

</body>

</html>