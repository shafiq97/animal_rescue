<!DOCTYPE html>
<html>
<head>
	<title>Add Question</title>
	<!-- Add Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
  <div class="container">
    <?php include 'header.php' ?>
		<h1>Add Question to Questionnaire</h1>
		<form method="post" action="add_question.php">
			<div class="form-group">
				<label>Question:</label>
				<input type="text" class="form-control" name="question" required>
			</div>
			<div class="form-group">
				<label>Choice 1:</label>
				<input type="text" class="form-control" name="choice_1" required>
			</div>
			<div class="form-group">
				<label>Choice 2:</label>
				<input type="text" class="form-control" name="choice_2" required>
			</div>
			<div class="form-group">
				<label>Choice 3:</label>
				<input type="text" class="form-control" name="choice_3" required>
			</div>
			<div class="form-group">
				<label>Choice 4:</label>
				<input type="text" class="form-control" name="choice_4" required>
			</div>
			<div class="form-group">
				<label>Answer:</label>
				<input type="text" class="form-control" name="answer" required>
			</div>
			<button type="submit" class="btn btn-primary">Add Question</button>
		</form>
	</div>
	<!-- Add Bootstrap JS -->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNVQ8ew" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
