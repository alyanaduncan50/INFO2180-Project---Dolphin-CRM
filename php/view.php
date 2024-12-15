<?php

include 'connection.php';

header('Content-Type: application/json');


$sql = "
    SELECT c.*, n.created_by AS note_created_by, n.created_at AS note_created_at, n.comment AS note_comment
    FROM contacts c
    JOIN notes n ON c.id = n.contact_id
";

$result = $conn->query($sql);

$contacts = [];


while ($row = $result->fetch_assoc()) {
    
    $contactIndex = array_search($row['id'], array_column($contacts, 'Id'));

    if ($contactIndex === false) {
        
        $contacts[] = [
            'Id' => $row['id'],
            'title' => $row['title'],
            'firstname' => $row['firstname'],
            'lastname' => $row['lastname'],
            'email' => $row['email'],
            'telephone' => $row['telephone'],
            'company' => $row['company'],
            'type' => $row['type'],
            'assigned_to' => $row['assigned_to'],
            'created_by' => $row['created_by'],
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
            'notes' => []  
        ];

        
        $contactIndex = count($contacts) - 1;
    }

    
    if ($row['created_by'] && $row['comment']) {
        $contacts[$contactIndex]['notes'][] = [
            'createdBy' => $row['note_created_by'],
            'createdAt' => $row['note_created_at'],
            'comment' => $row['note_comment']
        ];
    }
}


echo json_encode($contacts);

?>
