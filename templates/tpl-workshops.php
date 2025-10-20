<?php include_once('inc_head.php') ?>
<style>
    /* Card hover effect */
    .workshop-card:hover {
        transform: translateY(-5px);
        transition: 0.3s ease-in-out;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
    }

    .card i {
        font-size: 0.93rem;
        padding-right: 0.3rem;
    }
</style>

</head>

<body>
    <?php include_once('inc_header.php') ?>
    <section class="bg-primary text-light py-3">
        <div class="container">
            <div class="d-flex align-items-center fs-4">
                <i class="ft-calendar" class="text-primary"></i>
                <h1 class="fw-bold text-light h3 mb-0 mt-0 ms-2">Workshops</h1>
            </div>

        </div>
    </section>


    <!-- Upcoming Workshops -->
    <div class="container my-5">

        <div class="row g-4">
            <?php
            if ($render) {
                foreach ($render as $key => $workshop) {
                    if ($workshop['status'] === 'Upcoming') { ?>
                        <div class="col-md-6">
                            <div class="card workshop-card shadow-sm p-3">
                                <div class="d-flex align-items-center">
                                    <i class="ft-briefcase" class="text-primary me-2"></i>
                                    <h5 class="fw-bold mb-0"><?= $workshop['title'] ?></h5>
                                </div>
                                <p class="text-muted mt-2"><?= $workshop['description'] ?></p>
                                <ul class="list-unstyled">
                                    <li><i class="ft-map-pin" class="me-1"></i> Venue: <?= $workshop['venue'] ?></li>
                                    <li><i class="ft-clock" class="me-1"></i> Date:
                                        <?= htmlspecialchars(date('F j, Y', strtotime($workshop['date']))) ?>
                                    </li>
                                    <li><i class="ft-info" class="me-1"></i> Status: <span
                                            class="badge bg-warning text-dark"><?= $workshop['status'] ?></span></li>
                                </ul>
                                <a href="#" class="btn btn-primary btn-sm">Register Now</a>
                            </div>
                        </div>
                    <?php }
                }
            }
            ?>

        </div>
    </div>

    <!-- Completed Workshops -->
    <div class="container my-5">
        <h3 class="fw-bold text-secondary"><i class="ft-check-circle"></i> Completed Workshops</h3>
        <div class="row g-4">
            <?php
            if ($render) {
                foreach ($render as $key => $workshop) {
                    if ($workshop['status'] === 'Completed') { ?>
                        <div class="col-md-6">
                            <div class="card workshop-card shadow-sm p-3">
                                <div class="d-flex align-items-center">
                                    <i class="ft-bar-chart-2" class="text-secondary me-2"></i>
                                    <h5 class="fw-bold mb-0"><?= $workshop['title'] ?></h5>
                                </div>
                                <p class="text-muted mt-2"><?= $workshop['description'] ?>
                                </p>
                                <ul class="list-unstyled">
                                    <li><i class="ft-map-pin" class="me-1"></i> Venue: <?= $workshop['venue'] ?></li>
                                    <li><i class="ft-clock" class="me-1"></i> Date:
                                        <?= htmlspecialchars(date('F j, Y', strtotime($workshop['date']))) ?>
                                    </li>
                                    <li><i class="ft-info" class="me-1"></i> Status: <span
                                            class="badge bg-success"><?= $workshop['status'] ?></span>
                                    </li>
                                </ul>
                                <div>
                                    <i class="ft-users" class="me-1"></i> <strong>Participants:</strong> John Doe, Jane Smith,
                                    Michael Brown...
                                </div>
                                <a href="#" class="btn btn-outline-secondary btn-sm mt-2">View Summary</a>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
            ?>
        </div>
    </div>

    <?php include_once('inc_footer.php') ?>

</body>

</html>