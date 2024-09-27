<?php
    class LoginController{
        public function index(){
            include("../>view/home/login.php");
        }
    }
    ?>
    <?php
// Bắt đầu session để lưu trữ thông tin đăng nhập
session_start();

// Bao gồm tệp kết nối cơ sở dữ liệu và các hàm hỗ trợ
require '../includes/database-connection.php';
require '../includes/functions.php';

// Kiểm tra xem form đăng nhập có được submit hay không
if (isset($_POST['login'])) {
    // Lấy username và password từ form
    $username = $_POST['txtUser'];
    $password = $_POST['txtPassword'];

    // Kiểm tra nếu cả hai trường đều được điền
    if (!empty($username) && !empty($password)) {
        // Truy vấn cơ sở dữ liệu để kiểm tra xem username và password có đúng không
        $sql = "SELECT * FROM `user` WHERE username = :username AND password = :password";
        
        // Sử dụng prepared statement để ngăn ngừa SQL injection
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username, 'password' => $password]);
        
        // Kiểm tra kết quả
        $user = $stmt->fetch();

        if ($user) {
            // Nếu thông tin đúng, lưu username vào session và chuyển hướng tới trang chính
            $_SESSION['username'] = $username;
            header('Location: ../index.php');
        } else {
            // Nếu không tìm thấy user, chuyển hướng tới trang đăng nhập và báo lỗi
            header("Location: ../login.php?error=Invalid username or password");
        }
    } else {
        // Nếu chưa điền đủ thông tin, chuyển hướng lại và báo lỗi
        header("Location: ../login.php?error=Please fill in both fields");
    }
} else {
    // Nếu không có dữ liệu POST (tức là chưa submit form), hiển thị trang đăng nhập
    include('../view/home/login.php');
}
