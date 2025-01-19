<?php

    script("shared", "lib/chart");        // `js/lib/chart.js` einbinden
    script("investments", "uebersicht");  // `js/uebersicht.js` einbinden

    style("shared", "style");             // `css/style.css` einbinden

?>

<div class="data">

    <?php echo $aktien; ?>

</div>

<div class="data">

    <?php echo $devisen; ?>

</div>

<div class="data">

    <?php echo $etfs; ?>

</div>

<div class="data">

    <?php echo $rohstoffe; ?>

</div>

<div class="data">

    <?php echo $investmentsDevelopmentModelJson; ?>

</div>

<div class="app">

    <div class="nav">
        <ul>
            <li class="active">
                <a href="">
                    Übersicht
                </a>
            </li>
            <li>
                <a href="investment/aktien">
                    Aktien
                </a>
            </li>
            <li>
                <a href="investment/devisen">
                    Devisen
                </a>
            </li>
            <li>
                <a href="investment/etfs">
                    ETFs
                </a>
            </li>
            <li>
                <a href="investment/rohstoffe">
                    Rohstoffe
                </a>
            </li>
        </ul>
    </div>

    <div class="main" style="display: grid; grid-template-columns: 50% 50%; grid-template-rows: 50% 50%;">
        <div class="chart-wrapper">
            <canvas class="chart"></canvas>
        </div>
        <div class="chart-wrapper">
            <canvas class="chart"></canvas>
        </div>
        <div class="chart-wrapper">
            <canvas class="chart"></canvas>
        </div>
    </div>

</div>