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

    <!-- PHP - include classes, create objects, filters and input values to objects, calculate costs of inputs and sets price -->

    <?php

        include_once 'classes/Prices.php';
        include 'classes/Pads.php';
        include 'classes/Costs.php';

        $prices = new Prices("data/prices.xml");
        $pads = new Pads;
        $costs = new Costs;

        $showResults = false;

        if (isset($_GET['submit'])) {
            $pads->addPads(array_filter($_GET['length']), array_filter($_GET['width']));
            $costs->calcCosts($pads, $_GET['quality'], $_GET['discount']);

            $showResults = true;
        }

    ?>

</head>
<body>

    <!-- Heading -->
    <div class="container">
        <header class="logo">
            <h1>Mouse Pad Experts</h1>
        </header>

        <!-- Navigation -->
        <nav>
            <a href="index.php" class="active">Order</a>
            <a href="commission.php">Commissions</a>
        </nav>

        <!-- Order Form Section -->
        <section class="input">

            <!-- Sub Heading -->
            <h2>Order Mouse Pad</h2>

            <!-- Form -->
            <form>
                
                <!-- Salesperson Input -->
                <h3>Salesperson</h3>
                        <div class="row">
                            <label for="discount">Salesperson</label>
                            <input id="discount" type="text" name="salesperson">
                        </div>

                <div class="flex-h">

                    <!-- Input for Mousepad dimensions -->
                    <section class="half flex-item">
                        <h3>Mouse Pads</h3>
                        <table id="tblPads" class="tbl">
                            <thead>
                                <th scope="row">Mouse Pad</th>
                                <th class="forty">Width</th>
                                <th class="forty">Length</th>
                            </thead>
                            <tfoot>
                                <td colspan="3"><button type="button" class="btn block" id="addPad">Add Mouse Pad</button></td>
                            </tfoot>
                            <tbody>
                                <tr id=1>
                                    <td>1</td>
                                    <td><input type="number" placeholder="1 = 1 Centimeter" step=0.01 min=0.0 id="width1" name="width[]"></td>
                                    <td><input type="number" placeholder="1 = 1 Centimeter" step=0.01 min=0.0 id="length1" name="length[]"></td>
                                </tr>
                            </tbody>
                        </table>
                    </section>
                    
                    <!-- Input for Quality / Speed -->
                    <section class="half flex-item">
                        <h3>Quality / Speed</h3>

                        <!-- PHP - Loops through the data array from the prices object and takes input -->
                        <?php

                            $i = 0;
                            foreach ($prices->getDataAsArray() as $key => $value) {
                            ?>
                                <div class="row">
                                    <input id="q_<?php echo $i; ?>" type="radio" name="quality" value="<?php echo $key; ?>" <?php if ($i == 0) {
                                                                                                                                echo "checked";
                                                                                                                            } ?>>
                                    <label class="radio-label" for="q_<?php echo $i; ?>"><?php echo $key; ?> - $<?php echo $value; ?>/ m2</label>
                                </div>
                            
                            <!-- Add 1 number for each new mouse pad -->
                            <?php
                                $i++;
                            }

                        ?>

                        <!-- Input for Discount -->
                        <h3>Discount</h3>
                        <div class="row">
                            <label for="discount">Discount (max 5%)</label>
                            <input id="discount" type="number" step=1 min=0 max=5 name="discount">
                        </div>
                    </section>

                </div>

                <!-- Submit -->
                <div class="row">
                    <button class="block" name="submit">Submit</button>
                </div>
            </form>

        </section>

        <!-- Output of results (Number of mouse pads, total area, wastage allowance, BLM, quality / speed, total cost, discount amount, cost without gst, gst, cost) -->
        <section class="output">

            <!-- Results -->
            <!-- PHP - Get results -->
            <?php if ($showResults) { ?>
                <h2>Results</h2>
                <table class="tbl output">
                    <tbody>
                        <tr>
                            <th class="half">Number of Mouse Pads</th>
                            <td class="num"><?php echo $pads->numPads(); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Total Area</th>
                            <td class="num"><?php echo $pads->getTotalArea(); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Wastage allowance</th>
                            <td class="num"><?php echo ($costs::WASTE * 100); ?>%</td>
                        </tr>
                        <tr>
                            <th scope="row">m2 (rounded)</th>
                            <td class="num"><?php echo $costs->getM2(); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Quality / Speed</th>
                            <td><?php echo $costs->getQuality(); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Total Cost</th>
                            <td class="num"><span class="currency">$</span><?php echo $costs->getTotalCost(); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Discount Amount</th>
                            <td class="num"><span class="currency">$</span><?php echo $costs->getDiscount_amount(); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Cost (excl GST)</th>
                            <td class="num"><span class="currency">$</span><?php echo $costs->getExcl_cost(); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">GST</th>
                            <td class="num"><span class="currency">$</span><?php echo $costs->getGst(); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Cost (incl GST)</th>
                            <td class="num"><span class="currency">$</span><?php echo $costs->getIncl_cost(); ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php }; ?>

        </section>

    </div>

    <!-- JavaScript - Add a new row to the table when clicking the add button, adds cell for input -->

    <script>

        document.getElementById('addPad').addEventListener('click', function() {
            //add an additional row to the bottom of the pads table dynamically
            var padsTable = document.getElementById("tblPads").getElementsByTagName('tbody')[0];;
            var rowCount = padsTable.rows.length + 1;

            //add to end
            var newRow = padsTable.insertRow(-1);

            //add first col
            var newNum = newRow.insertCell(0);

            //Show row number
            newNum.innerHTML = rowCount; 

            //add second col
            var newLength = newRow.insertCell(1); 
            newLength.innerHTML = '<input type="number" step=0.01 min=0.0 name="width[]">';

            //add third col
            var newWidth = newRow.insertCell(2); 
            newWidth.innerHTML = '<input type="number" step=0.01 min=0.0 name="length[]">'
        })

    </script>

</body>
</html>