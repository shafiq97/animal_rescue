<?php
$host     = "localhost";
$username = "root";
$password = "";
$dbname   = "animal_rescue";

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

?>

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
						<div class="form-check">
							<input class="form-check-input" type="checkbox" value="" name="isMedical" id="isMedical">
							<label class="form-check-label" for="isMedical">
								Need medical fee
							</label>
						</div>
					</div>
					<div class="form-group">
						<label id="code-category-label" for="animal-code-category">Code Category</label>
						<select name="code_category" class="form-control" id="code_category">
							<option value="">Please select</option>
							<?php
							// Retrieve categories from the database
							$query  = "SELECT * FROM category_donation";
							$result = mysqli_query($conn, $query);
							while ($row = mysqli_fetch_assoc($result)) {
								$selected = '';
								if ($row['id'] == $category_donation[0]['code_category']) {
									$selected = 'selected';
								}
								echo "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>";
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label id="medical_adopt_fee">Adoption Fee</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">$</span>
							</div>
							<input type="number" class="form-control" name="medical_adopt_fee" min="0" step="0.01" required>
						</div>
					</div>
					<div class="form-group">
						<label for="animal-status">Type</label>
						<select name="pet_type" class="form-control" id="pet_type">
							<option value="">Select animal type</option>
							<option value="dog">Dog</option>
							<option value="cat">Cat</option>
							<option value="rabbit">Rabbit</option>
							<option value="hamster">Hamster</option>
							<option value="bird">Bird</option>
						</select>
					</div>
					<div class="form-group">
						<label for="">Health Condition</label>
						<select name="health" class="form-control" id="">
							<option value="">Select health condition</option>
							<option value="good">Good</option>
							<option value="bad">Bad</option>
						</select>
					</div>
					<div class="form-group">
						<label for="animal-breed">Breed</label>
						<select name="breed" class="form-control" id="breed">
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
						<label for="animal-age">Age</label>
						<input type="number" class="form-control" id="animal-age" name="animal-age" required>
					</div>
					<div class="form-group">
						<label for="color">Color</label>
						<select name="color" class="form-control" id="">
							<option value="black">Black</option>
							<option value="grey">Grey</option>
							<option value="orange">Orange</option>
							<option value="mix">Mix color</option>
						</select>
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
						<label for="location" class="">Location</label>
						<div class="">
							<select name="location" class="form-control" id="location-select">
								<option value="johor">Johor</option>
								<option value="kedah">Kedah</option>
								<option value="kelantan">Kelantan</option>
								<option value="melaka">Melaka</option>
								<option value="negeri sembilan">Negeri Sembilan</option>
								<option value="pahang">Pahang</option>
								<option value="perak">Perak</option>
								<option value="perlis">Perlis</option>
								<option value="penang">Penang</option>
								<option value="sabah">Sabah</option>
								<option value="sarawak">Sarawak</option>
								<option value="selangor">Selangor</option>
								<option value="terengganu">Terengganu</option>
								<option value="wilayah persekutuan kuala lumpur">Wilayah Persekutuan Kuala Lumpur</option>
								<option value="wilayah persekutuan labuan">Wilayah Persekutuan Labuan</option>
								<option value="wilayah persekutuan putrajaya">Wilayah Persekutuan Putrajaya</option>
							</select>
						</div>
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
					<a href="dashboard.php" class="btn btn-block btn-warning">Cancel</a>
				</form>
			</div>
		</div>
	</div>

	<!-- Bootstrap JS -->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script>
		const isMedicalCheckbox = document.getElementById('isMedical');
		const medicalFeeLabel = document.getElementById('medical_adopt_fee');

		isMedicalCheckbox.addEventListener('change', function () {
			if (this.checked) {
				medicalFeeLabel.textContent = 'Medical fee';
			} else {
				medicalFeeLabel.textContent = 'Adoption fee';
			}
		});
	</script>
	<script>
		const codeCategoryLabel = document.getElementById('code-category-label');
		const codeCategorySelect = document.getElementById('code_category'); // Corrected the id here

		// Disable the dropdown by default
		codeCategoryLabel.classList.add('disabled');
		codeCategorySelect.disabled = true;

		isMedicalCheckbox.addEventListener('change', function () {
			if (isMedicalCheckbox.checked) {
				codeCategoryLabel.classList.remove('disabled');
				codeCategorySelect.disabled = false;
			} else {
				codeCategoryLabel.classList.add('disabled');
				codeCategorySelect.disabled = true;
			}
		});
	</script>
	<script>
		const petTypeSelect = document.getElementById('pet_type');
		const breedSelect = document.getElementById('breed');

		petTypeSelect.addEventListener('change', function () {
			breedSelect.innerHTML = ''; // clear all options

			if (this.value === 'dog') {
				addOption('Mixed Breed', 'Mixed Breed');
				addOption('Affenpinscher', 'Affenpinscher');
				addOption('Afghan Hound', 'Afghan Hound');
				addOption('Airedale Terrier', 'Airedale Terrier');
				addOption('Akbash', 'Akbash');
				addOption('Akita', 'Akita');
				addOption('Alaskan Malamute', 'Alaskan Malamute');
				addOption('American Bulldog', 'American Bulldog');
				addOption('American Eskimo Dog', 'American Eskimo Dog');
				addOption('American Hairless Terrier', 'American Hairless Terrier');
				addOption('American Staffordshire Terrier', 'American Staffordshire Terrier');
				addOption('American Water Spaniel', 'American Water Spaniel');
				addOption('Anatolian Shepherd', 'Anatolian Shepherd');
				addOption('Appenzell Mountain Dog', 'Appenzell Mountain Dog');
				addOption('Australian Cattle Dog/Blue Heeler', 'Australian Cattle Dog/Blue Heeler');
				addOption('Australian Kelpie', 'Australian Kelpie');
				addOption('Australian Shepherd', 'Australian Shepherd');
				addOption('Australian Terrier', 'Australian Terrier');
			} else if (this.value === 'cat') {
				addOption('Domestic Short Hair', 'Domestic Short Hair');
				addOption('Domestic Medium Hair', 'Domestic Medium Hair');
				addOption('Domestic Long Hair', 'Domestic Long Hair');
				addOption('Abyssinian', 'Abyssinian');
				addOption('American Curl', 'American Curl');
				addOption('American Shorthair', 'American Shorthair');
				addOption('American Wirehair', 'American Wirehair');
				addOption('Applehead Siamese', 'Applehead Siamese');
				addOption('Balinese', 'Balinese');
				addOption('Bengal', 'Bengal');
				addOption('Birman', 'Birman');
				addOption('Bobtail', 'Bobtail');
				addOption('Bombay', 'Bombay');
				addOption('British Shorthair', 'British Shorthair');
				addOption('Burmese', 'Burmese');
				addOption('Burmilla', 'Burmilla');
				addOption('Calico', 'Calico');
				addOption('Canadian Hairless', 'Canadian Hairless');
				addOption('Chartreux', 'Chartreux');
			}
		});

		function addOption(value, text) {
			const option = document.createElement('option');
			option.value = value;
			option.textContent = text;
			breedSelect.appendChild(option);
		}
	</script>

</body>
</html>