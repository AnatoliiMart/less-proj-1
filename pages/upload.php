<h1>Uploads</h1>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $file = $_FILES['file'];
  extract($file);
  if ($error == 4) {
    $_SESSION['message'] = ['File not selected!', 'danger'];
    redirect('/upload');
  }
  if ($error !== 0) {
    $_SESSION['message'] = ['Error!!! File is not uploaded', 'danger'];
    redirect('/upload');
  }
  $allowedTypes = ['image/png', 'image/jpeg', 'image/gif', 'image/webp'];
  if (!in_array($type, $allowedTypes)) {
    $_SESSION['message'] = ['Wrong file type! Choose another file!', 'danger'];
    redirect('/upload');
  }
  $dir = 'uploads-img/';
  if (!file_exists($dir)) {
    mkdir($dir);
  }
  $fname = $name;
  if (file_exists($dir . '/' . $name)) {
    $fname = uniqid() . '_' . $name;
  }
  move_uploaded_file($file['tmp_name'], $dir . '/' . $fname);
  resizeImage($dir . '/' . $fname, 100, true, 'uploads-img/small');
  resizeImage($dir . '/' . $fname, 300, false, 'uploads-img/big');
  $_SESSION['message'] = ['File uploaded!', 'success'];
}

function addWatermark($sourceImagePath, $watermarkImagePath, $outputImagePath)
{
  extract(pathinfo($sourceImagePath));
  $functionCreate = 'imagecreatefrom' . ($extension === 'jpg' ? 'jpeg' : $extension);
  $image = $functionCreate($sourceImagePath);
  $watermark = imagecreatefrompng($watermarkImagePath);

  // Получаем размеры загруженного изображения и водяного знака
  $imageWidth = imagesx($image);
  $imageHeight = imagesy($image);
  $watermarkWidth = imagesx($watermark);
  $watermarkHeight = imagesy($watermark);

  // Позиция водяного знака (нижний правый угол)
  $destX = $imageWidth - $watermarkWidth - 10;
  $destY = $imageHeight - $watermarkHeight - 10;

  // Накладываем водяной знак на изображение
  imagecopy($image, $watermark, $destX, $destY, 0, 0, $watermarkWidth, $watermarkHeight);

  $functionSave = 'image' . ($extension === 'jpg' ? 'jpeg' : $extension);
  if (!file_exists($outputImagePath)) {
    mkdir($outputImagePath);
  }
  $functionSave($image, "$outputImagePath/$basename");

  // Освобождаем память
  imagedestroy($image);
  imagedestroy($watermark);
}


function resizeImage(string $path, int $size, bool $crop, string $pathToSave)
{
  extract(pathinfo($path));
  $functionCreate = 'imagecreatefrom' . ($extension === 'jpg' ? 'jpeg' : $extension);
  $src = $functionCreate($path);
  list($src_width, $src_height) = getimagesize($path);

  if ($crop) {
    // жорстка обрізка
    $dest = imagecreatetruecolor($size, $size);

    if ($src_width > $src_height) {
      imagecopyresampled($dest, $src, 0, 0, round($src_width / 2 - $src_height / 2), 0, $size, $size, $src_height, $src_height);
    } else {
      imagecopyresampled($dest, $src, 0, 0, 0, round($src_height / 2 - $src_width / 2), $size, $size, $src_width, $src_width);
    }
  } else {
    // пропорційне змінення
    $dest_width = $size;
    $dest_height = round($size * $src_height / $src_width);
    $dest = imagecreatetruecolor($dest_width, $dest_height);
    imagecopyresampled($dest, $src, 0, 0, 0, 0, $dest_width, $dest_height, $src_width, $src_height);
  }
  $functionSave = 'image' . ($extension === 'jpg' ? 'jpeg' : $extension);
  if (!file_exists($pathToSave)) {
    mkdir($pathToSave);
  }
  $functionSave($dest, "$pathToSave/$basename");
  addWatermark($path, "watermarks\Lovepik_com-380011847-watermark-border-red-and-yellow-china-wind-border-decoration.png", 'uploads-img/watermarked');
}
?>
<?php
if (isset($_SESSION['message'])) {
  list($message, $type) = $_SESSION['message'];
  echo '<div class="alert alert-' . $type . '">' . $message . '</div>';
  unset($_SESSION['message']);
}
?>
<form action="/upload" method="POST" enctype="multipart/form-data">
  <div class="mb-3">
    <label for="form-label">File:</label>
    <input type="file" class="form-control" name="file">
  </div>
  <button type="submit" class="btn btn-primary">Upload</button>
</form>