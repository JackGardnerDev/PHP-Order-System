<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta name="description" content="Mouse Pad Order & History System using PHP">
    <meta name="keywords" content="PHP">
    <meta name="author" content="Jack Gardner">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mouse Pad Experts</title>
    <link rel="icon" type="image/x-icon" href="imgs/favicon.png">
    <link rel="stylesheet" href="assets/css/style.css?<?php echo date('his')?>">

    <!-- PHP - Set default timezone to Melbourne, include commission class, sets commission variable to null, creates empty array for search with name, month and year, create commission object from csv and calculate commission from inputs  -->
    <?php

    date_default_timezone_set ( "Australia/Melbourne" );

    include "classes/Commission.php";
    $commission = null;

    $search = [
        "name" => "",
        "month" => "",
        "year" => ""
    ];
    
    if (isset($_GET['submit'])) {
        
        $commission = new Commission("data/sales.csv");
        try {
            $search["name"] = filter_input(INPUT_GET, "salesperson", FILTER_SANITIZE_STRING);
            $search["month"] = filter_input(INPUT_GET, "month", FILTER_SANITIZE_NUMBER_INT);
            $search["year"] = filter_input(INPUT_GET, "year", FILTER_SANITIZE_NUMBER_INT);

            $commission->calculate($search);
        } catch (Exception $e) {
            echo $e;
        }
    }

    ?>

</head>
<body>

    <!-- Section for Commission Search -->
    <div class="container">

        <!-- Header -->
        <header class="logo">
            <h1>Mouse Pad Experts</h1>
        </header>

        <!-- Navigation -->
        <nav>
            <a href="index.php">Order</a>
            <a href="commission.php" class="active">Commissions</a>
        </nav>

        <!-- Sub Heading -->
        <section class="input">
            <h2>Commission Search</h2>

            <!-- Form -->
            <form>
                <div class="row">
                    <!-- Validation COULD get a list of unique names in file and use them only -->
                    <label for="salesperson">Salesperson</label>
                    <input name="salesperson" type="text" required>
                </div>
                <div class="row">
                    <label for="month">Month</label>
                    <!-- <input name="month" type="number" min="1" max="12"> -->
                    <select name="month" id="month" required>
                        <?php
                        for ($i = 1; $i <= 12; $i++) {
                            $dateObj   = DateTime::createFromFormat('!m', $i);
                            echo "<option value='$i'>" . $dateObj->format('F') . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="row">
                    <!-- Validation COULD get a list of unique years in the file and use them only -->
                    <label for="year">Year</label>
                    <input name="year" type="number" min="2010" max="<?php echo date('Y'); ?>" required>
                </div>

                <!-- Submit button -->
                <div class="row">
                    <button class="block" type="submit" name="submit">Get Commission</button>
                </div>
            </form>
        </section>

        <!-- Section for Commission Information -->
        <section class="output">
            <h2>Commission Information</h2>
            <table class="tbl">
                <!-- Salesperson -->
                <tr>
                    <th scope="row">Salesperson</th>
                    <td><?php if(!empty($search['name'])){echo $search['name'];} ?></td>
                </tr>
                <!-- Commission Period -->
                <tr>
                    <th scope="row">Commission Period</th>
                    <td><?php
                        if(!empty($search['month'])){
                            $dateObj = DateTime::createFromFormat('!m', $search['month']);
                            echo $dateObj->format('F') . ", " . $search['year'];
                        }
                        ?>
                    </td>
                </tr>
                <!-- Total Sales -->
                <tr>
                    <th scope="row">Total Sales</th>
                    <td class="num"><span class="currency">$</span><?php 
                        if(!empty($commission)){
                            echo number_format($commission->getTotalSales(), 2); 
                        }
                        ?></td>
                </tr>
                <!-- Commission Rate -->
                <tr>
                    <th scope="row">Commission Rate</th>
                    <td class="num"><?php 
                        if(!empty($commission)){
                            echo ($commission->getRate() * 100) . "%"; 
                        }?></td>
                </tr>
                <!-- Commission Amount -->
                <tr>
                    <th scope="row">Commission Amount</th>
                    <td class="num"><span class="currency">$</span><?php 
                        if(!empty($commission)){
                            echo number_format($commission->getCommission(), 2); 
                        }?></td>
                </tr>
            </table>
        </section>

    </div>

</body>
</html>