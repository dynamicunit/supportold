<?php include_once(__DIR__ . '/../inc_head.php') ?>
</head>

<body>
    <?php include_once(__DIR__ . '/../inc_sidebar.php') ?>


    <!-- CONTENT -->
    <main class="content">
        
        <section>
            <div class="container-fluid mb-2 ">
                <div class="row">
                    <div class="col">

                        <!-- Title + Button in one row -->
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                <h1 class="text-dark mb-0 me-3">Tickets Lists</h1>
                                <a href="<?= $baseurl ?>/user/manage-ticket" class="btn btn-light px-2 py-1">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Breadcrumb below -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= $baseurl ?>/user/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active">Tickets</li>
                            </ol>
                        </nav>

                    </div>
                </div>
            </div>
        </section>

        <section class="container-fluid">
            <div class="row">
                <div class="col">
                    <?= $view->page_messages($validation_errors); ?>
                    <div class="table-responsive mt-1">
                        <table class="table table-bordered table-hover align-middle shadow-sm bg-white p-3 rounded shadow-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Sr.</th>
                                    <th>Subject</th>
                                    <th>Ticket Status</th>
                                    <th>Created At</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($render) {
                                    foreach ($render as $key => $row): ?>
                                        <tr id="post_<?= $row['ticket_id'] ?>">
                                            <!-- Serial Number -->
                                            <td><?= $key + 1 ?></td>

                                            <!-- Subject -->
                                            <td><?= htmlspecialchars($row['issue_title']) ?></td>

                                            <!-- Status with badge -->
                                            <td>
                                                <?php
                                                $statusClass = 'bg-secondary'; // default
                                                switch (strtolower($row['status_name'])) {
                                                    case 'draft':
                                                        $statusClass = 'bg-light text-dark';
                                                        break;
                                                    case 'approved':
                                                        $statusClass = 'bg-success';
                                                        break;
                                                    case 'assigned':
                                                        $statusClass = 'bg-primary';
                                                        break;
                                                    case 'pending':
                                                        $statusClass = 'bg-warning text-dark';
                                                        break;
                                                    case 'rejected':
                                                        $statusClass = 'bg-danger';
                                                        break;
                                                    case 'cancelled':
                                                        $statusClass = 'bg-dark';
                                                        break;
                                                    case 'closed':
                                                        $statusClass = 'bg-secondary';
                                                        break;
                                                    case 'initial':
                                                        $statusClass = 'bg-info';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge <?= $statusClass ?>">
                                                    <?= htmlspecialchars($row['status_name']) ?>
                                                </span>
                                            </td>
                                            <!-- created date -->
                                            <td><?= htmlspecialchars(functions::timeAgo($row['created_at'])) ?></td>

                                            <!-- Actions -->
                                            <td class="text-center">
                                                <a class="btn btn-sm btn-outline-primary me-1"
                                                    href="<?= $baseurl ?>/user/manage-ticket/<?= $row['ticket_id'] ?>">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <a href="#" class="btn btn-sm btn-outline-danger delete-ticket"
                                                    data-entity-id="<?= $row['ticket_id'] ?>">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                <?php endforeach;
                                } else {
                                    echo '<tr><td colspan="4" class="text-center text-muted">No records found</td></tr>';
                                } ?>
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
                    <h5 class="modal-title" id="deleteEntityModalLabel">Delete Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this Ticket?
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
        document.addEventListener('DOMContentLoaded', function() {
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

            document.querySelectorAll('.delete-ticket').forEach(function(button) {
                button.addEventListener('click', function() {
                    deletePostId = this.getAttribute('data-entity-id');
                    var deleteEntityModal = new bootstrap.Modal(document.getElementById('deleteEntityModal'));
                    deleteEntityModal.show();
                });
            });

            document.getElementById('confirmDeleteEntity').addEventListener('click', function() {
                var formData = new FormData();
                formData.append('id', deletePostId);
                formData.append('token', csrfToken);

                fetch('<?= $baseurl ?>/user/control/delete-ticket.php', {
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