<?php
// Annual Report
if (file_exists(SKBM_EXTENDER_MODULES_PATH . '/annual-report/annual-report.php')) {
  include SKBM_EXTENDER_MODULES_PATH . '/annual-report/annual-report.php';
}

// Financial Report
if (file_exists(SKBM_EXTENDER_MODULES_PATH . '/financial-report/financial-report.php')) {
  include SKBM_EXTENDER_MODULES_PATH . '/financial-report/financial-report.php';
}

// Public Disclosure
if (file_exists(SKBM_EXTENDER_MODULES_PATH . '/public-disclosure/public-disclosure.php')) {
  include SKBM_EXTENDER_MODULES_PATH . '/public-disclosure/public-disclosure.php';
}

// Press Release
if (file_exists(SKBM_EXTENDER_MODULES_PATH . '/press-release/press-release.php')) {
  include SKBM_EXTENDER_MODULES_PATH . '/press-release/press-release.php';
}

// General Meeting
if (file_exists(SKBM_EXTENDER_MODULES_PATH . '/general-meeting/general-meeting.php')) {
  include SKBM_EXTENDER_MODULES_PATH . '/general-meeting/general-meeting.php';
}

// Sustainability Report
if (file_exists(SKBM_EXTENDER_MODULES_PATH . '/sustains-report/sustains-report.php')) {
  include SKBM_EXTENDER_MODULES_PATH . '/sustains-report/sustains-report.php';
}
