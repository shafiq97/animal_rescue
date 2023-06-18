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
							<option value="small-and-furry">Small and Furry</option>
							<option value="reptiles">Reptiles</option>
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
						<label for="animal-age">Age (month/year)</label>
						<input type="text" class="form-control" id="animal-age" name="animal-age" required>
						<small>Month/Year</small>
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
						<textarea class="form-control" id="animal-description" name="animal-description" rows="3" required></textarea>
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
							<h6 class="form-check-label" for="terms">I agree to Safe Paws <a target="_blank" href="../terms_and_condition.php">terms and conditions</a></h6>
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

		isMedicalCheckbox.addEventListener('change', function() {
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

		isMedicalCheckbox.addEventListener('change', function() {
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

		petTypeSelect.addEventListener('change', function() {
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
			} else if (this.value === 'rabbit') {
				addOption('', 'Select Rabbit breed');
				addOption('American', 'American');
				addOption('American Fuzzy Lop', 'American Fuzzy Lop');
				addOption('American Sable', 'American Sable');
				addOption('Angora Rabbit', 'Angora Rabbit');
				addOption('Belgian Hare', 'Belgian Hare');
				addOption('Beveren', 'Beveren');
				addOption('Britannia Petite', 'Britannia Petite');
				addOption('Bunny Rabbit', 'Bunny Rabbit');
				addOption('Californian', 'Californian');
				addOption('Champagne DArgent', 'Champagne DArgent');
				addOption('Checkered Giant', 'Checkered Giant');
				addOption('Chinchilla', 'Chinchilla');
				addOption('Cinnamon', 'Cinnamon');
				addOption('Dutch', 'Dutch');
				addOption('Dwarf', 'Dwarf');
				addOption('New Zealand', 'New Zealand');
				addOption('Mini Rex', 'Mini Rex');
				addOption('Rex', 'Rex');
				addOption('Rhinelander', 'Rhinelander');
			} else if (this.value === 'hamster') {
				addOption('', 'Select hamster breed');
				addOption('Chinese Hamster', 'Chinese Hamster');
				addOption('Eversmanns Hamster', 'Eversmanns Hamster');
				addOption('Long-Tailed Hamster', 'Long-Tailed Hamster');
				addOption('Migratory Hamster', 'Migratory Hamster');
				addOption('Mouse-Like Hamster', 'Mouse-Like Hamster');
				addOption('Rat Hamster', 'Rat Hamster');
				addOption('Roborovskys Hamster', 'Roborovskys Hamster');
				addOption('Rummanian Hamster', 'Rummanian Hamster');
				addOption('Short Dwarf Hamster', 'Short Dwarf Hamster');
				addOption('Striped Hairy Foot Russian Hamster', 'Striped Hairy Foot Russian Hamster');
				addOption('Striped Hamster', 'Striped Hamster');
				addOption('Syrian / Golden Hamster', 'Syrian / Golden Hamster');
				addOption('Tibetan Hamsterham', 'Tibetan Hamsterham');

			} else if (this.value === 'fish') {
				addOption('', 'Select fish breed');
				addOption('Arowanas', 'Arowanas');
				addOption('Botia', 'Botia');
				addOption('Catfish', 'Catfish');
				addOption('Characins', 'Characins');
				addOption('Cichlids', 'Cichlids');
				addOption('Cyprinds', 'Cyprinds');
				addOption('Goldfish', 'Goldfish');
				addOption('Killifish', 'Killifish');
				addOption('Koi', 'Koi');
				addOption('Labyrinth Fish', 'Labyrinth Fish');
				addOption('Livebearers', 'Livebearers');
				addOption('Loaches', 'Loaches');
				addOption('Perches', 'Perches');
				addOption('Rainbowfish', 'Rainbowfish');

			} else if (this.value === 'reptiles') {
				addOption('', 'Select reptile type');
				addOption('Frog', 'Frog');
				addOption('Gecko', 'Gecko');
				addOption('Hermit Crab', 'Hermit Crab');
				addOption('Iguana', 'Iguana');
				addOption('Lizard', 'Lizard');
				addOption('Snake', 'Snake');
				addOption('Tortoise', 'Tortoise');
				addOption('Turtle', 'Turtle');
			} else if (this.value === 'small-and-furry') {
				addOption('', 'Select small and furry animal');
				addOption('Chinchilla', 'Chinchilla');
				addOption('Degu', 'Degu');
				addOption('Ferret', 'Ferret');
				addOption('Gerbil', 'Gerbil');
				addOption('Guinea Pig', 'Guinea Pig');
				addOption('Hamster', 'Hamster');
				addOption('Hedgehog', 'Hedgehog');
				addOption('Mouse', 'Mouse');
				addOption('Prairie Dog', 'Prairie Dog');
				addOption('Racoon', 'Racoon');
				addOption('Rat', 'Rat');
				addOption('Skunk', 'Skunk');
				addOption('Sugar Glider', 'Sugar Glider');
				addOption('Tarantula', 'Tarantula');
			} else if (this.value === 'bird') {
				addOption('', 'Select bird species');
				addOption('African Grey', 'African Grey');
				addOption('Amazon', 'Amazon');
				addOption('Brotogeris', 'Brotogeris');
				addOption('Budgie/Budgerigar', 'Budgie/Budgerigar');
				addOption('Button Quail', 'Button Quail');
				addOption('Caique', 'Caique');
				addOption('Canary', 'Canary');
				addOption('Chicken', 'Chicken');
				addOption('Cockatiel', 'Cockatiel');
				addOption('Cockatoo', 'Cockatoo');
				addOption('Conure', 'Conure');
				addOption('Dove', 'Dove');
				addOption('Duck', 'Duck');
				addOption('Eclectus', 'Eclectus');
				addOption('Emu', 'Emu');
				addOption('Finch', 'Finch');
				addOption('Goose', 'Goose');
				addOption('Guinea Fowl', 'Guinea Fowl');
				addOption('Kakariki', 'Kakariki');
				addOption('Lory/Lorikeet', 'Lory/Lorikeet');
				addOption('Lovebird', 'Lovebird');
				addOption('Macaw', 'Macaw');
				addOption('Ostrich', 'Ostrich');
				addOption('Parakeet', 'Parakeet');
				addOption('Parrot', 'Parrot');
				addOption('Parrotlet', 'Parrotlet');
				addOption('Peacock/Pea Fowl', 'Peacock/Pea Fowl');
				addOption('Pheasant', 'Pheasant');
				addOption('Pigeon', 'Pigeon');
				addOption('Pionus', 'Pionus');
				addOption('Poicephalus/Senegal', 'Poicephalus/Senegal');
				addOption('Quail', 'Quail');
				addOption('Quaker Parakeet', 'Quaker Parakeet');
				addOption('Rhea', 'Rhea');
				addOption('Ringneck/Psittacula', 'Ringneck/Psittacula');
				addOption('Rosella', 'Rosella');
				addOption('Softbill (Other)', 'Softbill (Other)');
				addOption('Swan', 'Swan');
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