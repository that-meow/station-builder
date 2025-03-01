<?php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
?>
<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="refresh" content="30">

    <title>Remaining demand</title>

    <style>

        table, th, td {

            border: 1px solid;

        }

        table {

            border-collapse: collapse;

        }

	html {

		background-color: black;

	}

	td, h1 {

		color: gray;

		padding: 5px;

	}

	.unbordered {

		border: 0px;

	}

    </style>

</head>

<body>

    <?php

    echo("<h1>" . $_GET["system"] . "</h1>");

    ?>
    <table>

    <thead>

        <tr>

            <td>Commodity</td>

            <td>Current Amount</td>
            <td>Progress</td>
            <td>Economy</td>

            <td>Possible Buy Locations</td>

            <td>Total Amount</td>

        </tr>

    </thead>

    <tbody>
    <?php

    $connect = new mysqli(<your db details here>) or die("pripojeni se nezdarilo");

    $connect->set_charset("utf8") or die("Charset chyba.");

    $stmt = $connect->prepare("SELECT * FROM `relations`

LEFT JOIN commodities ON relations.commodity_id = commodities.commodity_id

WHERE relations.station_id = (SELECT station_id FROM stations WHERE system_name = ?)");

    $stmt->bind_param("s", $_GET["system"]);
    $stmt->execute();

    $result = $stmt->get_result() or die("Fault1");

    $connect->close();

    while($row = $result->fetch_object()) {

        $commodity_name = $row->name;

        $current_amount = $row->current_amount;

        $economy = $row->economy;

        $edsm_id = $row->edsm_id;

        $total_amount = $row->original_amount;
        if($edsm_id == 666) {
            $buy_locations = "Inara";
        } else {
            $buy_locations = "<a target='_blank' href='https://www.edsm.net/en/search/stations/index/buyCommodity/" . $edsm_id . "/cmdrPosition/" . $_GET["system"]. "/sortBy/distanceCMDR'>EDSM</a>"; 
        }

        echo("<tr><td>" . $commodity_name . "</td>\n<td>" . $current_amount . "</td>\n<td style='display: flex'><progress value='" . ($total_amount-$current_amount) . "' max='" . $total_amount . "'>" . floor(($total_amount-$current_amount)/$total_amount * 100) . " %</progress><label style='flex:1; text-align:right'>". floor(($total_amount-$current_amount)/$total_amount * 100) . " %</label></td>\n<td>" . $economy . "</td>\n<td>" . $buy_locations . "</td>\n<td>" . $total_amount . "</td>\n</tr>");

    }
    ?>

    </tbody>

    </table>
	<p><a href="./deliver?system=<?php echo($_GET["system"]) ?>">Register deliveries here</a></p>

</body>

</html>

