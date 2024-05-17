<h1>Reviews</h1>
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = clear($_POST['name'] ?? '');
  $review = clear($_POST['review'] ?? '');

  $created_at = time();

  // $reviews = file_exists('reviews.txt') ? json_decode(file_get_contents('reviews.txt'), true) : [];
  // $new_review = compact('name', 'review', 'created_at');
  // $reviews[] = $new_review;
  $new_review = compact('name', 'review', 'created_at');
  $file = fopen("reviews.txt", "a+");
  fwrite($file, json_encode($new_review) . "\n");
  fclose($file);
  // if(empty($name) || empty($review)) {
  //   $_SESSION['message'] = ['All fields are required!', 'danger'];
  //   $_SESSION["name"] = $name;
  //   $_SESSION["text"] = $review;
  // } else {
  //   $message = "Name: $name\nReview: $review";
  //   $_SESSION['message'] = ['Message has been sent!', 'success'];
  // }
}
?>
<form action="/reviews" method="post">
  <div class="mt-3">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" class="form-control" required>
  </div>
  <div class="mt-3">
    <label for="review">Rewiew:</label>
    <textarea name="review" id="review" class="form-control" required></textarea>
  </div>
  <button class="btn btn-primary mt-3">Send</button>
</form>
<?php
$reviews = file('reviews.txt');
foreach ($reviews as $item) {
  $rewiew = json_decode($item, true);
  $date = date('d-m-Y H:i', $rewiew['created_at']);
  echo "<div>{$rewiew['name']} - {$rewiew['review']} - {$date}</div>";
}
?>