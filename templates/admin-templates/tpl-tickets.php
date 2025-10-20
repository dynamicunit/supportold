<?php include_once(__DIR__ . '/../inc_head.php') ?>
</head>

<body>

    <main class="page-wrapper">
        <?php include_once(__DIR__ . '/../inc_header.php') ?>
        <section class="container my-5">
            <div class="row">
                <div class="col">
                    <div class="d-flex align-items-center justify-content-between">
                        <h1>Tickets Lists</h1>

                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <?= $view->page_messages($validation_errors); ?>
                    <div id="message"></div>
                    <div class="table-responsive mt-1">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Issue Title</th>
                                    <th>Issue Description</th>
                                    <th>Assigned To</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($render) {
                                    foreach ($render as $key => $row): ?>
                                        <tr id="ticket_<?= $row['ticket_id'] ?>">
                                            <td><?= htmlspecialchars($row['issue_title']) ?></td>
                                            <td><?= htmlspecialchars($row['issue_description']) ?></td>
                                            <td>
                                                <div class="form-group">
                                                    <select name="assignuser" id="assignuser" class="form-select">
                                                        <option selected disabled>Assign To User</option>
                                                        <?php foreach ($admin_users as $user) { ?>
                                                            <option value="<?= $user->id ?>" <?= $row['assigned_user_id'] === $user->id ? 'selected' : '' ?>><?= $user->full_name ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-outline-primary"
                                                    href="<?= $baseurl ?>/admin/manage-ticket/<?= $row['ticket_id'] ?>"><i
                                                        class="fas fa-pencil"></i></a>
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

            document.getElementById('assignuser').addEventListener('change', function () {
                const selectedUserId = this.value;
                const ticketId = this.closest('tr').id.replace('ticket_', '');
                const formData = new FormData();
                const message = document.getElementById('message');
                message.innerHTML = '';
                formData.append('assignuser', selectedUserId);
                formData.append('ticket', ticketId);
                formData.append('token', csrfToken);
                $url = "<?= $baseurl ?>/admin/control/assign-user.php";
                fetch($url, {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        message.innerHTML = data.message;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while assigning the user.');
                    });
            });

        });
    </script>

</body>

</html>