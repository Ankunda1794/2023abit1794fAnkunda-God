<?php
session_start();
require 'config.php';

$errors = [];
$success = "";

// Updated stream assignment function
function assignStream($class, $course = '') {
    $scienceCourses = ['BCM/ICT', 'PCM/ICT', 'PCB/SUB', 'PEM/ICT', 'BAG/SUBMATHS'];
    if (in_array($class, ['S5', 'S6'])) {
        return in_array(strtoupper(trim($course)), $scienceCourses) ? 'Science Stream' : 'Arts Stream';
    } else {
        $streams = ['A', 'B', 'C'];
        return $streams[array_rand($streams)];
    }
}

function generateStudentID($gender) {
    return 'ST' . rand(1000, 9999);
}

function assigndormitory($gender) {
    $maleDormitories = ['New York', 'California', 'Los Angeles'];
    $femaleDormitories = ['Heavens', 'Matthew', 'Angels'];
    return ($gender === 'Male') ? $maleDormitories[array_rand($maleDormitories)] : $femaleDormitories[array_rand($femaleDormitories)];
}

function genAdmNo() {
    return 'ADM' . rand(10000, 99999);
}

function genReg($class) {
    return in_array($class, ['S4', 'S6']) ? 'REG' . rand(1000, 9999) : null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];
    $username = trim($_POST['username']);
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $document_path = null;
    if (!empty($_FILES['document']['name'])) {
        $upload_dir = "uploads/";
        $filename = uniqid() . "_" . basename($_FILES["document"]["name"]);
        if (move_uploaded_file($_FILES["document"]["tmp_name"], $upload_dir . $filename)) {
            $document_path = $upload_dir . $filename;
        } else {
            $errors[] = "Document upload failed.";
        }
    }

    if ($role === 'student') {
        $contact = $_POST['student_contact'];
        $gender = $_POST['student_gender'];
        $address = $_POST['student_address'];
        $dob = $_POST['student_dob'];
        $scholar_type = $_POST['student_scholar_type'];
        $class = $_POST['student_class'];
        $course = $_POST['student_course'] ?? '';
        $term = $_POST['student_term'] ?? '';
        $stream = assignStream($class, $course);
        $student_id = generateStudentID($gender);
        
        // Assign dormitory ONLY if scholar type is boarding, else NULL
        $dormitory = ($scholar_type === 'boarding') ? assigndormitory($gender) : null;
        
        $admission_no = genAdmNo();
        $registration_no = genReg($class); // Only for S4 and S6

        $stmt = $conn->prepare("INSERT INTO student_pending (
            username, full_name, email, password, contact, gender, address, dob,
            scholar_type, class, course, stream, student_id, dormitory,
            admission_no, registration_no, term, document_path
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

        $stmt->bind_param("ssssssssssssssssss",
            $username, $full_name, $email, $password,
            $contact, $gender, $address, $dob,
            $scholar_type, $class, $course, $stream,
            $student_id, $dormitory, $admission_no,
            $registration_no, $term, $document_path
        );

        if ($stmt->execute()) {
            $success = "Student registration submitted for verification.";
        } else {
            $errors[] = "Failed to register student.";
        }
        $stmt->close();

    } else {
        // Unchanged staff/admin logic...
        $position = $_POST['position'];
        $department = $_POST['department'];
        $qualifications = $_POST['qualifications'];
        $contact = $_POST['staff_contact'];
        $gender = $_POST['staff_gender'];
        $address = $_POST['staff_address'];
        $status = $_POST['staff_status'];
        $staff_type = $_POST['staff_type'] ?? null;
        $class_teaching = $_POST['class_teaching'] ?? null;
        $subject = $_POST['subject'] ?? null;

        if ($role === 'systems_admin' && $position === 'admin') {
            $stmt = $conn->prepare("INSERT INTO users (
              username, full_name, email, password,
              role, position, department, qualifications,
              contact, gender, address, status, document_path
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("sssssssssssss", $username, $full_name, $email, $password,
                $role, $position, $department, $qualifications,
                $contact, $gender, $address, $status, $document_path);
            $stmt->execute() ? $success = "Admin registered successfully." : $errors[] = "Admin registration failed.";
            $stmt->close();
        } elseif ($role === 'admin' && $position === 'headteacher') {
            $stmt = $conn->prepare("INSERT INTO admin_pending (
                username, full_name, email, password,
                role, position, department, qualifications,
                contact, gender, address, status, document_path
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("sssssssssssss", $username, $full_name, $email, $password,
                $role, $position, $department, $qualifications,
                $contact, $gender, $address, $status, $document_path);
            $stmt->execute() ? $success = "Headteacher request submitted." : $errors[] = "Failed to register headteacher.";
            $stmt->close();
        } else {
            $stmt = $conn->prepare("INSERT INTO workers_pending (
                username, full_name, email, password,
                role, position, department, qualifications,
                contact, gender, address, status,
                staff_type, class_teaching, subject, document_path
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("ssssssssssssssss", $username, $full_name, $email, $password,
                $role, $position, $department, $qualifications,
                $contact, $gender, $address, $status,
                $staff_type, $class_teaching, $subject, $document_path);
            $stmt->execute() ? $success = "Registration submitted for verification." : $errors[] = "Failed to register staff/admin.";
            $stmt->close();
        }
    }
}
?>

<!-- HTML remains unchanged except the stream/registration logic -->

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Registration</title>
  <style>
    body { font-family: Arial; background: #f4f4f4; padding: 20px; }
    form { background: #fff; padding: 25px; max-width: 600px; margin:auto; border-radius:6px; box-shadow:0 0 10px rgba(0,0,0,0.1); }
    label { display:block; font-weight:bold; margin-top:15px; }
    input, select { width:100%; padding:10px; margin-top:5px; }
    button { margin-top:20px; padding:10px; width:100%; background:#007BFF; color:#fff; border:none; border-radius:4px; font-size:16px; }
    .hidden { display:none; }
    .error { background:#f8d7da; color:#721c24; padding:12px; margin-bottom:15px; }
    .success { background:#d4edda; color:#155724; padding:12px; margin-bottom:15px; }
  </style>
</head>
<body>

<?php if ($errors): ?>
  <div class="error"><ul>
    <?php foreach ($errors as $e): ?><li><?=htmlspecialchars($e)?></li><?php endforeach; ?>
  </ul></div>
<?php endif; ?>

<?php if ($success): ?>
  <div class="success"><?=htmlspecialchars($success)?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
  <label>Role</label>
  <select name="role" id="role" required>
    <option value="">Select</option>
    <option value="student">Student</option>
    <option value="staff">Staff</option>
    <option value="admin">Admin</option>
    <option value="systems_admin">Systems Admin</option>
  </select>

  <label>Username</label><input type="text" name="username" required>
  <label>Full Name</label><input type="text" name="full_name" required>
  <label>Email</label><input type="email" name="email">
  <label>Password</label><input type="password" name="password" required>

  <!-- Student fields -->
  <div id="studentFields" class="hidden">
    <label>Contact</label><input type="text" name="student_contact">
    <label>Gender</label>
    <select name="student_gender">
      <option value="">Select</option><option value="Male">Male</option><option value="Female">Female</option>
    </select>
    <label>Address</label><input type="text" name="student_address">
    <label>Date of Birth</label><input type="date" name="student_dob">
    <label>Scholar Type</label>
    <select name="student_scholar_type"><option value="">Select</option><option value="boarding">Boarding</option><option value="day">Day</option></select>
    <label>Class</label>
    <select name="student_class" id="student_class">
      <option value="">Select</option><option value="S1">S1</option><option value="S2">S2</option><option value="S3">S3</option><option value="S4">S4</option><option value="S5">S5</option><option value="S6">S6</option>
    </select>
    <div id="studentCourseWrapper" class="hidden"><label>Course</label><input type="text" name="student_course"></div>
    <label>Term</label>
    <select name="student_term" required>
      <option value="">Select</option><option value="1">Term 1</option><option value="2">Term 2</option><option value="3">Term 3</option>
    </select>
  </div>

  <!-- Staff/Admin fields -->
  <div id="positionWrapper"><label>Position</label><select name="position" id="position"></select></div>
  <div id="staffTypeWrapper" class="hidden"><label>Staff Type</label><select name="staff_type" id="staff_type"><option value="">Select</option><option value="teaching">Teaching</option><option value="non-teaching">Non-teaching</option></select></div>
  <div id="classTeachingWrapper" class="hidden"><label>Class Teaching</label><input type="text" name="class_teaching"><label>Subject</label><input type="text" name="subject"></div>
  <div id="commonStaffFields">
    <label>Department</label><input type="text" name="department">
    <label>Qualifications</label><input type="text" name="qualifications">
    <label>Contact</label><input type="text" name="staff_contact">
    <label>Gender</label><select name="staff_gender"><option value="">Select</option><option value="Male">Male</option><option value="Female">Female</option></select>
    <label>Address</label><input type="text" name="staff_address">
    <label>Status</label><select name="staff_status"><option value="">Select</option><option value="active">Active</option><option value="inactive">Inactive</option></select>
  </div>

  <label>Attach Document (optional)</label><input type="file" name="document">
  <button type="submit">Register</button>
</form>

<script>
const role = document.getElementById('role');
const position = document.getElementById('position');
const staffTypeWrapper = document.getElementById('staffTypeWrapper');
const studentFields = document.getElementById('studentFields');
const studentClass = document.getElementById('student_class');
const studentCourseWrapper = document.getElementById('studentCourseWrapper');
const classTeachingWrapper = document.getElementById('classTeachingWrapper');
const staffType = document.getElementById('staff_type');
const commonStaffFields = document.getElementById('commonStaffFields');
const positionWrapper = document.getElementById('positionWrapper');

function clearOptions(sel){ while(sel.options.length) sel.remove(0); }
function fillCombinedPositions(){
  clearOptions(position);
  position.options.add(new Option("Select",""));
  ["headteacher","deputy headteacher","bursar","deputy bursar",
   "director of studies","deputy director of studies","patron","matron",
   "administrator","secretary","librarian","lab attendant","teacher"].forEach(p=> {
    position.options.add(new Option(p,p));
  });
}

role.addEventListener('change', () => {
  const isStudent = role.value === 'student';
  studentFields.classList.toggle('hidden', !isStudent);
  commonStaffFields.style.display = isStudent ? 'none' : 'block';
  positionWrapper.style.display = isStudent ? 'none' : 'block';
  staffTypeWrapper.classList.add('hidden');
  classTeachingWrapper.classList.add('hidden');
  clearOptions(position);
  if (role.value === 'staff') {
    staffTypeWrapper.classList.remove('hidden');
  } else if (["admin", "systems_admin"].includes(role.value)) {
    fillCombinedPositions();
  }
});

staffType.addEventListener('change', () => {
  clearOptions(position);
  if (staffType.value === 'teaching') {
    position.options.add(new Option("teacher","teacher"));
    classTeachingWrapper.classList.remove('hidden');
  } else if (staffType.value === 'non-teaching') {
    ["patron","matron","secretary","librarian","lab attendant"].forEach(p=>{
      position.options.add(new Option(p,p));
    });
    classTeachingWrapper.classList.add('hidden');
  }
});

studentClass.addEventListener('change', () => {
  studentCourseWrapper.classList.toggle('hidden', !['S5','S6'].includes(studentClass.value));
});
</script>
</body>
</html>
