<?php
session_start();
require 'config.php';

// Redirect to login if username or position not set
if (!isset($_SESSION['username'], $_SESSION['position'], $_SESSION['full_name'])) {
    header("Location: login.php");
    exit();
}

$position = strtolower($_SESSION['position']);
$class_teaching = $_SESSION['class_teaching'] ?? '';
$username = $_SESSION['username'];
$full_name = $_SESSION['full_name'];

$activities = [];
if ($position === 'teacher') {
    // Only show teacher activities if class_teaching is set
    if (!$class_teaching) {
        $activities = ['No assigned class teaching found.' => '#'];
    } else {
        // Teacher activities
        $activities = [
            'Record Attendance' => 'teacher_attendance.php',
            'Upload Lesson Notes' => 'teacher_notes.php',
            'View Timetable' => 'view_timetable.php',
            'Record Student Performance' => 'record_performance.php',
            'Create Take-Away Assignment' => 'create_assignment.php',
          'view messages' => 'view_messages.php',
            'View Student Submissions' => 'view_submissions.php',
            
            'Send Complaint/Letter to Secretary' => 'send letter.php' // ✅ this line added/fixed
        ];
    }
}

 else {
    // Non-teacher roles with position based activities
    switch ($position) {
        case 'librarian':
            $activities = [
                'Register New Book' => 'librarian_add_book.php',
                'Issue Book to Student' => 'librarian_issue_book.php',
                'Track Returned Books' => 'librarian_returned_books.php',
                'Send Comment to Headteacher' => 'send_comment.php',
                'view reply from Headteacher' => 'view_reply.php',
            ];
            break;
        case 'secretary':
            $activities = [
                'Receive Letters' => 'receive_letters.php',
                'Type Official Documents' => 'secretary_type_documents.php',
                'Maintain Office Inventory' => 'secretary_inventory.php',
                'Send Comment to Headteacher' => 'send_comment.php',
                'view messages' => 'view_messages.php',
                
            ];

            break;
        case 'school nurse':
            $activities = [
                'Record Health Incidents' => 'nurse_health_log.php',
                'Student Health Checkups' => 'nurse_checkups.php',
                'Manage First Aid Supplies' => 'nurse_supplies.php',
  
                'view messages' => 'view_messages.php',
            ];
            break;
        case 'patron':
            $activities = [
                'Monitor Student Welfare' => 'patron_welfare.php',
                'Plan Club Activities' => 'patron_club_plans.php',
                'Prepare House Reports' => 'patron_house_reports.php',
          'view messages' => 'view_messages.php', ];
            break;
        case 'matron':
            $activities = [
                'Manage Dormitory Issues' => 'matron_dorm.php',
                'Record Sick Reports' => 'matron_health_log.php',
                'Handle Lost & Found' => 'matron_lost_found.php',
                'view messages' => 'view_messages.php',
            ];
            break;
        default:
            $activities = ['No specific role activities assigned.' => '#'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Staff Dashboard</title>
<style>
  body {
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f0f2f5;
    margin: 20px;
  }
  .dashboard {
    max-width: 900px;
    margin: auto;
    background: #fff;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
  }
  h2 {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
    font-weight: 700;
  }
  .activities {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
  }
  .activity {
    background: #ffffff;
    border-radius: 10px;
    padding: 25px 20px;
    box-shadow: 0 4px 12px rgb(0 0 0 / 0.1);
    text-align: center;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .activity:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgb(0 0 0 / 0.15);
  }
  .activity a {
    display: inline-block;
    font-size: 18px;
    font-weight: 600;
    color: #0066cc;
    text-decoration: none;
    padding: 12px 18px;
    border: 2px solid #0066cc;
    border-radius: 8px;
    transition: background-color 0.3s ease, color 0.3s ease;
  }
  .activity a:hover {
    background-color: #0066cc;
    color: white;
  }
  @media (max-width: 480px) {
    .dashboard {
      padding: 20px 15px;
    }
  }
</style>
</head>
<body>

<div class="dashboard">
  <h2>Welcome, <?= htmlspecialchars($full_name) ?> (<?= htmlspecialchars(ucfirst($position)) ?>)</h2>
  <?php if ($position === 'teacher'): ?>
    <p style="text-align:center; margin-bottom: 30px; color:#555; font-size:1.1rem;">
      Assigned Class: <strong><?= htmlspecialchars(strtoupper($class_teaching)) ?></strong>
    </p>
  <?php endif; ?>

  <div class="activities">
    <?php foreach ($activities as $label => $url): ?>
      <div class="activity">
        <a href="<?= htmlspecialchars($url) ?>"><?= htmlspecialchars($label) ?></a>
      </div>
    <?php endforeach; ?>
  </div>
</div>
    <div class="logout">
        <a href="logout.php"> Logout</a>
    </div>
</div>

</body>
</html>
