<?php
// =============================================
// Cấu hình kết nối database
// Chỉnh sửa thông tin phù hợp với hosting
// =============================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'h2221ce896_telec');
define('DB_USER', 'h2221ce896_telec');
define('DB_PASS', 'MXhuunxabUF3aXgPKNHm');
define('DB_CHARSET', 'utf8mb4');

define('SITE_NAME', 'TELEC CMS');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', '../uploads/');

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
            ]);
        } catch (PDOException $e) {
            die('<div style="font-family:sans-serif;padding:40px;background:#fff3cd;border:1px solid #ffc107;border-radius:8px;max-width:600px;margin:50px auto;">
                <h2 style="color:#856404;">⚠️ Lỗi kết nối Database</h2>
                <p>Không thể kết nối tới database. Vui lòng kiểm tra lại thông tin trong <code>config/db.php</code></p>
                <p style="color:#6c757d;font-size:13px;">Chi tiết: ' . htmlspecialchars($e->getMessage()) . '</p>
            </div>');
        }
    }
    return $pdo;
}
