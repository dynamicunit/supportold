<?php include_once(__DIR__ . '/../inc_head.php') ?>
</head>

<body>
    <main class="page-wrapper">
        <?php include_once(__DIR__ . '/../inc_header.php') ?>
        <section class="container my-5">
            <div class="row">
                <div class="col">
                    <h1 class="text-center">Admin Dashboard</h1>
                    <?= $view->page_messages(); ?>
                </div>
            </div>
        </section>
    </main>
    <?php include_once(__DIR__ . '/../inc_footer.php') ?>
</body>

</html>