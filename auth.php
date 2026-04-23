<?php
session_start();
$conn = new mysqli("localhost", "root", "", "user_auth");

// REGISTER
if (isset($_POST['register'])) {
    $user = $_POST['username'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $conn->query("INSERT INTO users(username, password) VALUES('$user', '$pass')");
    echo "Registered successfully<br>";
}

// LOGIN
if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $res = $conn->query("SELECT * FROM users WHERE username='$user'");
    if ($row = $res->fetch_assoc()) {
        if (password_verify($pass, $row['password'])) {
            $_SESSION['user'] = $user;
        } else {
            echo "Wrong password<br>";
        }
    } else {
        echo "User not found<br>";
    }
}

// LOGOUT
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: auth.php");
}
?>

<!-- UI PART -->
<?php if (!isset($_SESSION['user'])) { ?>

<h3>Register</h3>
<form method="post">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <button name="register">Register</button>
</form>

<h3>Login</h3>
<form method="post">
    Username: <input type="text" name="username"><br>
    Password: <input type="password" name="password"><br>
    <button name="login">Login</button>
</form>

<?php } else { ?>

<h2>Welcome <?php echo $_SESSION['user']; ?></h2>
<a href="?logout=true">Logout</a>

<?php } ?>