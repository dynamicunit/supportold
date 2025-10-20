<?php include_once('inc_head.php') ?>

<style>
    /* Card hover effect */
    .kb-card:hover {
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
                <i class="ft-book" class="text-primary"></i>
                <h1 class="fw-bold text-light h3 mb-0 mt-0 ms-2">Knowledgebase</h1>
            </div>

        </div>
    </section>

    <!-- Knowledgebase Cards -->
    <div class="container my-5">
        <div class="row g-4">

            <?php
            if ($render) {
                foreach ($render as $key => $blog) { ?>
                    <div class="col-md-4">
                        <div class="card kb-card shadow-sm p-3">
                            <div class="d-flex align-items-center">
                                <i class="ft-tag" class="text-primary me-2"></i>
                                <h5 class="fw-bold mb-0"><?= $blog['blog_title'] ?></h5>
                            </div>
                            <p class="text-muted mt-2"><?= $blog['meta_desc'] ?></p>
                            <div class="d-flex align-items-center text-muted">
                                <i class="ft-clock" class="me-1"></i>
                                <small>Last updated:
                                    <strong><?= htmlspecialchars(date('F j, Y', strtotime($blog['updated_at']))) ?></strong></small>
                            </div>
                        </div>
                    </div>
                <?php }
            }
            ?>
        </div>
    </div>
    <?php include_once('inc_footer.php') ?>

</body>

</html>