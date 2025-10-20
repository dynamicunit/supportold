<?php include_once(__DIR__ . '/../inc_head.php') ?>
</head>

<body>

    <main class="page-wrapper">
        <?php include_once(__DIR__ . '/../inc_header.php') ?>
        <section class="container my-5">
            <div class="row">
                <div class="col">
                    <div class="d-flex align-items-center justify-content-between">
                        <h1>SLA Policies</h1>
                        <div>
                            <a href="<?= $baseurl ?>/admin/manage-sla-policies"
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
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Response Time</th>
                                    <th>Created On</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($render) {
                                    foreach ($render as $key => $row): ?>
                                        <tr id="sla_<?= $row['id'] ?>">

                                            <td><?= $key + 1 ?></td>
                                            <td><?= htmlspecialchars($row['sla_name']) ?></td>
                                            <td><?= htmlspecialchars($row['description']) ?></td>
                                            <td><?= htmlspecialchars($row['response_time_hours']) . ' hours' ?></td>
                                            <td><?= htmlspecialchars($row['created_at']) ?></td>


                                            <td>
                                                <a class="btn btn-sm btn-outline-primary"
                                                    href="<?= $baseurl ?>/admin/manage-sla-policies/<?= $row['id'] ?>"><i
                                                        class="icon_pencil"></i></a>
                                                <a href="#" class="btn btn-sm btn-outline-primary delete-sla"
                                                    data-sla-id="<?= $row['id'] ?>"><i class="icon_trash"></i></a>
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
    <div class="modal fade" id="deleteSlaModal" tabindex="-1" aria-labelledby="deleteSlaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSlaModalLabel">Delete SLA Policies</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this SLA Policies?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteSla">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <?php include_once(__DIR__ . '/../inc_footer.php') ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var deleteSlaId;
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

            document.querySelectorAll('.delete-sla').forEach(function (button) {
                button.addEventListener('click', function () {
                    deleteSlaId = this.getAttribute('data-sla-id');
                    var deleteSlaModal = new bootstrap.Modal(document.getElementById('deleteSlaModal'));
                    deleteSlaModal.show();
                });
            });

            document.getElementById('confirmDeleteSla').addEventListener('click', function () {
                var formData = new FormData();
                formData.append('id', deleteSlaId);
                formData.append('token', csrfToken);

                fetch('<?= $baseurl ?>/admin/control/delete-sla.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => {
                        fetchCsrfToken();
                        if (!response.ok) {
                            throw new Error('Error: Could not delete the Sla.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            var postElement = document.getElementById('sla_' + deleteSlaId);
                            if (postElement) {
                                postElement.remove();
                            }

                            var deleteSlaModal = bootstrap.Modal.getInstance(document.getElementById('deleteSlaModal'));
                            if (deleteSlaModal) {
                                deleteSlaModal.hide();
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