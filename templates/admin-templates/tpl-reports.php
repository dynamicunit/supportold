<?php include_once(__DIR__ . '/../inc_head.php') ?>
</head>

<body>

    <main class="page-wrapper">
        <?php include_once(__DIR__ . '/../inc_header.php') ?>
        <section class="container my-5">
            <div class="row">
                <div class="col">
                    <div class="d-flex align-items-center justify-content-between">
                        <h1>Reports</h1>
                        <div>
                            <a href="<?= $baseurl ?>/admin/manage-report" class="btn btn-sm btn-outline-primary"><i
                                    class="fas fa-add"></i> Add</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <?= $view->page_messages($validation_errors); ?>
                    <div class="table-responsive mt-1">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>System Name</th>
                                    <th>Coloumns</th>
                                    <th>Url</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($render) {
                                    foreach ($render as $key => $row): ?>
                                        <tr id="post_<?= $row['id'] ?>">
                                            <td><?= htmlspecialchars($row['report_name']) ?></td>
                                            <td><?= htmlspecialchars($row['description']) ?></td>
                                            <td><?= htmlspecialchars($row['system_name']) ?></td>
                                            <td><?= htmlspecialchars($row['columns']) ?></td>
                                            <td><?= htmlspecialchars($row['report_url']) ?></td>
                                            <td>
                                                <a class="btn btn-sm btn-outline-primary"
                                                    href="<?= $baseurl ?>/admin/manage-report/<?= $row['id'] ?>"><i
                                                        class="fas fa-pencil"></i></a>
                                                <a href="#" class="btn btn-sm btn-outline-primary delete-report"
                                                    data-workshop-id="<?= $row['id'] ?>"><i class="fas fa-trash"></i></a>
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
    <!-- Delete Post Modal -->
    <div class="modal fade" id="deleteEntityModal" tabindex="-1" aria-labelledby="deleteEntityModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteEntityModalLabel">Delete Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this report?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteEntity">Delete</button>
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

            document.querySelectorAll('.delete-report').forEach(function (button) {
                button.addEventListener('click', function () {
                    deletePostId = this.getAttribute('data-workshop-id');
                    var deleteEntityModal = new bootstrap.Modal(document.getElementById('deleteEntityModal'));
                    deleteEntityModal.show();
                });
            });

            document.getElementById('confirmDeleteEntity').addEventListener('click', function () {
                var formData = new FormData();
                formData.append('id', deletePostId);
                formData.append('token', csrfToken);

                fetch('<?= $baseurl ?>/admin/control/delete-report.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => {
                        fetchCsrfToken();
                        if (!response.ok) {
                            throw new Error('Error: Could not delete the entity.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            var postElement = document.getElementById('post_' + deletePostId);
                            if (postElement) {
                                postElement.remove();
                            }

                            var deleteEntityModal = bootstrap.Modal.getInstance(document.getElementById('deleteEntityModal'));
                            if (deleteEntityModal) {
                                deleteEntityModal.hide();
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