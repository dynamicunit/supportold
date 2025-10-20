<!-- TOP NAV -->
    <nav class="navbar fixed-top px-3">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <!-- Toggle hidden on lg+ -->
                <button class="btn btn-outline-dark me-2 d-lg-none" id="sidebarToggle" style="z-index: 1200">
                    ☰
                </button>
                <a class="navbar-brand" href="#">My Dashboard</a>
            </div>

            <div class="nav-tools d-flex align-items-center text-light">
                <button class="icon-btn" aria-label="Notifications">
                    <i data-feather="bell" class="icon-md"></i>
                </button>
                <button class="icon-btn" aria-label="Messages">
                    <i data-feather="message-circle" class="icon-md"></i>
                </button>
                <div class="dropdown">
                    <button class="btn d-flex align-items-center dropdown-toggle" data-bs-toggle="dropdown"
                        type="button" style="border: none; background: none;">
                        <img src="https://placeholder.pics/svg/30" alt="User" class="rounded-circle me-2" width="30"
                            height="30" />
                        <div class="text-start d-none d-sm-block">
                            <div class="fw-semibold text-dark user-name">John Doe</div>
                            <div class="text-muted user-role">Administrator</div>
                        </div>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li><a class="dropdown-item" href="#">Logout</a></li>
                    </ul>
                </div>


            </div>

        </div>
    </nav>

    <!-- SIDEBAR + OVERLAY -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

   <aside class="sidebar" id="sidebar">
  <ul class="nav nav-pills flex-column mb-auto mt-3">
    <li class="nav-item">
      <a class="nav-link active" href="#">
        <i data-feather="home" class="icon-md me-2"></i> Home
      </a>
    </li>

    <li>
      <a class="nav-link" href="<?= $baseurl ?>/user/tickets">
        <i data-feather="inbox" class="icon-md me-2"></i> Tickets
      </a>
    </li>

    <li>
      <a class="nav-link" href="#">
        <i data-feather="bar-chart-2" class="icon-md me-2"></i> Reports
      </a>
    </li>

    <li>
      <a class="nav-link" href="#">
        <i data-feather="settings" class="icon-md me-2"></i> Settings
      </a>
    </li>

    <li class="mt-3 border-top pt-3">
      <a class="nav-link" href="#">
        <i data-feather="log-out" class="icon-md me-2"></i> Logout
      </a>
    </li>
  </ul>
</aside>
