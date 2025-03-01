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
    <title>Submit a station</title>
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
	    td, label, h2, h3, p {
		    color: gray;
            padding: 5px
	    }
        .spacer {
            margin-top: 0px;
		    padding-top: 0px;
            font-size: smaller;
            padding-bottom: 5px
        }
        .data-href { cursor: pointer; }
    </style>
</head>
<body>
    <?php
    $connect = new mysqli(<your db details here>) or die("pripojeni se nezdarilo");
    $connect->set_charset("utf8") or die("Charset chyba.");
    $query = "SELECT `commodity_id`, `name` FROM `commodities` ORDER BY `commodities`.`commodity_id` ASC";
    $result = $connect->query($query) or die("Fault1");
    $commodity_count = mysqli_num_rows($result);
    if(!empty($_POST["system-name"])) {
        $stmt = $connect->prepare("INSERT INTO `stations`( `system_name`, `system_architect`) VALUES (?, ?)");
        $stmt->bind_param("ss", $_POST["system-name"], $_POST["system-architect"]);
        $stmt->execute();
        $stmt2 = $connect->prepare("SELECT `station_id` FROM `stations` WHERE `system_name` = ?");
        $stmt2->bind_param("s", $_POST["system-name"]);
        $stmt2->execute();
        $station_id = $stmt2->get_result()->fetch_object()->station_id or die("Fault1");
        echo($stmt->error);
        echo($stmt2->error);
        $insert_commodities = $connect->prepare("INSERT INTO `relations`(`station_id`, `commodity_id`, `original_amount`, `current_amount`) VALUES (?, ?, ?, ?)");
        for ($x=1; $x < $commodity_count + 1; $x++) {
            if (empty($_POST["initial-values" . $x])){ $_POST["initial-values" . $x] = 0; }
            if (empty($_POST["current-values" . $x])){ $_POST["current-values" . $x] = 0; }
            $insert_commodities->bind_param("iiii", $station_id, $x, $_POST["initial-values" . $x], $_POST["current-values" . $x]);
            $insert_commodities->execute();
        }

    }
    $connect->close();
    ?>
    <h2>Submit your system to Station Builder</h2>
    <p>Want to see your system on this website? Please fill out the form below.</p>
    <p>Please do remember that this isn't some magical doo-dad that'll update the data for you automatically. For now, you need to enter your deliveries through a webpage. (there may be plans to implement a way of automatically submitting new deliveries later on...)</p>
    <h3>System submission form:</h3>
    <form method="POST" action=".">
        <label for="system-name">System name:</label><br>
        <input type="text" id="system-name" name="system-name" required><br>
        <p class="spacer">The name of the system you're colonizing.</p>
        <label for="system-architect">System architect:</label><br>
        <input type="text" id="system-architect" name="system-architect" required><br>
        <p class="spacer">This will let others find your system by your commander name. No need to include the "CMDR" prefix</p>
        <label for="initial-values">Initial values:</label><br>
        <table id="initial-values">
            <thead>
                <tr>
                    <td>Commodity</td>
                    <td>Initial values</td>
                    <td>Current values</td>
                </tr>
            </thead>
            <tbody>
            <?php
    while($row = $result->fetch_object()) {
        $name = $row->name;
        $comm_id = $row->commodity_id;
        echo("<tr><td>" . $name . "</td>");
        echo("<td><input type='number' name='initial-values" . $comm_id . "'></td>");
        echo("<td><input type='number' name='current-values" . $comm_id . "'></td></tr>");
    }
            ?>
            </tbody>
        </table>
        <p class="spacer">For "initial values", please fill in the commodity amounts from the earliest date you can remember. These values are used for showing the progress of your station.</p>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
