<?php

namespace Drupal\userdashboardbpm\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;

/**
 * Controller for the User Dashboard BPM module.
 */
class UserDashboardBpmController extends ControllerBase {

  /**
   * Display the User Dashboard BPM.
   *
   * @return array
   *   The dashboard content.
   */
  public function dashboard() {
    $content = array();

    // Load user entities.
    $users = \Drupal::entityTypeManager()
      ->getStorage('user')
      ->loadMultiple();

    // Prepare user data for the template.
    $user_data = array();
    foreach ($users as $user) {
      $user_data[] = array(
        'id' => $user->id(),
        'name' => $user->getAccountName(),
        'email' => $user->getEmail(),
        'username' => $user->getAccountName(), // Utilize getAccountName() instead of getUsername().
        'status' => $user->isActive() ? 'Active' : 'Blocked',
        'roles' => implode(', ', $user->getRoles()),
        'member_for' => \Drupal::service('date.formatter')->formatTimeDiffSince($user->getCreatedTime()),
        'last_access' => $user->getLastAccessedTime() ? \Drupal::service('date.formatter')->format($user->getLastAccessedTime(), 'custom', 'Y-m-d H:i:s') : 'N/A',
        // Add more fields as needed.
      );
    }

    $content['users'] = $user_data;

    return array(
      '#theme' => 'userdashboardbpm_dashboard',
      '#content' => $content,
      '#attached' => array(
        'library' => array(
          'userdashboardbpm/userdashboardbpm-styling',
        ),
      ),
    );
  }
}
