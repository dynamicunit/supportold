<?php include_once(__DIR__ . '/../inc_head.php') ?>
</head>

<body>
    <?php include_once(__DIR__ . '/../inc_sidebar.php') ?>


    
<!-- CONTENT -->
<main class="content">
  <!-- Header Row -->
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <h1 class="mb-0">Dashboard</h1>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
      </ol>
    </nav>
  </div>

  <!-- Quick Stats Cards -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
      <div class="card text-center p-3 shadow-sm">
        <i data-feather="inbox" class="mb-2" style="width: 28px; height: 28px;"></i>
        <h5 class="fw-bold mb-0">128</h5>
        <small class="text-muted">Total Tickets</small>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="card text-center p-3 shadow-sm">
        <i data-feather="alert-circle" class="mb-2 text-warning" style="width: 28px; height: 28px;"></i>
        <h5 class="fw-bold mb-0">37</h5>
        <small class="text-muted">Open Tickets</small>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="card text-center p-3 shadow-sm">
        <i data-feather="check-circle" class="mb-2 text-success" style="width: 28px; height: 28px;"></i>
        <h5 class="fw-bold mb-0">82</h5>
        <small class="text-muted">Closed Tickets</small>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="card text-center p-3 shadow-sm">
        <i data-feather="clock" class="mb-2 text-secondary" style="width: 28px; height: 28px;"></i>
        <h5 class="fw-bold mb-0">9</h5>
        <small class="text-muted">Pending Tickets</small>
      </div>
    </div>
  </div>

  <!-- Recent Tickets Table -->
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title mb-3 d-flex align-items-center">
        <i data-feather="list" class="me-2 text-dark" style="width: 20px; height: 20px;"></i>
        Recent Tickets
      </h5>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th scope="col">Ticket ID</th>
              <th scope="col">Title</th>
              <th scope="col">Status</th>
              <th scope="col">Assigned To</th>
              <th scope="col">Created</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>#1024</td>
              <td>Login page error</td>
              <td><span class="badge bg-primary-subtle text-primary-emphasis rounded-pill">Open</span></td>
              <td>Sarah</td>
              <td>2h ago</td>
            </tr>
            <tr>
              <td>#1019</td>
              <td>Database connection timeout</td>
              <td><span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill">Closed</span></td>
              <td>Alex</td>
              <td>1d ago</td>
            </tr>
            <tr>
              <td>#1014</td>
              <td>Report export issue</td>
                <td><span class="badge bg-primary-subtle text-primary-emphasis rounded-pill">Open</span></td>
              <td>John</td>
              <td>3d ago</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>




    <?php include_once(__DIR__ . '/../inc_footer.php') ?>


</body>

</html>