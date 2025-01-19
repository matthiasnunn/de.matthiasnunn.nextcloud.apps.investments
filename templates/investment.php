<?php

    script("investments", "lib/dataTables.min");  // `js/lib/dataTables.min.js` einbinden
    script("investments", "investment");          // `js/investment.js` einbinden

    style("investments", "lib/dataTables.min");   // `css/lib/dataTables.min.js` einbinden
    style("shared", "style");                     // `css/style.css` einbinden

?>

<div class="data">

    <?php echo $data; ?>

</div>

<?php

    function mapConditionToColor($value)
    {
        if ($value > 0)
        {
            return "color-green";
        }

        if ($value < 0)
        {
            return "color-red";
        }

        return "";
    }

    function toCurrencyString($number)
    {
        return number_format($number, 2, ',', '.');  // auf zwei Dezimalstellen runden
    }

?>

<div class="app">

    <div class="nav">
        <ul>
            <li>
                <a href="../uebersicht">
                    Übersicht
                </a>
            </li>
            <li class="<?php echo ($type === 'Aktie') ? 'active' : ''; ?>">
                <a href="aktien">
                    Aktien
                </a>
            </li>
            <li class="<?php echo ($type === 'Devise') ? 'active' : ''; ?>">
                <a href="devisen">
                    Devisen
                </a>
            </li>
            <li class="<?php echo ($type === 'ETF') ? 'active' : ''; ?>">
                <a href="etfs">
                    ETFs
                </a>
            </li>
            <li class="<?php echo ($type === 'Rohstoff') ? 'active' : ''; ?>">
                <a href="rohstoffe">
                    Rohstoffe
                </a>
            </li>
        </ul>
    </div>

    <div class="main" style="overflow-y: scroll">
        <table class="table" style="margin: -1.5em 0">
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo toCurrencyString($investmentIncludedModel->totalPurchasePrice); ?> €</td>
                    <td></td>
                    <td></td>
                    <td><?php echo toCurrencyString($investmentIncludedModel->totalCurrentPrice); ?> €</td>
                    <td class="<?php echo mapConditionToColor($investmentIncludedModel->totalRendite); ?>"><?php echo toCurrencyString($investmentIncludedModel->totalRendite); ?> %</td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>