<?php include_once('inc_head.php') ?>
<style>
    .report-card {
        transition: 0.3s;
    }

    .report-card:hover {
        transform: translateY(-3px);
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
    }
</style>
</head>

<body>
    <?php include_once('inc_header.php') ?>

    <section class="bg-primary text-light py-3">
        <div class="container">
            <div class="d-flex align-items-center fs-4">
                <i class="ft-bar-chart-2" class="text-primary"></i>
                <h1 class="fw-bold text-light h3 mb-0 mt-0 ms-2">Reporting</h1>
            </div>

        </div>
    </section>

    <!-- Reports Table -->
    <div class="container my-5">
        <div class="table-responsive">
            <table class="table table-bordered  bg-white">
                <thead class="table-light">
                    <tr>
                        <th style="width: 25%;"><i class="ft-file-text" class="me-1"></i> Report</th>
                        <th style="width: 30%;"><i class="ft-info" class="me-1"></i> Description</th>
                        <th><i class="ft-list" class="me-1"></i> Columns</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Sample Data Row -->
                    <?php if ($render) {
                        foreach ($render as $key => $row) {
                            $columns = json_decode($row['columns'], true);
                            $coloumns = implode(", ", $columns);
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <h6 class="fw-bold mb-0"><?= htmlspecialchars($row['report_name']) ?></h6>
                                        <a href="<?= $row['report_url'] ?>" target="_blank" class="icon-link ms-2">
                                            <i class="ft-external-link"></i>
                                        </a>
                                    </div>
                                    <span class="badge bg-dark"><?= htmlspecialchars($row['system_name']) ?></span><br>
                                    <small><i class="ft-clock" class="me-1"></i> Last Updated:
                                        <?= htmlspecialchars(date('F j, Y', strtotime($row['last_updated']))) ?></small>
                                </td>
                                <td><?= htmlspecialchars($row['description']) ?></td>
                                <td><?= $coloumns ?> </td>
                            </tr>
                        <?php }
                    } else {
                        echo '<tr><td colspan="3">No records found</td></tr>';
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