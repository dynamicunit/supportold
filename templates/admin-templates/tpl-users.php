<?php include_once(__DIR__ . '/../inc_head.php') ?>
</head>

<body>
    <main class="page-wrapper">
        <?php include_once(__DIR__ . '/../inc_header.php') ?>

        <section class="container my-5">
            <div class="row">
                <div class="col">
                    <div class="d-flex align-items-center justify-content-between">
                        <h1>Users List</h1>
                        <div>
                            <a href="<?= $baseurl ?>/admin/manage-users" class="btn btn-sm btn-outline-primary"><i
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
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>IP Address</th>
                                    <th>Membership</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($render as $row): ?>
                                    <tr id="user_<?= $row['id'] ?>">
                                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                                        <td><?= htmlspecialchars($row['phone']) ?></td>
                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                        <td><?= htmlspecialchars($row['registration_ip_address']) ?></td>
                                        <td><?= htmlspecialchars($row['membership']) ?></td>
                                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                                        <td>
                                            <a href="<?= $baseurl ?>/admin/forgot-password/<?= $row['id'] ?>"
                                                class="btn btn-sm btn-outline-primary"><i class="icon_key"></i></a>
                                            <a href="<?= $baseurl ?>/admin/manage-users/<?= $row['id'] ?>"
                                                class="btn btn-sm btn-outline-primary"><i class="icon_pencil"></i></a>
                                            <a href="#" class="btn btn-sm btn-outline-primary delete-user"
                                                data-user-id="<?= $row['id'] ?>"><i class="icon_trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <?= $render_pagination ?>

                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteAuthorModal" tabindex="-1" aria-labelledby="deleteAuthorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAuthorModalLabel">Delete User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this user?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmDeleteAuthor">Yes, delete</button>
                </div>
            </div>
        </div>
    </div>

    <?php include_once(__DIR__ . '/../inc_footer.php') ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var deleteAuthId;
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


            fetchCsrfToken();

            document.querySelectorAll('.delete-user').forEach(function (button) {
                button.addEventListener('click', function () {
                    deleteAuthId = this.getAttribute('data-user-id');
                    var deleteAuthorModal = new bootstrap.Modal(document.getElementById('deleteAuthorModal'));
                    deleteAuthorModal.show();
                });
            });

            document.getElementById('confirmDeleteAuthor').addEventListener('click', function () {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '<?= $baseurl ?>/admin/control/delete-user.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            try {
                                var responseObject = JSON.parse(xhr.responseText);
                                fetchCsrfToken();
                                if (responseObject.success) {
                                    var userElement = document.getElementById('user_' + deleteAuthId);
                                    if (userElement) {
                                        userElement.remove();
                                    }
                                    var deleteAuthorModal = bootstrap.Modal.getInstance(document.getElementById('deleteAuthorModal'));
                                    if (deleteAuthorModal) {
                                        deleteAuthorModal.hide();
                                    }

                                } else {
                                    alert('Error: ' + responseObject.message);
                                }
                            } catch (e) {
                                alert('Error: Could not parse delete response.');
                            }
                        } else {
                            alert('Error: Could not delete the author.');
                        }
                    }
                };
                xhr.send('id=' + encodeURIComponent(deleteAuthId) + '&token=' + encodeURIComponent(csrfToken));
            });
        });
    </script>

</body>

</html>