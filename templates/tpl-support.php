<?php include_once('inc_head.php') ?>
</head>

<body>
    <?php include_once('inc_header.php') ?>

    <!-- Page Heading -->
    <section class="bg-primary text-light py-3">
        <div class="container">
            <div class="d-flex align-items-center fs-4">
                <i class="ft-inbox" class="text-primary"></i>
                <h1 class="fw-bold text-light h3 mb-0 mt-0 ms-2">Support Tickets</h1>
            </div>

        </div>
    </section>
    <!-- Support Tickets Table -->
    <div class="container my-5">
        <div class="table-responsive">
            <table class="table table-hover table-bordered bg-white shadow-sm">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Description</th>
                        <th>Date Created</th>
                        <th>Created By</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($render) {
                        foreach ($render as $key => $row) {

                            switch ($row['status']) {
                                case 'Initiated':
                                    $background_color = 'text-primary';
                                    break;
                                case 'Approved by Client Manager':
                                    $background_color = 'text-success';
                                    break;
                                case 'Closed':
                                    $background_color = 'text-danger';
                                    break;
                            }
                            ?>
                            <tr>
                                <td class=""><?= $key + 1 ?></td>
                                <td><?= htmlspecialchars($row['issue_description']) ?></td>
                                <td><?= htmlspecialchars(date('Y-m-d', strtotime($row['created_at']))) ?></td>
                                <td><?= htmlspecialchars($row['user_name']) ?></td>
                                <td class="">
                                    <span class=" <?= $background_color ?>"><?= $row['status'] ?></span>
                                </td>
                            </tr>
                        <?php }
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
    <?php include_once('inc_footer.php') ?>

</body>

</html>