<?php include_once(__DIR__ . '/../inc_head.php') ?>
<style>
    .btn-minus,
    .btn-plus {
        font-weight: bold;
        font-size: 1.2rem;
        display: inline-block;
        line-height: 1;
        text-decoration: none;
        width: 2rem;
        height: 2rem;
        text-align: center;
        padding: 0;
    }
</style>
</head>

<body>

    <?php include_once(__DIR__ . '/../inc_header.php') ?>

    <section id="data" class="container my-5">
        <h1><?= $ticket_data ? 'Update ' : '' ?>Tickets</h1>

        <ul class="nav nav-tabs" id="ticketTabs" role="tablist">
            <!-- Ticket Overview Tab -->
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview"
                    type="button" role="tab" aria-controls="overview" aria-selected="true">
                    Ticket Overview
                </button>
            </li>
            <!-- Attachments Tab -->
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="attachments-tab" data-bs-toggle="tab" data-bs-target="#attachments"
                    type="button" role="tab" aria-controls="attachments" aria-selected="false">
                    Attachments
                </button>
            </li>
            <!-- Comments Tab -->
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="comments-tab" data-bs-toggle="tab" data-bs-target="#comments" type="button"
                    role="tab" aria-controls="comments" aria-selected="false">
                    Comments
                </button>
            </li>
        </ul>

        <div class="tab-content mt-3" id="ticketTabsContent">

            <div id="status-message"></div>
            <!-- Ticket Overview Content -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                <form name="ticket_overview" action="" method="post" id="overview-form" novalidate>

                    <div class="row mb-3">
                        <!-- Title -->
                        <div class="col-md-9">
                            <label for="ticketTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="ticketTitle" name="ticketTitle" required
                                minlength="5" maxlength="20" value="<?= $ticket_data->issue_title ?? '' ?>" />
                        </div>
                        <!-- App Name (combobox) -->
                        <div class="col-md-3">
                            <label for="appName" class="form-label">App Name</label>
                            <select class="form-select" id="appName" name="appName" required>
                                <?= $cbo_app ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">

                        <!-- Issue Type (combobox) -->
                        <div class="col-md-4">
                            <label for="issueType" class="form-label">Issue Type</label>
                            <select class="form-select" id="issueType" name="issueType" required>
                                <?= $cbo_issue_type ?>
                            </select>
                        </div>
                        <!-- Priority (combobox) -->
                        <div class="col-md-4">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-select" id="priority" name="priority" required>
                                <?= $cbo_priority ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select" required>
                                    <?= $cbo_status ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <!-- Issue Description (textarea) -->
                        <div class="col">
                            <label for="issueDescription" class="form-label">Issue Description</label>
                            <textarea class="form-control" id="issueDescription" name="issueDescription" rows="4"
                                required minlength="5" maxlength="300"><?= $ticket_data->issue_description ?? '' ?></textarea>
                        </div>

                    </div>
                    <input type="hidden" name="ticket_id" value="<?= $ticket_data->ticket_id ?? '' ?>">
                    <button type="submit" class="btn btn-primary" id="overview-submit"><span
                            class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                        <?= $ticket_data ? 'Update' : 'Save' ?>
                        Ticket</button>
                </form>
            </div>

            <!-- Attachments Content -->
            <div class="tab-pane fade" id="attachments" role="tabpanel" aria-labelledby="attachments-tab">
                <form name="ticket_attachments" action="" id="form-attachments" method="post"
                    enctype="multipart/form-data" novalidate>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="attachmentsTable">
                            <thead>
                                <tr>
                                    <th style="width:50px;"></th>
                                    <th>Document Title</th>
                                    <th>Upload Document</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($pictures)) {
                                    foreach ($pictures as $index => $picture) {
                                        ?>
                                                <tr>
                                                    <td>
                                                        <button type="button" class="btn btn-link text-danger btn-minus"
                                                            onclick="removeRow(this, <?= $picture->id ?>)">–</button>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="doc_titleup[<?= $picture->id ?>]"
                                                            value="<?= htmlspecialchars($picture->title) ?>" required />
                                                        <input type="hidden" name="existing_files[]" value="<?= $picture->id ?>">
                                                    </td>
                                                    <td>
                                                        <img src="<?= $picture->url ?>" alt="Uploaded Image"
                                                            style="max-width: 100px; display: block">
                                                    </td>
                                                </tr>
                                                <?php
                                    }
                                } else {
                                    ?>
                                        <tr>
                                            <td>
                                                <button type="button" class="btn btn-link text-danger btn-minus"
                                                    onclick="removeRow(this)">–</button>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="doc_title[]"
                                                    placeholder="Document Title" required />
                                            </td>
                                            <td>
                                                <input type="file" class="form-control" required name="doc_file[]"
                                                    accept="image/*" />
                                            </td>
                                        </tr>
                                        <?php
                                }
                                ?>
                            </tbody>

                        </table>
                    </div>
                    <button type="button" class="btn btn-link text-success btn-plus" id="addAttachmentRow">+</button>
                    <br /><br />

                    <input type="hidden" name="ticket_id" value="<?= $ticket_id ?? '' ?>">
                    <input type="hidden" name="deleted_files" id="deleted_files">

                    <button type="submit" class="btn btn-primary submit-btn" id="attachments-submit" <?= empty($pictures) ? 'disabled' : '' ?>>
                        <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span> Save
                        Attachments</button>
                </form>
            </div>

            <!-- Comments Content -->
            <div class="tab-pane fade" id="comments" role="tabpanel" aria-labelledby="comments-tab">
                <form name="ticket_comments" action="" id="message-form" method="post">
                    <div class="mb-3">
                        <label for="newMessage" class="form-label">Message</label>
                        <textarea class="form-control" id="newMessage" name="newMessage" rows="3" required minlength="5"
                            maxlength="30"></textarea>
                    </div>
                    <input type="hidden" name="ticket_id" value="<?= $ticket_id ?? '' ?>">

                    <button type="submit" class="btn btn-primary mb-4 submit-btn" id="message-submit"
                        <?= empty($comments) ? 'disabled' : '' ?>><span class="spinner-border spinner-border-sm d-none"
                            aria-hidden="true"></span> Post
                        Comment</button>
                </form>

                <!-- History of comments/messages -->
                <h5 class="mb-3">Previous Messages</h5>
                <div id="previousMessages">
                    <?php if (!empty($comments)): ?>
                            <?php foreach ($comments as $comment): ?>
                                    <div class="d-flex justify-content-between border rounded p-2 mb-2">
                                        <div>
                                            <strong><?= htmlspecialchars($comment['full_name']) ?></strong><br>
                                            <small><?= date('Y-m-d h:i A', strtotime($comment['created_at'])) ?></small>
                                        </div>
                                        <div>
                                            <?= nl2br(htmlspecialchars($comment['message_body'])) ?>
                                        </div>
                                    </div>
                            <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            </div>

        </div>
    </section>

    <?php include_once(__DIR__ . '/../inc_footer.php') ?>
    <script src="<?= $baseurl ?>/templates/js/validate.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            var csrfToken;
            const message = document.querySelector('#status-message');
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

            const form = document.getElementById('overview-form');
            // overview form submit
            if (form) {
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    if (!validateForm(form)) {
                        return;
                    }

                    const submitButton = form.querySelector('#overview-submit');
                    const spinner = submitButton.querySelector('.spinner-border');

                    submitButton.disabled = true;
                    spinner.classList.remove('d-none');

                    const formData = new FormData(form);
                    formData.append('token', csrfToken);
                    const url = "<?= $baseurl ?>/user/control/ctl_add_ticket_overview.php";
                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            body: formData
                        });
                        fetchCsrfToken();
                        if (response.ok) {
                            const result = await response.json();
                            message.innerHTML = '';
                            console.log(result);
                            // add ticked id 
                            if (result.ticket_id) {
                                document.querySelectorAll('input[name="ticket_id"]').forEach(input => {
                                    input.value = result.ticket_id;
                                });
                                // remove disabled from buttons
                                document.querySelectorAll('.btn-primary').forEach(button => {
                                    button.removeAttribute('disabled');
                                });
                                let attachmentsTab = new bootstrap.Tab(document.getElementById('attachments-tab'));
                                attachmentsTab.show();
                            }
                            message.innerHTML = result.message;

                        } else {
                            console.error('Error:', response.statusText);
                        }
                    } catch (error) {
                        console.error(error);

                        message.innerHTML = '';
                        const error_message = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                           we got an unexpeced error. Please try again later.
                                           <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                       </div>`
                        message.innerHTML = error_message;
                    } finally {
                        submitButton.disabled = false;
                        spinner.classList.add('d-none');
                    }
                });

                const inputs = form.querySelectorAll('input, textarea, select, file');
                inputs.forEach(input => {
                    input.addEventListener('blur', () => {
                        validateInput(input);
                    });
                });
            }

            // attachment form submit
            const attachmentForm = document.getElementById('form-attachments');
            if (attachmentForm) {
                attachmentForm.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    if (!validateForm(attachmentForm)) {
                        return;
                    }

                    const submitButton = attachmentForm.querySelector('#attachments-submit');
                    const spinner = submitButton.querySelector('.spinner-border');

                    submitButton.disabled = true;
                    spinner.classList.remove('d-none');

                    const formData = new FormData(attachmentForm);
                    formData.append('token', csrfToken);

                    const url = "<?= $baseurl ?>/user/control/ctl_add_attachment.php";
                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            body: formData
                        });
                        fetchCsrfToken();
                        if (response.ok) {
                            const result = await response.json();
                            message.innerHTML = '';

                            if (result.status && result.ids && result.urls && result.titles) {
                                const tableBody = document.querySelector("#attachmentsTable tbody");
                                tableBody.innerHTML = "";
                                result.ids.forEach((id, index) => {
                                    let title = result.titles[index];
                                    let fileUrl = result.urls[index];

                                    let newRow = document.createElement("tr");
                                    newRow.innerHTML = `
                            <td>
                                <button type="button" class="btn btn-link text-danger btn-minus" onclick="removeRow(this, ${id})">–</button>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="doc_titleup[${id}]" value="${title}" required />
                                <input type="hidden" name="existing_files[]" value="${id}">
                            </td>
                            <td>
                                <img src="${fileUrl}" alt="Uploaded Image" style="max-width: 100px; display: block;">
                            </td>
                        `;

                                    tableBody.appendChild(newRow);
                                });

                                let attachmentsTab = new bootstrap.Tab(document.getElementById('comments-tab'));
                                attachmentsTab.show();
                                message.innerHTML = result.message;
                            }

                        } else {
                            console.error('Error:', response.statusText);
                        }

                    } catch (error) {
                        console.error(error);

                        message.innerHTML = '';
                        const error_message = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                       we got an unexpeced error. Please try again later.
                                       <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                   </div>`
                        message.innerHTML = error_message;
                    } finally {
                        submitButton.disabled = false;
                        spinner.classList.add('d-none');
                    }
                });

                const inputs = form.querySelectorAll('input, textarea, select, file');
                inputs.forEach(input => {
                    input.addEventListener('blur', () => {
                        validateInput(input);
                    });
                });
            }
            // comment form
            const commentForm = document.getElementById('message-form');
            if (commentForm) {
                commentForm.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    if (!validateForm(commentForm)) {
                        return;
                    }

                    const submitButton = commentForm.querySelector('#message-submit');
                    const spinner = submitButton.querySelector('.spinner-border');

                    submitButton.disabled = true;
                    spinner.classList.remove('d-none');

                    const formData = new FormData(commentForm);
                    formData.append('token', csrfToken);
                    const url = "<?= $baseurl ?>/user/control/ctl_add_message.php";
                    try {

                        const response = await fetch(url, {
                            method: 'POST',
                            body: formData
                        });
                        fetchCsrfToken();
                        if (response.ok) {
                            const result = await response.json();
                            message.innerHTML = '';
                            message.innerHTML = result.message;

                            document.querySelector('#newMessage').value = '';

                            let lastComment = document.createElement("div");
                            lastComment.innerHTML = `<div class="d-flex justify-content-between border rounded p-2 mb-2">
                            <div>
                                <strong>${result.username}</strong><br>
                                <small>${formatDateTime()}</small>
                            </div>
                            <div>
                        ${result.lastmessage}
                            </div>
                        </div>`;
                            document.getElementById('previousMessages').prepend(lastComment);
                        } else {
                            console.error('Error:', response.statusText);
                        }

                    } catch (error) {
                        console.error(error);

                        message.innerHTML = '';
                        const error_message = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                           we got an unexpeced error. Please try again later.
                                           <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                       </div>`
                        message.innerHTML = error_message;
                    } finally {
                        submitButton.disabled = false;
                        spinner.classList.add('d-none');
                    }
                });

                function formatDateTime() {
                    const now = new Date();

                    const year = now.getFullYear();
                    const month = String(now.getMonth() + 1).padStart(2, '0');
                    const day = String(now.getDate()).padStart(2, '0');

                    let hours = now.getHours();
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const amPm = hours >= 12 ? 'PM' : 'AM';

                    hours = hours % 12 || 12;

                    return `${year}-${month}-${day} ${hours}:${minutes} ${amPm}`;
                }



                const inputs = form.querySelectorAll('input, textarea, select, file');
                inputs.forEach(input => {
                    input.addEventListener('blur', () => {
                        validateInput(input);
                    });
                });
            }


        });


        function removeRow(btn, imageId = '') {
            var row = btn.closest('tr');
            if (row.parentNode.rows.length > 1) {
                row.remove();
            }
            if (imageId) {
                let deletedFilesInput = document.getElementById("deleted_files");
                if (deletedFilesInput) {
                    deletedFilesInput.value += imageId + ",";
                }
            }

        }

        function addRow(tableId, columns) {
            const table = document.getElementById(tableId).querySelector('tbody');
            const newRow = document.createElement('tr');

            columns.forEach(function (cellHtml) {
                const td = document.createElement('td');
                td.innerHTML = cellHtml;
                newRow.appendChild(td);
            });

            table.appendChild(newRow);
        }

        // Add Attachment Row
        document.getElementById('addAttachmentRow').addEventListener('click', function () {
            addRow('attachmentsTable', [
                '<button type="button" class="btn btn-link text-danger btn-minus" onclick="removeRow(this)">–</button>',
                '<input type="text" class="form-control" name="doc_title[]" placeholder="Document Title"/>',
                '<input type="file" class="form-control" name="doc_file[]"/>'
            ]);
        });
    </script>


</body>

</html>