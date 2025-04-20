<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project File Explorer</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
            color: #333;
            line-height: 1.6;
        }
        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #eee;
            padding-bottom: 0.5rem;
        }
        .file-list {
            list-style: none;
            padding: 0;
            margin-top: 1.5rem;
        }
        .file-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            transition: background-color 0.2s;
        }
        .file-item:hover {
            background-color: #f8f9fa;
        }
        .folder-label {
            display: inline-block;
            background: #e3f2fd;
            color: #1976d2;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            margin-right: 1rem;
            font-size: 0.85em;
            font-weight: 500;
        }
        a {
            color: #2c3e50;
            text-decoration: none;
            flex-grow: 1;
            display: flex;
            align-items: center;
        }
        a:hover {
            color: #1a73e8;
        }
        .file-icon {
            margin-right: 0.75rem;
            color: #5f6368;
            width: 20px;
            text-align: center;
        }
        .file-size {
            color: #70757a;
            font-size: 0.85em;
            margin-left: auto;
            padding-left: 1rem;
        }
    </style>
</head>
<body>
    <h1>📂 Project File Explorer</h1>
    <ul class="file-list">
        <?php
        class DotDirFilter extends RecursiveFilterIterator {
            public function accept() {
                $file = $this->current();
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
                
                if (strpos($relativePath, '/') === false || 
                    strpos(basename($relativePath), '.') === 0) {
                    continue;
                }

                $files[] = [
                    'folder' => dirname($relativePath),
                    'name' => $file->getFilename(),
                    'path' => $relativePath,
                    'size' => $file->getSize()
                ];
            }
            return $files;
        }

        $root = rtrim(__DIR__, '/');
        $allFiles = scanRecursively($root, $root);
        usort($allFiles, function($a, $b) {
            return strcmp($a['path'], $b['path']);
        });

        function formatSize($bytes) {
            if ($bytes < 1024) return $bytes . ' B';
            if ($bytes < 1048576) return round($bytes/1024, 1) . ' KB';
            return round($bytes/1048576, 1) . ' MB';
        }

        foreach ($allFiles as $file) {
            echo '<li class="file-item">';
            echo '<a href=" https://cultstrike.netlify.app/'.htmlspecialchars($file['path']).'">';
            echo '<span class="folder-label">'.htmlspecialchars($file['folder']).'</span>';
            echo '<span class="file-icon">📄</span>';
            echo htmlspecialchars($file['name']);
            echo '<span class="file-size">'.formatSize($file['size']).'</span>';
            echo '</a></li>';
        }
        ?>
    </ul>
</body>
</html>