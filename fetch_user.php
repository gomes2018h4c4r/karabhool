<?php

//fetch_user.php

include('config/database_connection.php');

session_start();

$query = "
SELECT * FROM users 
WHERE id != '" . $_SESSION['user_id'] . "' 
";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

$output = '
<table class="table table-bordered table-striped">
	<tr>
		<th width="70%">pseudo</td>
		<th width="20%">Statut</td>
		<th width="10%">Action</td>
	</tr>
';

foreach ($result as $row) {
    $status = '';
    $current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 10 second');
    $current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
    $user_last_activity = fetch_user_last_activity($row['pseudo'], $connect);
    if ($user_last_activity > $current_timestamp) {
        $statut = '<span class="label label-success">Online</span>';
    } else {
        $statut = '<span class="label label-danger">Offline</span>';
    }
    $output .= '
	<tr>
		<td>' . $row['pseudo'] . ' ' . count_unseen_message($row['id'], $_SESSION['user_id'], $connect) . ' ' . fetch_is_type_status($row['id'], $connect) . '</td>
		<td>' . $statut . '</td>
		<td><button type="button" class="btn btn-info btn-xs start_chat" data-touserid="' . $row['id'] . '" data-topseudo="' . $row['pseudo'] . '">Start Chat</button></td>
	</tr>
	';
}

$output .= '</table>';

echo $output;

?>