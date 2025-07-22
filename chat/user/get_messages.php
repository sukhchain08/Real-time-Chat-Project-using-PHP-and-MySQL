<?php
    include '../include/connection.php'; // Include your database connection

    session_start(); // Start session to access $_SESSION['number']

    $number = $_SESSION['number'];
    $receiver = $_GET['receiver'] ?? '';

    if (!empty($receiver)) {
        $message_query = mysqli_query($conn, "SELECT * FROM `messages` WHERE (sender = $number AND receiver = $receiver) OR (sender = $receiver AND receiver = $number)");
        $message_query_row = mysqli_num_rows($message_query);
        if($message_query_row != 0)
        {
            while ($row = mysqli_fetch_assoc($message_query))
            {
                $isSender = ($row['sender'] == $number);

                if ($isSender)
                {
                    echo 
                    "
                        <div class='d-flex justify-content-end'>
                            <span class='message-bubble sender-message'>
                                " . htmlspecialchars($row['message']) . "
                            </span>
                        </div>
                    ";
                }
                else
                {
                    echo 
                    "
                        <div class='d-flex justify-content-start'>
                            <span class='message-bubble receiver-message'>
                                " . htmlspecialchars($row['message']) . "
                            </span>
                        </div>
                    ";
                }
            }
        }
        else
        {
            echo "<p class='fw-semibold fs-3 text-danger'>No Message Found</p>";
        }
    }
?>