<!DOCTYPE html>
<html>
<head>
	<title>Register Animal</title>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
  <div class="container mt-5">
    <div class="row">
			<div class="col-md-6 mx-auto">
				<h1 class="text-center mb-4">Register Animal</h1>
				<form action="animal_submit.php" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label for="animal-name">Name</label>
						<input type="text" class="form-control" id="animal-name" name="animal-name" required>
					</div>
					<div class="form-group">
						<label for="animal-age">Age</label>
						<input type="number" class="form-control" id="animal-age" name="animal-age" required>
					</div>
					<div class="form-group">
						<label for="animal-description">Description</label>
						<textarea class="form-control" id="animal-description" name="animal-description" rows="3" required></textarea>
					</div>
					<div class="form-group">
						<label for="animal-image">Image</label>
						<input type="file" class="form-control-file" id="animal-image" name="animal-image" required>
					</div>
					<div class="form-group">
						<label for="donation-amount">Donation Amount</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">$</span>
							</div>
							<input type="number" class="form-control" id="donation-amount" name="donation-amount" min="0" step="0.01" required>
						</div>
					</div>
					<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>

	<!-- Bootstrap JS -->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
