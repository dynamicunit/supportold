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
      <div class="col-6 col-lg-4">
        <div class="card text-center p-3 shadow-sm">
          <i data-feather="inbox" class="mb-2" style="width: 28px; height: 28px;"></i>
          <h5 class="fw-bold mb-0"><?= $total_tickets ?></h5>
          <small class="text-muted">Total Tickets</small>
        </div>
      </div>

      <div class="col-6 col-lg-4">
        <div class="card text-center p-3 shadow-sm">
          <i data-feather="alert-circle" class="mb-2 text-warning" style="width: 28px; height: 28px;"></i>
          <h5 class="fw-bold mb-0"><?= $open_tickets ?></h5>
          <small class="text-muted">Open Tickets</small>
        </div>
      </div>

      <div class="col-6 col-lg-4">
        <div class="card text-center p-3 shadow-sm">
          <i data-feather="check-circle" class="mb-2 text-success" style="width: 28px; height: 28px;"></i>
          <h5 class="fw-bold mb-0"><?= $closed_tickets ?></h5>
          <small class="text-muted">Closed Tickets</small>
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
              <?php if ($render) {
                foreach ($render as $key => $row) { ?>
                  <tr>
                    <td id="ticket_<?= $row['ticket_id'] ?>">#<?= $key + 1 ?></td>
                    <td><?= htmlspecialchars($row['issue_title']) ?></td>
                    <td>
                      <?php
                      $assigned_user = $row['assigned_user_id'] ? $user->fetch('users', ['id', ['=', $row['assigned_user_id']]])->full_name : '';
                      $statusClass = 'bg-secondary'; // default
                      switch (strtolower($row['status_name'])) {
                        case 'draft':
                          $statusClass = 'bg-light text-dark';
                          break;
                        case 'approved':
                          $statusClass = 'bg-success';
                          break;
                        case 'assigned':
                          $statusClass = 'bg-primary';
                          break;
                        case 'pending':
                          $statusClass = 'bg-warning text-dark';
                          break;
                        case 'rejected':
                          $statusClass = 'bg-danger';
                          break;
                        case 'cancelled':
                          $statusClass = 'bg-dark';
                          break;
                        case 'closed':
                          $statusClass = 'bg-secondary';
                          break;
                        case 'initial':
                          $statusClass = 'bg-info';
                          break;
                      }
                      ?>
                      <span class="badge <?= $statusClass ?>">
                        <?= htmlspecialchars($row['status_name']) ?>
                      </span>
                    </td>
                    <td><?= $assigned_user ? $assigned_user : 'Not assigned yet' ?></td>
                    <td><?= functions::timeAgo($row['created_at']) ?></td>
                  </tr>
                <?php }
              } else {
                echo '<tr><td colspan="5" class="text-center text-muted">No records found</td></tr>';
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>




  <?php include_once(__DIR__ . '/../inc_footer.php') ?>


</body>

</html>