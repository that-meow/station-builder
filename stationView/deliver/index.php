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

    <title>Delivery menu</title>

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

	td, h1, label {

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
    if(!empty($_POST["request_type"])) {
        if ($_POST["request_type"] == "deliver") {
            $connect = new mysqli(<your db details here>) or die("pripojeni se nezdarilo");
            $connect->set_charset("utf8") or die("Charset chyba.");

                $stmt = $connect->prepare("UPDATE `relations`
                SET `current_amount` = (SELECT `current_amount` FROM `relations` WHERE `station_id` = (SELECT station_id FROM stations WHERE system_name = ?) AND `commodity_id` = (SELECT commodity_id FROM commodities WHERE name = ?)) - ?
                WHERE `station_id` = (SELECT station_id FROM stations WHERE system_name = ?) AND `commodity_id` = (SELECT commodity_id FROM commodities WHERE name = ?)"
            );
            $stmt->bind_param("ssiss", $_GET["system"], $_POST["commodity"], $_POST["amount"], $_GET["system"], $_POST["commodity"]);
            $stmt->execute();
            echo($stmt->error);
            
        }
        elseif ($_POST["request_type"] == "set") {
            $connect = new mysqli(<your db details here>) or die("pripojeni se nezdarilo");
            $connect->set_charset("utf8") or die("Charset chyba.");

            $stmt = $connect->prepare("UPDATE `relations`
            SET `current_amount` = ?
            WHERE `station_id` = (SELECT station_id FROM stations WHERE system_name = ?) AND `commodity_id` = (SELECT commodity_id FROM commodities WHERE name = ?)");
            $stmt->bind_param("iss", $_POST["amount"], $_GET["system"], $_POST["commodity"]);
            $stmt->execute();
            echo($stmt->error);

        }
        $connect->query("UPDATE `relations` SET `current_amount`= 0 WHERE `current_amount` < 0");
        $connect->close();
    }
    echo("<h1>Deliver to " . $_GET["system"] . "</h1>");
    ?>
    <form method="POST" action=".?system=<?php echo($_GET["system"]) ?>">
        <input type="radio" required id="set" name="request_type" value="set">
        <label for="set">set a commodity to a specified amount</label><br>
        <input type="radio" required id="deliver" name="request_type" value="deliver">
        <label for="deliver">deliver an amount of a commodity</label><br>
        <label for="amount">Amount:</label><br>
        <input type="number" id="amount" name="amount" required><br>
        <label for="commodity">Commodity:</label><br>
        <input type="text" id="commodity" name="commodity" required><br>
        <input type="submit" value="Submit">
    </form>
</body>

</html>

