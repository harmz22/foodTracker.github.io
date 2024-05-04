<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Macronutrient Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="headfootstyle.css">
    <style>
    #chart-title {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }
    </style>
</head>
<body>
<div class = "backwrap">
<div class="wrapper">
    <div class="row">
        <div class="col-md-12">
            <h2 id="chart-title">Macronutrient For Today's FoodList</h2>

            <?php
            require_once "config.php";

            $sql = "SELECT SUM(fat) AS totalFat, SUM(carb) AS totalCarb, SUM(protein) AS totalProtein FROM todaylist";
            $result = mysqli_query($link, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                $totals = mysqli_fetch_assoc($result);
                $totalFat = $totals['totalFat'];
                $totalCarb = $totals['totalCarb'];
                $totalProtein = $totals['totalProtein'];

                // Calculate percentages
                $total = $totalFat + $totalCarb + $totalProtein;
                $fatPercentage = ($totalFat / $total) * 100;
                $carbPercentage = ($totalCarb / $total) * 100;
                $proteinPercentage = ($totalProtein / $total) * 100;

                // Output canvas for the chart
                echo '<canvas id="macronutrientChart" width="400" height="400"></canvas>';

                // Output script to generate the pie chart
                echo '<script>
                        var ctx = document.getElementById("macronutrientChart").getContext("2d");
                        var myPieChart = new Chart(ctx, {
                            type: "pie",
                            data: {
                                labels: ["Fat", "Carbohydrate", "Protein"],
                                datasets: [{
                                    data: [' . $fatPercentage . ', ' . $carbPercentage . ', ' . $proteinPercentage . '],
                                    backgroundColor: ["#FF5733", "#FFD700", "#5DADE2"],
                                }],
                            },
                        });
                      </script>';
            } else {
                echo '<p>No data available for the chart.</p>';
            }

            // Close connection
            mysqli_close($link);
            ?>
        </div>
    </div>
</div>
</div>

</body>
</html>