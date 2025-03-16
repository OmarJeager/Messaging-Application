<?php
session_start();

if (!isset($_SESSION['username'])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Chat App - Sign Up</title>
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
	<!-- Font Awesome Icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<!-- Custom CSS -->
	<style>
		/* Dark Mode Styles */
		body.dark-mode {
			background-color: #121212;
			color: #ffffff;
		}

		body.dark-mode .form-control {
			background-color: #333;
			color: #fff;
			border-color: #444;
		}

		body.dark-mode .form-control:focus {
			background-color: #444;
			color: #fff;
			border-color: #555;
			box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.25);
		}

		body.dark-mode .btn-primary {
			background-color: #0d6efd;
			border-color: #0d6efd;
		}

		body.dark-mode .btn-primary:hover {
			background-color: #0b5ed7;
			border-color: #0a58ca;
		}

		body.dark-mode .alert {
			background-color: #333;
			color: #fff;
			border-color: #444;
		}

		/* Input and Button Animations */
		.form-control {
			transition: all 0.3s ease;
		}

		.form-control:focus {
			transform: scale(1.02);
			box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
		}

		.btn-primary {
			transition: all 0.3s ease;
		}

		.btn-primary:hover {
			transform: scale(1.05);
			box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
		}

		/* Theme Toggle Button */
		.theme-toggle {
			position: fixed;
			bottom: 20px;
			right: 20px;
			border-radius: 50%;
			width: 50px;
			height: 50px;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		/* Icons */
		.input-group-text {
			background-color: #e9ecef;
			border-color: #ced4da;
		}
	</style>
	<!-- Favicon -->
	<link rel="icon" href="img/logo.png">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
	<div class="w-400 p-5 shadow rounded">
		<form method="post" action="app/http/signup.php" enctype="multipart/form-data">
			<div class="d-flex justify-content-center align-items-center flex-column">
				<img src="img/logo.png" class="w-25">
				<h3 class="display-4 fs-1 text-center">Sign Up</h3>
			</div>
			<!-- Error Message -->
			<?php if (isset($_GET['error'])) { ?>
			<div class="alert alert-warning" role="alert">
				<?php echo htmlspecialchars($_GET['error']); ?>
			</div>
			<?php } 
              
              if (isset($_GET['name'])) {
              	$name = $_GET['name'];
              } else $name = '';

              if (isset($_GET['username'])) {
              	$username = $_GET['username'];
              } else $username = '';
			?>
			<!-- Name Input -->
			<div class="mb-3">
				<label class="form-label">Name</label>
				<div class="input-group">
					<span class="input-group-text"><i class="fas fa-user"></i></span>
					<input type="text" name="name" value="<?=$name?>" class="form-control" required>
				</div>
			</div>
			<!-- Username Input -->
			<div class="mb-3">
				<label class="form-label">Username</label>
				<div class="input-group">
					<span class="input-group-text"><i class="fas fa-at"></i></span>
					<input type="text" class="form-control" value="<?=$username?>" name="username" required>
				</div>
			</div>
			<!-- Password Input -->
			<div class="mb-3">
				<label class="form-label">Password</label>
				<div class="input-group">
					<span class="input-group-text"><i class="fas fa-lock"></i></span>
					<input type="password" class="form-control" name="password" required>
				</div>
			</div>
			<!-- Profile Picture Input -->
			<div class="mb-3">
				<label class="form-label">Profile Picture</label>
				<div class="input-group">
					<span class="input-group-text"><i class="fas fa-image"></i></span>
					<input type="file" class="form-control" name="pp">
				</div>
			</div>
			<!-- Sign Up Button -->
			<button type="submit" class="btn btn-primary w-100">Sign Up</button>
			<!-- Login Link -->
			<div class="text-center mt-3">
				<a href="index.php" class="text-decoration-none">Login</a>
			</div>
		</form>
	</div>
	<!-- Theme Toggle Button -->
	<button id="theme-toggle" class="btn btn-secondary theme-toggle">
		<i class="fas fa-moon"></i>
	</button>
	<!-- Custom JavaScript -->
	<script>
		// Dark Mode Toggle
		const themeToggle = document.getElementById('theme-toggle');
		const body = document.body;

		// Check for saved theme in localStorage
		const savedTheme = localStorage.getItem('theme');
		if (savedTheme) {
			body.classList.add(savedTheme);
			updateIcon();
		}

		// Toggle Theme
		themeToggle.addEventListener('click', () => {
			body.classList.toggle('dark-mode');
			localStorage.setItem('theme', body.classList.contains('dark-mode') ? 'dark-mode' : '');
			updateIcon();
		});

		// Update Theme Icon
		function updateIcon() {
			const icon = themeToggle.querySelector('i');
			if (body.classList.contains('dark-mode')) {
				icon.classList.remove('fa-moon');
				icon.classList.add('fa-sun');
			} else {
				icon.classList.remove('fa-sun');
				icon.classList.add('fa-moon');
			}
		}
	</script>
</body>
</html>
<?php
} else {
	header("Location: home.php");
	exit;
}
?>
