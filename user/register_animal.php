<!DOCTYPE html>
<html>
<head>
	<title>Register Animal</title>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
	<div class="container mt-5 mb-5">
		<div class="row">
			<div class="col-md-6 mx-auto">
				<h1 class="text-center mb-4">Register Animal</h1>
				<form action="animal_submit.php" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label for="animal-name">Pet Name</label>
						<input type="text" class="form-control" id="animal-name" name="animal-name" required>
					</div>
					<div class="form-group">
						<label for="animal-status">Pet Status</label>
						<select name="pet_status" class="form-control" name="" id="">
							<option value="adoption">For Adoption</option>
							<option value="lost">Lost</option>
							<option value="found">Found</option>
							<option value="owners_pet">Owner's pet</option>
						</select>
					</div>
					<div class="form-group">
						<label for="animal-status">Type</label>
						<select name="pet_type" class="form-control" id="">
							<option value="dog">Dog</option>
							<option value="cat">Cat</option>
							<option value="rabbit">Rabbit</option>
							<option value="hamster">Hamster</option>
							<option value="bird">Bird</option>
						</select>
					</div>
					<div class="form-group">
						<label for="animal-gender">Gender</label>
						<select name="pet_gender" class="form-control" id="">
							<option value="male">Male</option>
							<option value="female">Female</option>
							<option value="mixed">Mixed</option>
						</select>
					</div>
					<div class="form-group">
						<label for="animal-breed">Breed</label>
						<select name="breed" class="form-control" id="">
							<option value="V">V</option>
						</select>
					</div>
					<div class="form-group">
						<label for="animal-age">Age</label>
						<input type="number" class="form-control" id="animal-age" name="animal-age" required>
					</div>
					<div class="form-group">
						<label for="color">Color</label>
						<input type="color" class="form-control" id="color" name="color" required>
					</div>
					<div class="form-group">
						<label for="maturing_size">Size at maturing</label>
						<select name="maturing_size" class="form-control" name="maturing_size" id="">
							<option value="small">Small</option>
							<option value="medium">Medium</option>
							<option value="large">Large</option>
							<option value="extra_large">extra large</option>
						</select>
					</div>
					<div class="form-group">
						<label for="vaccinated">vaccinated</label>
						<select name="vaccinated" class="form-control" name="vaccinated" id="">
							<option value="yes">yes</option>
							<option value="no">no</option>
							<option value="not_sure">not sure</option>
						</select>
					</div>
					<div class="form-group">
						<label for="code_category">code category for apply donation</label>
						<select name="code_category" class="form-control" name="code_category" id="">
							<option value="1">1</option>
							<option value="2">2</option>
						</select>
					</div>
					<div class="form-group">
						<label for="animal-description">Description</label>
						<textarea class="form-control" id="animal-description" name="animal-description" rows="3"
							required></textarea>
					</div>
					<div class="form-group">
						<label for="animal-image">Image</label>
						<input type="file" class="form-control-file" id="animal-image" name="animal-image" required>
					</div>
					<div class="form-group">
						<label for="medical_adopt_fee">Medical or Adoption Fee</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">$</span>
							</div>
							<input type="number" class="form-control" id="medical_adopt_fee" name="medical_adopt_fee" min="0"
								step="0.01" required>
						</div>
					</div>
					<div class="form-group">
						<label for="role">My Role</label>
						<select name="role" class="form-control" name="role" id="">
							<option value="rescuer">rescuer</option>
							<option value="owner">owner</option>
						</select>
					</div>
					<div class="form-group form-check">
						<div class="row">
							<input type="checkbox" class="form-check-input" id="terms" required>
							<h6 class="form-check-label" for="terms">I agree to Safe Paws <a target="_blank"
									href="../terms_and_condition.php">terms and conditions</a></h6>
						</div>
					</div>
					<button type="submit" class="btn btn-block btn-primary">Submit</button>
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