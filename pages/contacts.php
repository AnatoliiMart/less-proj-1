<h1>Contacts</h1>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = clear($_POST['name'] ?? '');
  $email = clear($_POST['email'] ?? '');
  $message = clear($_POST['message'] ?? '');
  if (empty($name) || empty($email) || empty($message)) {
    $_SESSION['message'] = ['All fields are required!', 'danger'];
    $_SESSION["name"] = $name;
    $_SESSION["email"] = $email;
    $_SESSION["text"] = $message;
  } else {
    $message = "Name: $name\nEmail: $email\nMessage: $message";
    mail('anatolii.mart6@gmail.com', 'New message from contacts', $message);
    $_SESSION['message'] = ['Message sent!', 'success'];
  }
  redirect('/contacts');
}
?>
<?php
if (isset($_SESSION['message'])) {
  list($message, $type) = $_SESSION['message'];
  echo '<div class="alert alert-' . $type . '">' . $message . '</div>';
  unset($_SESSION['message']);
}
?>
<form action="/contacts" method="post">
  <div class="mb-3">
    <label for="form-label">Name:</label>
    <input type="text" name="name" class="form-control" value="<?php echo isset($_SESSION["name"]) ? $_SESSION["name"] : '';
                                                                unset($_SESSION["name"]) ?>">
  </div>
  <div class="mb-3">
    <label for="form-label">Email:</label>
    <input type="email" name="email" class="form-control" value="<?php echo isset($_SESSION["email"]) ? $_SESSION["email"] : '';
                                                                  unset($_SESSION["email"])  ?>">
  </div>
  <div class="mb-3">
    <label for="form-label">Message:</label>
    <textarea class="form-control" name="message"><?php echo isset($_SESSION["text"]) ? $_SESSION["text"] : '';
                                                  unset($_SESSION["text"]) ?></textarea>
  </div>
  <button class="btn btn-primary mt-3">Send</button>
</form>