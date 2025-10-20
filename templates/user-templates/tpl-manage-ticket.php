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

    /* Active tab: custom background + remove rounded bottom edge */
    .nav-tabs .nav-link.active {
        background-color: #dee2e6;
        /* your brand primary */
        color: black;
        font-weight: 500;
        border: 1px solid #dee2e6;
        /* keep border */
        border-bottom-color: transparent;
        /* hide bottom border */
        border-radius: 0;
        /* remove rounded corners if any */
    }

    /* Optional: make all tabs square-edged */
    .nav-tabs .nav-link {
        border-radius: 0;
    }
</style>
</head>

<body>
    <main class="content">
        <?php include_once(__DIR__ . '/../inc_sidebar.php') ?>


        <section>
            <div class="container-fluid mb-2">
                <div class="row">
                    <div class="col">

                        <!-- Title + Button in one row -->
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                <h1 class="text-dark mb-0 me-3">
                                    <?= $ticket_data ? 'Update ' : '' ?>Ticket #<?= $ticket_id ?>
                                </h1>
                                <a href="<?= $baseurl ?>/user/tickets" class="btn btn-light px-2 py-1">
                                    <i class="fas fa-list"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Breadcrumb below -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="<?= $baseurl ?>/user/dashboard">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="<?= $baseurl ?>/user/tickets">Tickets</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Ticket #<?= $ticket_id ?>
                                </li>
                            </ol>
                        </nav>

                    </div>
                </div>
            </div>
        </section>

        <section id="data" class="container-fluid bg-white p-0 shadow-sm rounded">

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
                    <button class="nav-link" id="comments-tab" data-bs-toggle="tab" data-bs-target="#comments"
                        type="button" role="tab" aria-controls="comments" aria-selected="false">
                        Comments
                    </button>
                </li>
                <!-- Closing Tab -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="closing-tab" data-bs-toggle="tab" data-bs-target="#closing"
                        type="button" role="tab" aria-controls="closing" aria-selected="false">
                        Closing
                    </button>
                </li>
            </ul>

            <div class="tab-content border p-4" id="ticketTabsContent">

                <div id="status-message"></div>
                <!-- Ticket Overview Content -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                    <form name="ticket_overview" action="" method="post" id="overview-form" novalidate>

                        <!-- Title -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-2 text-md-end">
                                <label for="ticketTitle" class="form-label mb-0">Title</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="ticketTitle" name="ticketTitle"
                                    required minlength="5" maxlength="200"
                                    value="<?= $ticket_data->issue_title ?? '' ?>" />
                            </div>
                        </div>

                        <!-- App Name -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-2 text-md-end">
                                <label for="appName" class="form-label mb-0">App Name</label>
                            </div>
                            <div class="col-md-6">
                                <select class="form-select" id="appName" name="appName" required>
                                    <?= $cbo_app ?>
                                </select>
                            </div>
                        </div>

                        <!-- Department -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-2 text-md-end">
                                <label for="department" class="form-label mb-0">Department</label>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" id="department" name="department" required>
                                    <?= $cbo_dep ?>
                                </select>
                            </div>
                        </div>

                        <!-- Issue Type -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-2 text-md-end">
                                <label for="issueType" class="form-label mb-0">Issue Type</label>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" id="issueType" name="issueType" required>
                                    <?= $cbo_issue_type ?>
                                </select>
                            </div>
                        </div>

                        <!-- Priority -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-2 text-md-end">
                                <label for="priority" class="form-label mb-0">Priority</label>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" id="priority" name="priority" required>
                                    <?= $cbo_priority ?>
                                </select>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-2 text-md-end">
                                <label for="status" class="form-label mb-0">Status</label>
                            </div>
                            <div class="col-md-4">
                                <select name="status" id="status" class="form-select" required>
                                    <option selected disabled>--select status--</option>
                                    <option value="0" selected disabled><?= htmlspecialchars($current_status) ?></option>
                                    <?php foreach ($statuses as $id => $name): ?>
                                        <option value="<?= htmlspecialchars($id) ?>"
                                            <?= (isset($ticket_data->ticket_status) && $ticket_data->ticket_status == $id) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($name) ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <!-- Issue Description -->
                        <div class="row mb-3 align-items-start">
                            <div class="col-md-2 text-md-end">
                                <label for="issueDescription" class="form-label mb-0">Issue Description</label>
                            </div>
                            <div class="col-md-10">
                                <textarea class="form-control" id="issueDescription" name="issueDescription" rows="4"
                                    required minlength="5" maxlength="1000"><?= $ticket_data->issue_description ?? '' ?></textarea>
                            </div>
                        </div>

                        <input type="hidden" name="ticket_id" value="<?= $ticket_data->ticket_id ?? '' ?>">

                        <!-- Submit -->
                        <div class="row">
                            <div class="col-md-10 offset-md-2">
                                <button type="submit" class="btn btn-primary" id="overview-submit">
                                    <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                                    <i class="fas fa-save me-1"></i>
                                    <?= $ticket_data ? 'Update' : 'Save' ?>
                                </button>
                            </div>
                        </div>

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
                                                    <input type="text" class="form-control"
                                                        name="doc_titleup[<?= $picture->id ?>]"
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
                        <button type="button" class="btn btn-link text-success btn-plus"
                            id="addAttachmentRow">+</button>
                        <br /><br />

                        <input type="hidden" name="ticket_id" value="<?= $ticket_id ?? '' ?>">
                        <input type="hidden" name="deleted_files" id="deleted_files">

                        <button type="submit" class="btn btn-primary submit-btn" id="attachments-submit"
                            <?= empty($ticket_data) ? 'disabled' : '' ?>><span
                                class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span> Save
                            Attachments</button>
                    </form>
                </div>

                <!-- Comments Content -->
                <div class="tab-pane fade" id="comments" role="tabpanel" aria-labelledby="comments-tab">
                    <form name="ticket_comments" action="" id="message-form" method="post" novalidate>
                        <div class="mb-3">
                            <label for="newMessage" class="form-label">Message</label>
                            <textarea class="form-control" id="newMessage" name="newMessage" rows="3" required
                                minlength="5" maxlength="30"></textarea>
                        </div>
                        <input type="hidden" name="ticket_id" value="<?= $ticket_id ?? '' ?>">

                        <button type="submit" class="btn btn-primary mb-4 submit-btn" id="message-submit"
                            <?= empty($ticket_data) ? 'disabled' : '' ?>><span
                                class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span> Post
                            Comment</button>
                    </form>

                    <h5 class="mb-3">Previous Messages</h5>
                    <div id="previousMessages">
                        <?php if (!empty($comments)): ?>
                            <?php foreach ($comments as $comment): ?>
                                <div class="pb-3 mb-3 border-bottom">
                                    <!-- Header: Name + Date -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold"><?= htmlspecialchars($comment['full_name']) ?></span>
                                        <small class="text-muted">
                                            <?= date('F j, Y · g:i A', strtotime($comment['created_at'])) ?>
                                        </small>
                                    </div>

                                    <!-- Message -->
                                    <div class="mt-1">
                                        <p class="mb-0 text-body">
                                            <?= nl2br(htmlspecialchars($comment['message_body'])) ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted fst-italic">No previous messages available.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Closing Information -->
                <div class="tab-pane fade" id="closing" role="tabpanel" aria-labelledby="closing-tab">
    <div class="container-fluid px-0">

        <div class="row border-bottom py-2 align-items-center">
            <div class="col-md-2 fw-semibold text-secondary text-md-end text-xl-end">Create Date</div>
            <div class="col-md-10 text-dark text-muted fst-italic">Not yet created</div>
        </div>

        <div class="row border-bottom py-2 align-items-center">
            <div class="col-md-2 fw-semibold text-secondary text-md-end text-xl-end">SLA Category / Priority</div>
            <div class="col-md-10 text-dark text-muted fst-italic">Not defined</div>
        </div>

        <div class="row border-bottom py-2 align-items-center">
            <div class="col-md-2 fw-semibold text-secondary text-md-end text-xl-end">SLA Target (in hours)</div>
            <div class="col-md-10 text-dark text-muted fst-italic">Not available</div>
        </div>

        <div class="row border-bottom py-2 align-items-center">
            <div class="col-md-2 fw-semibold text-secondary text-md-end text-xl-end">Assigned To</div>
            <div class="col-md-10 text-dark text-muted fst-italic">Not yet assigned</div>
        </div>

        <div class="row border-bottom py-2 align-items-center">
            <div class="col-md-2 fw-semibold text-secondary text-md-end text-xl-end">Closing Date</div>
            <div class="col-md-10 text-dark text-muted fst-italic">Not yet closed</div>
        </div>

        <div class="row border-bottom py-2 align-items-center">
            <div class="col-md-2 fw-semibold text-secondary text-md-end text-xl-end">Time Taken to Close</div>
            <div class="col-md-10 text-dark text-muted fst-italic">Not available</div>
        </div>

        <div class="row border-bottom py-2 align-items-start">
            <div class="col-md-2 fw-semibold text-secondary text-md-end text-xl-end">Closing Comments</div>
            <div class="col-md-10">
                <div class="border rounded p-3 bg-light text-muted fst-italic">
                    No closing comments available.
                </div>
            </div>
        </div>

    </div>
</div>




            </div>
        </section>
    </main>
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
                            let lastComment = document.createElement("div");
                            lastComment.innerHTML = `<div class="d-flex border rounded p-2 mb-2">
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

            columns.forEach(function(cellHtml) {
                const td = document.createElement('td');
                td.innerHTML = cellHtml;
                newRow.appendChild(td);
            });

            table.appendChild(newRow);
        }

        // Add Attachment Row
        document.getElementById('addAttachmentRow').addEventListener('click', function() {
            addRow('attachmentsTable', [
                '<button type="button" class="btn btn-link text-danger btn-minus" onclick="removeRow(this)">–</button>',
                '<input type="text" class="form-control" name="doc_title[]" placeholder="Document Title"/>',
                '<input type="file" class="form-control" name="doc_file[]"/>'
            ]);
        });
    </script>


</body>

</html>