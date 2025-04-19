<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Directory</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .file-list { list-style: none; padding: 0; }
        .file-item { padding: 0.5rem; border-bottom: 1px solid #eee; }
        .file-item:hover { background-color: #f8f9fa; }
        .folder { color: #2c3e50; font-weight: bold; }
        .file { color: #7f8c8d; }
        a { color: inherit; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .icon { margin-right: 0.5rem; }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <h1>Directory Listing</h1>
    <ul class="file-list">
        <?php
        // Get current directory contents
        $files = scandir(__DIR__);
        $folders = [];
        $normalFiles = [];

        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || $file === basename(__FILE__)) continue;
            
            if (is_dir($file)) {
                $folders[] = $file;
            } else {
                $normalFiles[] = $file;
            }
        }

        // Sort arrays
        sort($folders);
        sort($normalFiles);

        // Display folders first
        foreach ($folders as $item) {
            echo '<li class="file-item folder">';
            echo '<i class="icon fas fa-folder"></i>';
            echo htmlspecialchars($item);
            echo '</li>';
        }

        // Display files
        foreach ($normalFiles as $item) {
            echo '<li class="file-item file">';
            echo '<i class="icon fas fa-file"></i>';
            echo '<a href="' . htmlspecialchars($item) . '">' . htmlspecialchars($item) . '</a>';
            echo '</li>';
        }
        ?>
    </ul>
</body>
</html>