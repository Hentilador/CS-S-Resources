<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Files</title>
    <style>
        /* ... keep existing styles ... */
    </style>
</head>
<body>
    <h1>Project File Structure</h1>
    <ul class="file-list">
        <?php
        class DotDirFilter extends RecursiveFilterIterator {
            public function accept() {
                $file = $this->current();
                // Exclude directories starting with . and their contents
                return !($file->isDir() && strpos($file->getFilename(), '.') === 0);
            }
        }

        function scanRecursively($dir, $root) {
            $files = [];
            $iterator = new RecursiveIteratorIterator(
                new DotDirFilter(
                    new RecursiveDirectoryIterator(
                        $dir, 
                        FilesystemIterator::SKIP_DOTS | FilesystemIterator::UNIX_PATHS
                    )
                ),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($iterator as $file) {
                $relativePath = ltrim(substr($file->getPathname(), strlen($root)), '/');
                
                // Skip root files and hidden files
                if (strpos($relativePath, '/') === false || 
                    strpos(basename($relativePath), '.') === 0) {
                    continue;
                }

                $files[] = [
                    'folder' => dirname($relativePath),
                    'name' => $file->getFilename(),
                    'path' => $relativePath
                ];
            }
            return $files;
        }

        $root = rtrim(__DIR__, '/');
        $allFiles = scanRecursively($root, $root);
        usort($allFiles, function($a, $b) {
            return strcmp($a['path'], $b['path']);
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