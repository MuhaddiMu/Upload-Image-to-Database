<!-- Upload Image to Database -->
<?php

// Database Connection

$Server = "localhost";
$Username = "root";
$Password = "root";
$DB = "Uploads";
$Connection = mysqli_connect($Server, $Username, $Password, $DB);

if (!$Connection)
	{
	die("Connection Failed: " . mysqli_connect_error());
	}

?>

<?php
define("Title", "PHP File Upload");

if (isset($_FILES['Image']))
	{
	$File = $_FILES['Image'];
	$FileName = $File['name'];
	$FileTmp = $File['tmp_name'];
	$FileType = $File['type'];
	$FileSize = $File['size'];
	$FileError = $File['error'];
	$FileExt = pathinfo($FileName, PATHINFO_EXTENSION);
	if ($FileName)
		{
		if ($FileExt == 'png' || $FileExt == 'jpg' || $FileExt == 'gif' || $FileExt == 'bmp' || $FileExt == 'tiff')
			{
			if ($FileSize <= 5242880)
				{
				$FileNewName = uniqid(uniqid()) . '.' . $FileExt;
				$Dstn = "images/" . $FileNewName;
				if (move_uploaded_file($FileTmp, $Dstn))
					{
					$Web = "http://" . $_SERVER['SERVER_NAME'] . ":8888/File Upload";
					$Query = "INSERT INTO `Image`(`Image_Path`, `ID`) VALUES ('$Web/$Dstn', NULL)";
					$Result = mysqli_query($Connection, $Query);
					$Alert = "<div class='alert alert-success'>Perfect! File Uploaded Successfully at <a href='$Dstn'>$Dstn</a></div>";
					}
				}
			  else
				{
				$Alert = "<div class='alert alert-danger'>Your file size must be less than 5MB</div>";
				}
			}
		  else
			{
			$Alert = "<div class='alert alert-danger'>Only <p class='text-danger'>.bmp, .jpg, .png, .tiff, .gif</p> Extensions are allowed.</div>";
			}
		}
	}

?>


<!doctype html>
<html lang="en">
  <head>
      <style>
          p {
              display: inline;
          }
      </style>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="animate.css">

    <title><?php
echo Title; ?></title>
  </head>
  <body>
      <div class="container">
        <h1><?php
echo Title; ?></h1>
          
          <hr>
          
          <?php

if (!empty($File))
	{
	echo $Alert;
	}

?> 
          <div class="row">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="Image">Select an Image</label>
                    <input type="file" class="form-control" name="Image" id="Image" required>
                </div>
                <div class="form-group">
                    <button type="submit" name="Upload" id="Upload" class="btn btn-primary"><i class="fas fa-upload"></i> Upload Image</button>
                </div>
            </form>
          </div>
          
          <div class="row">
          
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="form-group">
                    <button type="submit" name="UploadShow" id="Upload" class="btn btn-primary"><i class="fas fa-images"></i> Show Uploads</button>
                    <button type="submit" name="UploadHide" id="Upload" class="btn btn-primary"><i class="fas fa-eye-slash"></i> Hide Uploads</button>
                </div>
              </form>
            </div>
            
          <hr>
          
           <?php

if (isset($_POST['UploadShow']))
	{
	echo "<div class='animated fadeInDownBig'>";
	$Query = "SELECT * FROM `Image`";
	$Result = mysqli_query($Connection, $Query);
	if (mysqli_num_rows($Result) > 0)
		{
		while ($Row = mysqli_fetch_assoc($Result))
			{
			echo '<img src ="' . $Row['Image_Path'] . '" class="img-thumbnail" height="50%" width="50%" alt="' . $Row['ID'] . '">';
			}

		echo "</div>";
		}
	  else
		{
		echo "<div class='alert alert-warning'>No Image in Database. Please upload it.</div>";
		}
	}

if (isset($_POST['UploadHide']))
	{
	echo "<div class='animated fadeOutUpBig'>";
	$Query = "SELECT * FROM `Image`";
	$Result = mysqli_query($Connection, $Query);
	if (mysqli_num_rows($Result) > 0)
		{
		while ($Row = mysqli_fetch_assoc($Result))
			{
			echo '<img src ="' . $Row['Image_Path'] . '" class="img-thumbnail" height="50%" width="50%" title="' . $Row['ID'] . '">';
			}

		echo "</div>";
		}
	  else
		{
		echo "<div class='alert alert-warning'>No Image in Database. Please upload it.</div>";
		}
	}

?>          
          
      </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->  
    <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>