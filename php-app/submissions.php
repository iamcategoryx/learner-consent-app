<?php
require_once __DIR__ . '/includes/db.php';

$error = null;
$totalRows = 0;
$rows = [];

$allowedPerPage = [25, 50, 100];
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 25;
if (!in_array($perPage, $allowedPerPage)) {
    $perPage = 25;
}

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

try {
    $db = getDb();

    $countStmt = $db->query('SELECT COUNT(*) FROM consent_submissions');
    $totalRows = (int)$countStmt->fetchColumn();

    $totalPages = max(1, (int)ceil($totalRows / $perPage));
    if ($page > $totalPages) {
        $page = $totalPages;
    }
    $offset = ($page - 1) * $perPage;

    $stmt = $db->prepare(
        'SELECT id, first_name, last_name, email, mobile, sentinel_number, consent, submitted_at
         FROM consent_submissions
         ORDER BY submitted_at DESC
         LIMIT :limit OFFSET :offset'
    );
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'Failed to load submissions. Please try again.';
    $totalPages = 1;
}

function paginationUrl($p, $pp) {
    return 'submissions.php?page=' . $p . '&per_page=' . $pp;
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
                            <?= $totalRows ?> submission<?= $totalRows !== 1 ? 's' : '' ?> found
                        </div>
                        <div class="toolbar-actions">
                            <div class="per-page-select" data-testid="select-per-page">
                                <label for="perPage">Show:</label>
                                <select id="perPage" onchange="window.location.href='submissions.php?page=1&per_page='+this.value">
                                    <?php foreach ($allowedPerPage as $opt): ?>
                                        <option value="<?= $opt ?>" <?= $perPage === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php if ($totalRows > 0): ?>
                                <a href="api/export.php" class="btn-export" data-testid="button-export-csv">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                    Export to CSV
                                </a>
                            <?php endif; ?>
                        </div>
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

                        <?php if ($totalPages > 1): ?>
                            <div class="pagination" data-testid="pagination">
                                <div class="pagination-info">
                                    Showing <?= $offset + 1 ?>–<?= min($offset + $perPage, $totalRows) ?> of <?= $totalRows ?>
                                </div>
                                <div class="pagination-controls">
                                    <?php if ($page > 1): ?>
                                        <a href="<?= paginationUrl($page - 1, $perPage) ?>" class="page-btn" data-testid="button-prev-page">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                            Previous
                                        </a>
                                    <?php else: ?>
                                        <span class="page-btn disabled">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                            Previous
                                        </span>
                                    <?php endif; ?>

                                    <?php
                                    $maxVisible = 7;
                                    $start = max(1, $page - (int)floor($maxVisible / 2));
                                    $end = min($totalPages, $start + $maxVisible - 1);
                                    if ($end - $start + 1 < $maxVisible) {
                                        $start = max(1, $end - $maxVisible + 1);
                                    }

                                    if ($start > 1): ?>
                                        <a href="<?= paginationUrl(1, $perPage) ?>" class="page-num" data-testid="page-1">1</a>
                                        <?php if ($start > 2): ?>
                                            <span class="page-ellipsis">&hellip;</span>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php for ($p = $start; $p <= $end; $p++): ?>
                                        <?php if ($p === $page): ?>
                                            <span class="page-num active" data-testid="page-<?= $p ?>"><?= $p ?></span>
                                        <?php else: ?>
                                            <a href="<?= paginationUrl($p, $perPage) ?>" class="page-num" data-testid="page-<?= $p ?>"><?= $p ?></a>
                                        <?php endif; ?>
                                    <?php endfor; ?>

                                    <?php if ($end < $totalPages): ?>
                                        <?php if ($end < $totalPages - 1): ?>
                                            <span class="page-ellipsis">&hellip;</span>
                                        <?php endif; ?>
                                        <a href="<?= paginationUrl($totalPages, $perPage) ?>" class="page-num" data-testid="page-<?= $totalPages ?>"><?= $totalPages ?></a>
                                    <?php endif; ?>

                                    <?php if ($page < $totalPages): ?>
                                        <a href="<?= paginationUrl($page + 1, $perPage) ?>" class="page-btn" data-testid="button-next-page">
                                            Next
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                                        </a>
                                    <?php else: ?>
                                        <span class="page-btn disabled">
                                            Next
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
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
