<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$name = $fat = $carb = $protein = "";
$name_err = $fat_err = $carb_err = $protein_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Check input errors before inserting into the database
    if (empty($name_err) && empty($fat_err) && empty($carb_err) && empty($protein_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO foodlist (name, fat, carb, protein) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssdd", $param_name, $param_fat, $param_carb, $param_protein);

            // Set parameters
            $param_name = $name;
            $param_fat = $fat;
            $param_carb = $carb;
            $param_protein = $protein;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to the landing page
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
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="crud.css">
    <style></style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add food record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="dietdash.php#foodlist" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>