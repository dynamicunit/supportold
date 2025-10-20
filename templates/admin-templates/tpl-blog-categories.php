<?php include_once(__DIR__ . '/../inc_head.php') ?>
</head>

<body>
    <main class="page-wrapper">
        <?php include_once(__DIR__ . '/../inc_header.php') ?>

        <section class="container my-5">
            <div class="row">
                <div class="col">
                    <div class="d-flex align-items-center justify-content-between">
                        <h1>Blog Categories List</h1>
                        <div>
                            <a href="<?= $baseurl ?>/admin/manage-blog-category"
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
                                    <th>Description</th>
                                    <th>Slug</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($render) {
                                    foreach ($render as $row): ?>
                                        <tr id="category_<?= $row['id'] ?>">
                                            <td><?= htmlspecialchars($row['id']) ?></td>
                                            <td><?= htmlspecialchars($row['description']) ?></td>
                                            <td><?= htmlspecialchars($row['slug']) ?></td>
                                            <td>
                                                <a class="btn btn-sm btn-outline-primary"
                                                    href="<?= $baseurl ?>/admin/manage-blog-category/<?= $row['id'] ?>"><i
                                                        class="icon_pencil"></i></a>
                                                <a href="#" class="btn btn-sm btn-outline-primary delete-category"
                                                    data-cat-id="<?= $row['id'] ?>"><i class="icon_trash"></i></a>

                                            </td>
                                        </tr>
                                    <?php endforeach;
                                } else {
                                    echo '<tr><td colspan="4">No records found</td></tr>';
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
    <!-- Delete Category Modal -->
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCategoryModalLabel">Delete Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this category?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteCategory">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <?php include_once(__DIR__ . '/../inc_footer.php') ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var deleteCategoryId;
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


            document.querySelectorAll('.delete-category').forEach(function (button) {
                button.addEventListener('click', function () {
                    deleteCategoryId = this.getAttribute('data-cat-id');
                    var deleteCategoryModal = new bootstrap.Modal(document.getElementById('deleteCategoryModal'));
                    deleteCategoryModal.show();
                });
            });

            document.getElementById('confirmDeleteCategory').addEventListener('click', function () {
                var formData = new FormData();
                formData.append('id', deleteCategoryId);
                formData.append('token', csrfToken);

                fetch('<?= $baseurl ?>/admin/control/delete-blog-category.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => {
                        fetchCsrfToken();
                        if (!response.ok) {
                            throw new Error('Error: Could not delete the category.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            var categoryElement = document.getElementById('category_' + deleteCategoryId);
                            if (categoryElement) {
                                categoryElement.remove();
                            }

                            var deleteCategoryModal = bootstrap.Modal.getInstance(document.getElementById('deleteCategoryModal'));
                            if (deleteCategoryModal) {
                                deleteCategoryModal.hide();
                            }
                        } else {
                            alert('Error: ' + data.message);
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