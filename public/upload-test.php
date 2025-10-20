<?php
// Simple upload diagnostic page
echo "<h2>Upload Configuration Check</h2>";

echo "<h3>PHP Configuration:</h3>";
echo "<ul>";
echo "<li>upload_max_filesize: " . ini_get('upload_max_filesize') . "</li>";
echo "<li>post_max_size: " . ini_get('post_max_size') . "</li>";
echo "<li>max_file_uploads: " . ini_get('max_file_uploads') . "</li>";
echo "<li>memory_limit: " . ini_get('memory_limit') . "</li>";
echo "<li>max_execution_time: " . ini_get('max_execution_time') . "</li>";
echo "<li>max_input_time: " . ini_get('max_input_time') . "</li>";
echo "</ul>";

echo "<h3>Laravel Storage:</h3>";
echo "<ul>";
echo "<li>Storage public path: " . storage_path('app/public') . "</li>";
echo "<li>Public storage link: " . (file_exists(public_path('storage')) ? 'EXISTS' : 'MISSING') . "</li>";
echo "</ul>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])) {
    echo "<h3>Upload Test Result:</h3>";
    if ($_FILES['test_file']['error'] === UPLOAD_ERR_OK) {
        $fileSize = round($_FILES['test_file']['size'] / 1024 / 1024, 2);
        echo "<div style='color: green;'>✅ Upload successful! File size: {$fileSize} MB</div>";
    } else {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'Upload stopped by extension',
        ];
        echo "<div style='color: red;'>❌ Upload failed: " . ($errors[$_FILES['test_file']['error']] ?? 'Unknown error') . "</div>";
    }
}
?>

<h3>Test Upload:</h3>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="test_file" accept="image/*,.pdf" required>
    <button type="submit">Test Upload</button>
</form>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
ul { background: #f5f5f5; padding: 15px; border-radius: 5px; }
</style>