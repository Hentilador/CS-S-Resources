<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Files</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .file-list { list-style: none; padding: 0; }
        .file-item { padding: 0.5rem; border-bottom: 1px solid #eee; }
        .folder-label { 
            display: inline-block;
            background: #f0f0f0;
            padding: 2px 8px;
            border-radius: 4px;
            margin-right: 1rem;
            font-size: 0.9em;
        }
        a { color: #2c3e50; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Project File Structure</h1>
    <ul class="file-list">
        <?php
        function scanRecursively($dir, $root) {
            $files = [];
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    $dir, 
                    FilesystemIterator::SKIP_DOTS | FilesystemIterator::UNIX_PATHS
                ),
                RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iterator as $file) {
                $relativePath = substr($file->getPathname(), strlen($root) + 1);
                
                // Skip hidden directories and their contents
                if ($file->isDir() && strpos($file->getFilename(), '.') === 0) {
                    $iterator->next();
                    continue;
                }
                
                // Skip root files and hidden files
                if ($file->isFile() && 
                    strpos($relativePath, '/') !== false && 
                    strpos(basename($relativePath), '.') !== 0
                ) {
                    $folder = dirname($relativePath);
                    $files[] = [
                        'folder' => $folder,
                        'name' => $file->getFilename(),
                        'path' => $relativePath
                    ];
                }
            }
            return $files;
        }

        $root = __DIR__;
        $allFiles = scanRecursively($root, $root);
        usort($allFiles, function($a, $b) {
            return strcmp($a['folder'].$a['name'], $b['folder'].$b['name']);
        });

        foreach ($allFiles as $file) {
            echo '<li class="file-item">';
            echo '<span class="folder-label">'.htmlspecialchars($file['folder']).'</span>';
            echo '<a href="'.htmlspecialchars($file['path']).'">';
            echo htmlspecialchars($file['name']);
            echo '</a></li>';
        }
        ?>
    </ul>
</body>
</html>