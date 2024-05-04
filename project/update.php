<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$name = $fat = $carb = $protein = "";
$name_err = $fat_err = $carb_err = $protein_err = "";

// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];

    // Validate name
    $input_name = trim($_POST["name"]);
    if (empty($input_name)) {
        $name_err = "Please enter a name.";
    } elseif (!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $name_err = "Please enter a valid name.";
    } else {
        $name = $input_name;
    }

    // Validate Fat
    $input_fat = trim($_POST["fat"]);
    if (empty($input_fat)) {
        $fat_err = "Please enter the fat amount.";
    } elseif (!preg_match('/^\d+(\.\d{2})?$/', $input_fat)) {
        $fat_err = "Please enter a positive decimal value with at least two decimal places.";
    } else {
        $fat = $input_fat;
    }

    // Validate Carb
    $input_carb = trim($_POST["carb"]);
    if (empty($input_carb)) {
        $carb_err = "Please enter the carb amount.";
    } elseif (!preg_match('/^\d+(\.\d{2})?$/', $input_carb)) {
        $carb_err = "Please enter a positive decimal value with at least two decimal places.";
    } else {
        $carb = $input_carb;
    }

    // Validate Protein
    $input_protein = trim($_POST["protein"]);
    if (empty($input_protein)) {
        $protein_err = "Please enter the protein amount.";
    } elseif (!preg_match('/^\d+(\.\d{2})?$/', $input_protein)) {
        $protein_err = "Please enter a positive decimal value with at least two decimal places.";
    } else {
        $protein = $input_protein;
    }

    // Check input errors before updating in the database
    if (empty($name_err) && empty($fat_err) && empty($carb_err) && empty($protein_err)) {
        // Prepare an update statement
        $sql = "UPDATE foodlist SET name=?, fat=?, carb=?, protein=? WHERE id=?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssddi", $param_name, $param_fat, $param_carb, $param_protein, $param_id);

            // Set parameters
            $param_name = $name;
            $param_fat = $fat;
            $param_carb = $carb;
            $param_protein = $protein;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to landing page
                header("location: dietdash.php#foodlist");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $id = trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM foodlist WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $name = $row["name"];
                    $fat = $row["fat"];
                    $carb = $row["carb"];
                    $protein = $row["protein"];
                } else {
                    // URL doesn't contain a valid id. Redirect to the error page
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="crud.css">
    <style></style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the food record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Fat</label>
                            <textarea name="fat" class="form-control <?php echo (!empty($fat_err)) ? 'is-invalid' : ''; ?>"><?php echo $fat; ?></textarea>
                            <span class="invalid-feedback"><?php echo $fat_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Carbohydrate</label>
                            <textarea name="carb" class="form-control <?php echo (!empty($carb_err)) ? 'is-invalid' : ''; ?>"><?php echo $carb; ?></textarea>
                            <span class="invalid-feedback"><?php echo $carb_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Protein</label>
                            <input type="text" name="protein" class="form-control <?php echo (!empty($protein_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $protein; ?>">
                            <span class="invalid-feedback"><?php echo $protein_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="dietdash.php#foodlist" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
