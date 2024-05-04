<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Today's Food List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="headfootstyle.css">
    <style></style>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });

        function redirectToDietDash() {
            window.location.href = 'dietdash.php#today';
        }

        // Add event listener to the form
        $("form").submit(function(event) {
            // Check if at least one checkbox is checked
            if ($('input[type="checkbox"]:checked').length === 0) {
                // If no checkbox is checked, prevent form submission and show an alert
                alert("Please select at least one item to remove from Today's List.");
                event.preventDefault();
            }
        });
    </script>
</head>
<body>
    <div class = "backwrap">
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mt-5 mb-3 clearfix">
                            <h2 class="pull-left">Today's Food List</h2>
                        </div>
                        
                        <?php
                        require_once "config.php";

                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            if (isset($_POST['selected_items']) && is_array($_POST['selected_items'])) {
                                $sql = "DELETE FROM todaylist WHERE id IN (";
                                $placeholders = implode(",", array_fill(0, count($_POST['selected_items']), "?"));
                                $sql .= $placeholders . ")";
                        
                                if ($stmt = mysqli_prepare($link, $sql)) {
                                    $types = str_repeat("i", count($_POST['selected_items']));
                                    $params = array_merge([$stmt, $types], $_POST['selected_items']);
                                    mysqli_stmt_bind_param(...$params);

                                    if (mysqli_stmt_execute($stmt)) {
                                        echo "Selected items removed from today's list successfully.";
                                        echo '<script>redirectToDietDash();</script>'; // Redirect after successful removal
                                    } else {
                                        echo "Oops! Something went wrong. Please try again later.";
                                    }

                                    mysqli_stmt_close($stmt);
                                }
                            } else {
                                echo "No items selected to remove from today's list.";
                            }
                        }

                        $sql = "SELECT * FROM todaylist";
                        if ($result = mysqli_query($link, $sql)) {
                            if (mysqli_num_rows($result) > 0) {
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
                                $totalFat = $totalCarb = $totalProtein = 0;
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['name'] . "</td>";
                                    echo "<td>" . $row['fat'] . "</td>";
                                    echo "<td>" . $row['carb'] . "</td>";
                                    echo "<td>" . $row['protein'] . "</td>";
                                    echo '<td><input type="checkbox" name="selected_items[]" class="mr-3" value="' . $row['id'] . '"></td>';
                                    echo "</tr>";

                                    $totalFat += $row['fat'];
                                    $totalCarb += $row['carb'];
                                    $totalProtein += $row['protein'];
                                }
                                echo "</tbody>";
                                echo "<tfoot>";
                                echo "<tr>";
                                echo "<td colspan='2'>Totals</td>";
                                echo "<td>$totalFat</td>";
                                echo "<td>$totalCarb</td>";
                                echo "<td>$totalProtein</td>";
                                echo "<td></td>";
                                echo "</tr>";
                                echo "</tfoot>";
                                echo "</table>";
                                echo '<input type="submit" name="submit" class="btn btn-danger" value="Remove from Today’s List">';
                                
                                echo '</form>';
                                mysqli_free_result($result);
                            } else {
                                echo '<div class="alert alert-danger"><em>No records were found in today\'s list.</em></div>';
                            }
                        } else {
                            echo "Oops! Something went wrong. Please try again later.";
                        }

                        mysqli_close($link);
                        ?>
                    </div>
                </div>        
            </div>
        </div>
        </div>
</body>
</html>