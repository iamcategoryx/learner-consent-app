<?php
require_once __DIR__ . '/includes/db.php';

$error = null;
$rows  = [];

try {
    $db   = getDb();
    $stmt = $db->query(
        'SELECT id, first_name, last_name, email, mobile, sentinel_number, consent, submitted_at
         FROM consent_submissions
         ORDER BY submitted_at DESC'
    );
    $rows = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'Failed to load submissions. Please try again.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consent Submissions | Absolute Training & Assessing Ltd</title>
    <meta name="description" content="View all learner consent submissions for Absolute Training & Assessing Ltd.">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container-wide">
        <a href="index.php" class="nav-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Back to Consent Form
        </a>

        <div class="card">
            <div class="card-header">
                <h1>Consent Submissions</h1>
                <p class="subtitle">All learner consent records</p>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert-error">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span><?= htmlspecialchars($error) ?></span>
                    </div>
                <?php else: ?>
                    <div class="submissions-toolbar">
                        <div class="count-badge">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <?= count($rows) ?> submission<?= count($rows) !== 1 ? 's' : '' ?> found
                        </div>
                        <?php if (count($rows) > 0): ?>
                            <a href="api/export.php" class="btn-export" data-testid="button-export-csv">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                Export to CSV
                            </a>
                        <?php endif; ?>
                    </div>

                    <?php if (count($rows) > 0): ?>
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Sentinel Number</th>
                                        <th>Consent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rows as $i => $row): ?>
                                        <?php $date = new DateTime($row['submitted_at']); ?>
                                        <tr data-testid="row-<?= $i ?>">
                                            <td data-testid="cell-<?= $i ?>-0"><?= $date->format('d/m/Y') ?></td>
                                            <td data-testid="cell-<?= $i ?>-1"><?= htmlspecialchars($row['first_name']) ?></td>
                                            <td data-testid="cell-<?= $i ?>-2"><?= htmlspecialchars($row['last_name']) ?></td>
                                            <td data-testid="cell-<?= $i ?>-3"><?= htmlspecialchars($row['email']) ?></td>
                                            <td data-testid="cell-<?= $i ?>-4"><?= htmlspecialchars($row['mobile']) ?></td>
                                            <td data-testid="cell-<?= $i ?>-5"><?= htmlspecialchars($row['sentinel_number']) ?></td>
                                            <td data-testid="cell-<?= $i ?>-6" class="<?= $row['consent'] ? 'consent-yes' : 'consent-no' ?>">
                                                <?= $row['consent'] ? 'TRUE' : 'FALSE' ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="empty-state">No submissions yet.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="footer">
            <p>&copy; 2025 Absolute Training &amp; Assessing Ltd. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
