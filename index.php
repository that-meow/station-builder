<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
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
	    td, h1, p {
		    color: gray;
		    padding: 5px;
	    }
        .data-href { cursor: pointer; }
    </style>
</head>
<?php
    $connect = new mysqli(<your db details here>) or die("pripojeni se nezdarilo");
    $connect->set_charset("utf8") or die("Charset chyba.");
    $query = "SELECT stations.station_id, stations.system_name, stations.system_architect, SUM(relations.original_amount) AS total_original, SUM(relations.current_amount) AS total_current FROM `stations`
LEFT JOIN relations ON stations.station_id = relations.station_id
GROUP BY stations.station_id";
    $result = $connect->query($query) or die("Fault1");
    $connect->close();
?>
<body>
    <h1>Welcome to Station Builder!</h1>
    <p>So far, we're tracking <?php echo(mysqli_num_rows($result)); ?> projects.</p>
    <table>
    <thead>
        <tr>
            <td>System Name</td>
            <td>System Architect</td>
            <td>Remaining Units</td>
            <td>Progress</td>
            <td>Total Units</td>
        </tr>
    </thead>
    <tbody>
    <?php
    while($row = $result->fetch_object()) {
        $name = $row->system_name;
        $architect = $row->system_architect;
        $current_amount = $row->total_current;
        $original_amount = $row->total_original;
        $redirect = '<tr class="data-href" onclick="window.location=`./stationView?system=' . $name . '`;"><td>';
        echo($redirect . $name . "</td>\n<td>" . $architect . "</td>\n<td>" . $current_amount . "</td>\n<td style='display: flex'><progress value='" . ($original_amount-$current_amount) . "' max='" . $original_amount . "'>" . floor(($original_amount-$current_amount)/$original_amount * 100) . " %</progress><label style='flex:1; text-align:right'>" . floor(($original_amount-$current_amount)/$original_amount * 100) . " %</label></td>\n<td>" . $original_amount . "</td>\n</tr>");
    }
    ?>
    </tbody>
    </table>
    <p>Want us to list your system here, too? <a href="./submit-a-station">Learn more here.</a></p>
</body>
</html>
