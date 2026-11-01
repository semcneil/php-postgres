<?php
// 1. Load the credentials securely using parse_ini_file
// $env_path = '/etc/iot-creds/vars.sh';  // if not using Hestia
$env_path = '../private/var.env';  // if using Hestia user space

if (!file_exists($env_path)) {
    die("Configuration file missing.");
}

$config = parse_ini_file($env_path);

$db_host = $config['DBHOST']; // Change if your DB is on a different server
$db_name = $config['DBNAME']; // Replace with your actual database name
$db_user = $config['DBUSER'] ?? null;
$db_pass = $config['DBPASS'] ?? null;

if (!$db_user || !$db_pass) {
    die("Database credentials not configured correctly.");
}

try {
    // 2. Connect to the database
    $dsn = "pgsql:host=$db_host;dbname=$db_name";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);

    // 3. Fetch the data from your table (replace 'your_table_name')
    $stmt = $pdo->query('SELECT id, data, created_at FROM testjunk2 ORDER BY created_at DESC');
    $rows = $stmt->fetchAll();

} catch (PDOException $e) {
    // In production, log $e->getMessage() instead of echoing it to prevent information leakage
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Records</title>
    <style>
        table { border-collapse: collapse; width: 100%; max-width: 800px; margin: 20px auto; font-family: sans-serif; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f4f4f4; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Data</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($rows)): ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['data']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" style="text-align: center;">No records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>

