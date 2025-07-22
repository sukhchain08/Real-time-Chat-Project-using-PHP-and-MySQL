<?php
    include '../include/privacy.php';
    include '../include/connection.php';
    include '../include/links.php';
    include '../include/search_user.php';

    $number = $_SESSION['number'];
    $query = mysqli_query($conn, "SELECT * FROM user WHERE number = '$number'");
    $result = mysqli_fetch_assoc($query);

    $search_value = "";
    if (isset($_GET['search_friend'])) {
        $search_value = mysqli_real_escape_string($conn, $_GET['search_friend']);
    }
    if (!empty($search_value))
    {
        $all_users_query = mysqli_query($conn, "SELECT * FROM user WHERE number != '$number' AND name LIKE '%$search_value%'");
    }
    else
    {
        $all_users_query = mysqli_query($conn, "SELECT * FROM user WHERE number != '$number'");
    }
    $all_user_row = mysqli_num_rows($all_users_query);

    
    $friend = $_GET['user'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <?php include '../include/links.php'; ?>
</head>
<body class="px-2 py-1 justify-content-md-center align-items-md-center vh-100 message-body">
    <section class="container-fluid col-12 col-md-4 message-container">
        <?php
            if (trim($friend) === '')
            {
                echo
                "
                <div class='chat-header'>
                    <span class='d-flex'>
                        <h4 class='col-6 fw-semibold pb-2'>Messages</h4>
                        <span class='col-6 d-flex align-items-center justify-content-end'>
                            <a href='dashboard.php' class='fw-semibold mb-2 text-decoration-none' style='height:22px;'>Go Back</a>
                        </span>
                    </span>
                    <form method='GET' class='d-flex col-12'>
                        <input type='search' class='form-control me-2' value='$search_value' name='search_friend' placeholder='Search Friend' autocomplete='off'>
                        <button type='submit' class='btn btn-success'><i class='fa-solid fa-search'></i></button>
                    </form>
                </div> <hr>";

                if($all_user_row != 0)
                {
                    while($all_user = mysqli_fetch_assoc($all_users_query))
                    {
                        $imagePath = '../'.$all_user['image'];

                        $friend_query = mysqli_query($conn, "SELECT * FROM `add_requests` WHERE ((req_from = $number AND req_to = {$all_user['number']} AND is_friend = 'yes') OR (req_from = {$all_user['number']} AND req_to = $number AND is_friend = 'yes'))");
                        $friend_result = mysqli_fetch_assoc($friend_query);

                        if(mysqli_num_rows($friend_query) != 0)
                        {
                            echo
                            "
                            <div class='d-flex col-12'>
                                <span class='col-2 d-flex'>
                                    <img src='".$imagePath."' width='50px' height='50px' style='border-radius: 50%;'>
                                </span>
                                <span class='col-6 fw-semibold ms-3 ms-md-0' style='font-size: 18px;'>".htmlspecialchars($all_user['name'])." <br>";
                                    if($all_user['gender'] == '')
                                    {
                                        echo "-";
                                    }
                                    else
                                    {
                                        echo
                                        "
                                            <p class='fw-normal' style='font-size: 17px;'>".$all_user['gender']."</p>
                                        ";
                                    }
                                    echo "
                                </span>
                                <span class='col-4 d-flex align-items-center justify-content-end'>
                                    <a href='message.php?user=".htmlspecialchars($all_user['number'])."' class='btn btn-primary text-decoration-none fw-semibold me-2'>
                                        <p class='d-none d-md-inline'>Message</p> 
                                        <i class='fa-solid fa-comment-dots ms-md-2'></i>
                                    </a>
                                </span>
                            </div> <hr>
                            ";
                        }
                    }
                }
                else
                {
                    echo
                    "
                        <h5 class='col-12 mt-2 px-3'>User Not Found</h5>
                        <a href='dashboard.php' class='fw-semibold px-3'>Go Back</a>
                    ";
                }
            }
            else {
                $friend_detail_query = mysqli_query($conn, "SELECT * FROM user WHERE number = '$friend'");
                $friend_detail = mysqli_fetch_assoc($friend_detail_query);
                $friend_img = '../' . $friend_detail['image'];

                echo 
                "
                    <div class='chat-header d-flex col-12'>
                        <span class='col-2 d-flex align-items-center'>
                            <img src='" . $friend_img . "' width='50px' height='50px' style='border-radius: 50%;'>
                        </span>
                        <span class='col-7 fw-bold ms-3 ms-md-0' style='position: relative; top: 7px; font-size: 19px;'>" . htmlspecialchars($friend_detail['name']) . "<br>";
                        if ($friend_detail['status'] != '') 
                        {
                            echo "<p class='fw-semibold' style='font-size: 16px; color: green'>" . $friend_detail['status'] . "</p>";
                        }
                        echo "
                        </span>
                        <span style='position: relative; top: 15px; left: 9%;' class='text-nowrap mess-goback'>
                            <a href='message.php' class='text-decoration-none fw-semibold'>Go Back</a>
                        </span>
                    </div>
                ";

                echo "<div class='chat-box' id='chat-box'>";
                    
                echo "</div>"; 

                echo 
                "
                    <form id='messageForm' class='message-input-form'>
                        <input type='hidden' id='receiver' value='" . htmlspecialchars($friend_detail['number']) . "'>
                        <input type='text' id='messageInput' autocomplete='off' placeholder='Type a message...' required
                            class='form-control' style='flex: 1; border-radius: 20px; margin-right: 10px;'>
                        <button type='submit' class='btn btn-success' style='border-radius: 20px;'><i class='fa-solid fa-paper-plane'></i> Send</button>
                    </form>

                    <script>
                        function fetchMessages() {
                            const receiver = document.getElementById('receiver').value;
                            const xhr = new XMLHttpRequest();
                            xhr.open('GET', 'get_messages.php?receiver=' + encodeURIComponent(receiver), true);

                            xhr.onload = function () {
                                if (xhr.status === 200) {
                                    const chatBox = document.getElementById('chat-box');
                                    const isAtBottom = chatBox.scrollTop + chatBox.clientHeight >= chatBox.scrollHeight - 5;
                                    const previousScrollHeight = chatBox.scrollHeight;
                                    chatBox.innerHTML = xhr.responseText;
                                    if (isAtBottom) 
                                    {
                                        chatBox.scrollTop = chatBox.scrollHeight;
                                    } 
                                    else 
                                    {
                                        const newScrollHeight = chatBox.scrollHeight;
                                        chatBox.scrollTop = chatBox.scrollTop + (newScrollHeight - previousScrollHeight);
                                    }
                                }
                            };

                            xhr.send();
                        }

                        fetchMessages(); 
                        setInterval(fetchMessages, 500); // Every 2 seconds

                        document.getElementById('messageForm').addEventListener('submit', function (e) {
                            e.preventDefault();
                            const messageInput = document.getElementById('messageInput');
                            const message = messageInput.value.trim();
                            const receiver = document.getElementById('receiver').value;

                            if (message !== '') {
                                const xhr = new XMLHttpRequest();
                                xhr.open('POST', 'send_message.php', true);
                                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                                xhr.onload = function () {
                                    messageInput.value = '';
                                    fetchMessages();

                                    const chatBox = document.getElementById('chat-box');
                                    setTimeout(() => {
                                        chatBox.scrollTop = chatBox.scrollHeight;
                                    }, 100); 
                                };

                                xhr.send(
                                    'message=' + encodeURIComponent(message) +
                                    '&receiver=' + encodeURIComponent(receiver)
                                );
                            }
                        });
                    </script>
                ";
            }
        ?>
    </section>
</body>
</html>