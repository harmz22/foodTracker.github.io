<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="headfootstyle.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style></style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
            
            // Add event listener to the form
            $("form").submit(function(event) {
                // Check if at least one checkbox is checked
                if ($('input[type="checkbox"]:checked').length === 0) {
                    // If no checkbox is checked, prevent form submission
                    alert("Please select at least one item to add to Today's List.");
                    event.preventDefault();
                }
            });
        });

        function redirectToDietDash() {
            window.location.href = 'dietdash.php';
        }
    </script>
</head>
<body>
    <div class = "backwrap">
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Food List</h2>
                        <a href="create.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Food</a>
                    </div>
                    
                    <?php
                
                    require_once "config.php";
                    
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        if (isset($_POST['selected_items']) && is_array($_POST['selected_items'])) {
                            $sql = "INSERT INTO todaylist (name, fat, carb, protein) SELECT name, fat, carb, protein FROM foodlist WHERE id IN (";
                    
                            $placeholders = implode(",", array_fill(0, count($_POST['selected_items']), "?"));
                            $sql .= $placeholders . ")";
                    
                            if ($stmt = mysqli_prepare($link, $sql)) {
                                $types = str_repeat("i", count($_POST['selected_items']));
                                $params = array_merge([$stmt, $types], $_POST['selected_items']);
                                $params = array_merge([$stmt, $types], array_values($_POST['selected_items']));
                                mysqli_stmt_bind_param(...$params);
                    
                                if (mysqli_stmt_execute($stmt)) {
                                    echo "Selected items added to todaylist successfully.";
                                    echo '<script>redirectToDietDash();</script>'; // Redirect after successful addition
                                } else {
                                    echo "Oops! Something went wrong. Please try again later.";
                                }
                    
                                mysqli_stmt_close($stmt);
                            }
                        } else {
                            echo "No items selected to add to today list.";
                        }
                    }

                    $sql = "SELECT * FROM foodlist";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="post">';
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>Name</th>";
                                        echo "<th>Fat</th>";
                                        echo "<th>Carbohydrate</th>";
                                        echo "<th>Protein</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>" . $row['fat'] . "</td>";
                                        echo "<td>" . $row['carb'] . "</td>";
                                        echo "<td>" . $row['protein'] . "</td>";
                                        echo "<td>";
                                            echo '<input type="checkbox" name="selected_items[]" class = "mr-3" value="' . $row['id'] . '">';
                                            echo '<a href="read.php?id='. $row['id'] .'" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="update.php?id='. $row['id'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="delete.php?id='. $row['id'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            echo '<input type="submit" name="submit" class="btn btn-danger" value="Add to Today’s List">';
                            echo '</form>';
                            if (isset($successMessage)) {
                                echo '<div class="alert alert-success mt-3">' . $successMessage . '</div>';
                            } elseif (isset($errorMessage)) {
                                echo '<div class="alert alert-danger mt-3">' . $errorMessage . '</div>';
                            }
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }

                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>
    </div>

</body>
</html>