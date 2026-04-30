<?php
require_once __DIR__ . '/includes/dbstudents.php';


$students = [];
$result = $conn->query('SELECT id, surname, name, middlename, address, contact_number FROM students ORDER BY id DESC');
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    $result->free();
}

$updateStudent = null;
if (isset($_GET['update_id']) && intval($_GET['update_id']) > 0) {
    $uid  = intval($_GET['update_id']);
    $stmt = $conn->prepare('SELECT id, surname, name, middlename, address, contact_number FROM students WHERE id=?');
    if ($stmt) {
        $stmt->bind_param('i', $uid);
        $stmt->execute();
        $res = $stmt->get_result();
        $updateStudent = $res->fetch_assoc();
        $stmt->close();
    }
    
  
    if (!$updateStudent) {
        header('Location: index.php?status=update_error&section=update');
        exit;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <img src="images/torres.jpg" id="logo" alt="Dashboard" onclick="showSection('home')" style="cursor: pointer;">
        <button class="navbarbuttons" onclick="showSection('create')"> Create </button>
        <button class="navbarbuttons" onclick="showSection('read')"> Read </button>
        <button class="navbarbuttons" onclick="showSection('update')"> Update </button>
        <button class="navbarbuttons" onclick="showSection('delete')"> Delete </button>
    </nav>

    <!-- HOME -->
    <section id="home" class="homecontent">
        <h1 class="splash">Welcome to Student Management System</h1>
        <h2 class="splash">A Project in Integrative Programming Technologies</h2>
    </section>

    <!-- CREATE -->
    <section id="create" class="content">
        <h1 class="contenttitle"> Insert New Student </h1>

        <form action="includes/insert.php" method="POST">
            <div class="google-input">
                <input type="text" name="surname" id="surname" class="field" placeholder=" " required>
                <label for="surname" class="label">Surname</label>
            </div>

            <div class="google-input">
                <input type="text" name="name" id="name" class="field" placeholder=" " required>
                <label for="name" class="label">Name</label>
            </div>

            <div class="google-input">
                <input type="text" name="middlename" id="middlename" class="field" placeholder=" ">
                <label for="middlename" class="label">Middle name</label>
            </div>

            <div class="google-input">
                <input type="text" name="address" id="address" class="field" placeholder=" ">
                <label for="address" class="label">Address</label>
            </div>

            <div class="google-input">
                <input type="text" name="contact_number" id="contact_number" class="field" placeholder=" ">
                <label for="contact_number" class="label">Mobile Number</label>
            </div>

            <div id="btncontainer">
                <button type="button" id="clrbtn" class="btns" onclick="clearFields()">Clear Fields</button>
                <button type="submit" id="savebtn" class="btns">Save</button>
            </div>
        </form>

        <div id="success-toast" class="toast-hidden">
            Registration Successful!
        </div>
    </section>

    <!-- READ -->
    <section id="read" class="content">
        <h1 class="contenttitle">View Students</h1>
        <table id="student-table" class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Surname</th>
                    <th>Name</th>
                    <th>Middle Name</th>
                    <th>Address</th>
                    <th>Contact Number</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($students) > 0): ?>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['id']) ?></td>
                            <td><?= htmlspecialchars($student['surname']) ?></td>
                            <td><?= htmlspecialchars($student['name']) ?></td>
                            <td><?= htmlspecialchars($student['middlename']) ?></td>
                            <td><?= htmlspecialchars($student['address']) ?></td>
                            <td><?= htmlspecialchars($student['contact_number']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No student records available yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <!-- UPDATE -->
    <section id="update" class="content">
        <h1 class="contenttitle">Update Student Record</h1>

        <!-- Step 1: Search student by ID -->
        <form action="index.php" method="GET">
            <input type="hidden" name="section" value="update">
            <div class="google-input">
                <input type="number" name="update_id" id="update_id" class="field" placeholder=" " value="<?= htmlspecialchars($_GET['update_id'] ?? '') ?>" required min="1">
                <label for="update_id" class="label">Student ID</label>
            </div>
            <button type="submit" class="btns" style="margin-top:0;">Search</button>
        </form>

 
        <?php if ($updateStudent): ?>
        <br/>
        <form action="includes/update.php" method="POST">
            <input type="hidden" name="id" value="<?= $updateStudent['id'] ?>">

            <div class="google-input">
                <input type="text" name="surname" id="upd_surname" class="field" placeholder=" " value="<?= htmlspecialchars($updateStudent['surname']) ?>" required>
                <label for="upd_surname" class="label">Surname</label>
            </div>

            <div class="google-input">
                <input type="text" name="name" id="upd_name" class="field" placeholder=" " value="<?= htmlspecialchars($updateStudent['name']) ?>" required>
                <label for="upd_name" class="label">Name</label>
            </div>

            <div class="google-input">
                <input type="text" name="middlename" id="upd_middlename" class="field" placeholder=" " value="<?= htmlspecialchars($updateStudent['middlename']) ?>">
                <label for="upd_middlename" class="label">Middle Name</label>
            </div>

            <div class="google-input">
                <input type="text" name="address" id="upd_address" class="field" placeholder=" " value="<?= htmlspecialchars($updateStudent['address']) ?>">
                <label for="upd_address" class="label">Address</label>
            </div>

            <div class="google-input">
                <input type="text" name="contact_number" id="upd_contact" class="field" placeholder=" " value="<?= htmlspecialchars($updateStudent['contact_number']) ?>">
                <label for="upd_contact" class="label">Mobile Number</label>
            </div>

            <div id="upd_btncontainer" style="display:flex; gap:20px; margin-top:10px;">
                <button type="submit" class="btns">Update Record</button>
            </div>
        </form>
        <?php endif; ?>

        <div id="update-success-toast" class="toast-hidden">
            Record Updated Successfully!
        </div>
        <div id="update-error-toast" class="toast-hidden" style="background-color:#e74c3c;">
            Update Failed. Please try again.
        </div>
    </section>

   
    <section id="delete" class="content">
        <h1 class="contenttitle">Delete Student Record</h1>

        <form action="includes/delete.php" method="POST">
            <div class="google-input">
                <input type="number" name="id" id="delete_id" class="field" placeholder=" " required min="1">
                <label for="delete_id" class="label">Student ID</label>
            </div>

            <p class="label" style="color:#e74c3c; width:auto; font-size:0.9em;">
            </p>

            <div style="display:flex; gap:20px; margin-top:10px;">
                <button type="submit" class="btns" id="delbtn"
                        onclick="return confirm('Are you sure you want to delete this student record?')">
                    Delete Record
                </button>
            </div>
        </form>

        <div id="delete-success-toast" class="toast-hidden" style="background-color:#e74c3c;">
            Record Deleted Successfully!
        </div>
        <div id="delete-error-toast" class="toast-hidden" style="background-color:#c0392b;">
            Deletion Failed. Please try again.
        </div>
    </section>

    <script src="script.js"></script>
</body>
</html>
