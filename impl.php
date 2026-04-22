<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Capture All Fields
    $data = [
        'Date' => date('Y-m-d H:i:s'),
        'First Name' => $_POST['fname'],
        'Middle Name' => $_POST['mname'],
        'Last Name' => $_POST['lname'],
        'Father' => $_POST['father_name'],
        'Mother' => $_POST['mother_name'],
        'Mobile' => $_POST['mobile'],
        'Opt Mobile' => $_POST['opt_mobile'],
        'Email' => $_POST['email'],
        'Temp Addr' => str_replace(["\r", "\n"], " ", $_POST['temp_addr']),
        'Perm Addr' => str_replace(["\r", "\n"], " ", $_POST['perm_addr']),
        'Class 10' => $_POST['edu_10'],
        '10+2' => $_POST['edu_12'],
        'Graduation' => $_POST['edu_grad'],
        'Masters' => $_POST['edu_mast'],
        'Skills' => $_POST['skills'],
        'Experience' => $_POST['exp']
    ];

    // 2. Handle File Uploads (Photo & Resume)
    $upload_dir = "uploads/";
    if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

    function upload($key, $dir) {
        if (!isset($_FILES[$key]) || $_FILES[$key]['error'] != 0) return "No File";
        $path = $dir . time() . "_" . basename($_FILES[$key]["name"]);
        return move_uploaded_file($_FILES[$key]["tmp_name"], $path) ? $path : "Error";
    }

    $data['Photo Path'] = upload('photo', $upload_dir);
    $data['Resume Path'] = upload('resume', $upload_dir);

    // 3. Save to Excel-friendly CSV
    $file = 'master_job_list.csv';
    $is_new = !file_exists($file);
    $handle = fopen($file, 'a');

    if ($is_new) {
        fputcsv($handle, array_keys($data)); // Write Headers
    }

    if (fputcsv($handle, array_values($data))) {
        echo "<body style='font-family:sans-serif; text-align:center; padding:50px;'>
                <div style='border:2px solid #2ecc71; display:inline-block; padding:20px; border-radius:10px;'>
                    <h1 style='color:#2ecc71;'>Submission Received</h1>
                    <p>Details for <b>{$data['First Name']}</b> have been added to the master sheet.</p>
                    <a href='index.html'>Submit Another</a>
                </div>
              </body>";
    }

    fclose($handle);
}
?>