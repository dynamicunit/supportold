<?php include_once('inc_head.php') ?>
<style>
  .card i {
    font-size: 1.6rem;
  }
</style>
</head>

<body>
  <?php include_once('inc_header.php') ?>

  <!-- Hero Section -->
  <section class="bg-primary text-white text-center py-5">
    <div class="container py-4">
      <h1 class="display-5 fw-bold text-white">Welcome to Dynamic Unit Support Portal</h1>
      <p class="lead">Manage your support tickets, access the knowledgebase, join workshops, and view reports
        effortlessly.</p>
      <a href="<?= $baseurl ?>/user/tickets" class="btn btn-lg btn-light text-primary mt-3">Get Support</a>
    </div>
  </section>

  <!-- Cards Section -->
  <section class="container py-5 my-5">
    <div class="row g-4">

      <!-- Support Tickets Card -->
      <div class="col-md-3">
        <div class="card text-center shadow-sm p-3 h-100">
          <i class="ft-inbox" class="text-primary fs-5"></i>
          <div class="card-body">
            <h5 class="card-title fw-bold">Tickets</h5>
            <p class="card-text">Manage your support requests with ease.</p>
            <a href="<?= $baseurl ?>/user/tickets" class="btn btn-outline-primary">View Tickets</a>
          </div>
        </div>
      </div>
<!--
      <div class="col-md-3">
        <div class="card text-center shadow-sm p-3 h-100">
          <i class="ft-book" class="text-primary display-4"></i>
          <div class="card-body">
            <h5 class="card-title fw-bold">Knowledgebase</h5>
            <p class="card-text">Find answers to frequently asked questions.</p>
            <a href="<?= $baseurl ?>/knowledgebase" class="btn btn-outline-primary">Browse Articles</a>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card text-center shadow-sm p-3 h-100">
          <i class="ft-calendar" class="text-primary display-4"></i>
          <div class="card-body">
            <h5 class="card-title fw-bold">Workshops</h5>
            <p class="card-text">Learn new skills and best practices.</p>
            <a href="<?= $baseurl ?>/workshops" class="btn btn-outline-primary">Join Workshop</a>
          </div>
        </div>
      </div>

   
      <div class="col-md-3">
        <div class="card text-center shadow-sm p-3 h-100">
          <i class="ft-bar-chart-2" class="text-primary display-4"></i>
          <div class="card-body">
            <h5 class="card-title fw-bold">Reports</h5>
            <p class="card-text">Get insights with detailed reports.</p>
            <a href="<?= $baseurl ?>/reporting" class="btn btn-outline-primary">View Reports</a>
          </div>
        </div>
      </div>
-->
    </div>
  </section>




  <?php include_once('inc_footer.php') ?>

</body>

</html>