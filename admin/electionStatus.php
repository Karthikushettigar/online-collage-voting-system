<!DOCTYPE HTML>
<html lang="en">

<head>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</head>

<body>
    <?php
    require '../common/connect.php';

    define('ENCRYPTION_KEY', getenv('ENCRYPTION_KEY'));

    if ($_SESSION['id'] == 'admin') {
        $query = "SELECT voteStatus FROM `login` WHERE id='admin'";
        $result = mysqli_query($conn, $query);
        while ($admin = mysqli_fetch_assoc($result)) {
            // Start Voting
            if($admin['voteStatus']==0){
    ?>
        <form action="../common/voteActions.php" method="post" class="w-100 h-100 text-center my-5">
            <button type="button" class="btn btn-success"data-bs-toggle="modal" data-bs-target="#startElection">Start Election</button>

            <div class="modal fade" id="startElection" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="startElectionLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="startElectionLabel">Confirm Election Start</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to start the election?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" name="startElection">Start Election</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php
        }
        else if($admin['voteStatus']!=0){
            // Stop Voting
            if($admin['voteStatus']==1){
    ?>
        <form action="../common/voteActions.php" method="post" class="w-100 text-center my-5">
            <button type="button" class="btn btn-danger"data-bs-toggle="modal" data-bs-target="#stopElection">Stop Election</button>

            <div class="modal fade" id="stopElection" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="stopElectionLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="stopElectionLabel">Confirm Election Stop</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure to stop the election?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger" name="stopElection">Stop Election</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php
            }
            // Declare Voting Results
            else if($admin['voteStatus']==2){
    ?>
        <form action="../common/voteActions.php" method="post" class="w-100 text-center my-5">
            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#declareResults">Declare Results</button>

            <div class="modal fade" id="declareResults" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="declareResultsLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="declareResultsLabel">Confirm Declare Results</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            All votes will be counted and results will be displayed!
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-info" name="declareResults">Declare Results</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php
            }
        // See Voting Results
        else if($admin['voteStatus']==3){
    ?>
        <form action="../common/voteActions.php" method="post" class="w-100 text-center my-5">
            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#newElection">Start New Election</button>

            <div class="modal fade" id="newElection" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newElectionLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="newElectionLabel">New Election Confirm</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            All the previous election data will be deleted and set up for new elections.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-info" name="newElection">Start New Election</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php
            $query = "SELECT post, id, name, voteCount, pfp FROM candidates
                      WHERE (post, voteCount) IN (
                          SELECT post, MAX(voteCount) AS max_votes FROM candidates
                          GROUP BY post
                      )         
                        ORDER BY CASE post
                            WHEN 'President' THEN 1
                            WHEN 'Vice President' THEN 2
                            WHEN 'General Secretary' THEN 3
                            WHEN 'Joint Secretary' THEN 4
                            WHEN 'Sports Secretary' THEN 5
                            WHEN 'Cultural Secretary' THEN 6
                            ELSE 7 END";
            
            $result = mysqli_query($conn, $query);
    ?>
        <div class="container">
            <h1 class="text-center">Final Results</h1>
            <div class='card-body d-flex justify-content-evenly row'>
            <?php
            if ($result) {
                // Fetch and display results
                while ($row = mysqli_fetch_assoc($result)) {
                    $post = $row['post'];
                    $candidateId = $row['id'];
                    $candidateImage = $row['pfp'];
                    $candidateName = $row['name'];
                    
                    // Decrypt the vote count
                    $encrypted_data = base64_decode($row['voteCount']);
                    $parts = explode('::', $encrypted_data);

                    if(count($parts) === 2) {
                        list($encryptedVoteCount, $iv) = $parts;
                        $decryptedVoteCount = openssl_decrypt($encryptedVoteCount, 'aes-256-cbc', ENCRYPTION_KEY, 0, $iv);
                    } else {
                        $decryptedVoteCount = 'Error: Invalid encryption data';
                    }
            ?>
                <div class='card col-md-3 col-sm-6 col-12' style="width: 16rem;">
                    <img src="<?=$candidateImage?>" class="h-50 card-img-top" alt="<?=$candidateImage?>">
                    <div class="card-body">
                        <h3 class="card-title"><?=$candidateName?></h3>
                        <h5 class="card-text"><?=$post?></h5>
                        <h3 class="card-title"><?=$decryptedVoteCount?></h3>
                    </div>
                </div>
            <?php } }?>
            </div>
        </div>
    <?php
        }
        echo "<h1 class='text-center mt-5'>Election Stats</h1>";
        function displayChart($position, $postID, $conn)
        {
            $voteChart = array();
            $count = 0;
            $vote = "SELECT * FROM candidates WHERE status='Accepted' AND post='$position'";
            $result = mysqli_query($conn, $vote);

            while ($row = mysqli_fetch_assoc($result)) {
                // Decrypt the vote count
                $encrypted_data = base64_decode($row['voteCount']);
                $parts = explode('::', $encrypted_data);

                if(count($parts) === 2) {
                    list($encryptedVoteCount, $iv) = $parts;
                    $decryptedVoteCount = openssl_decrypt($encryptedVoteCount, 'aes-256-cbc', ENCRYPTION_KEY, 0, $iv);
                } else {
                    $decryptedVoteCount = 'Error: Invalid encryption data';
                }

                $voteChart[$count]["label"] = $row["name"];
                $voteChart[$count]["y"] = (int)$decryptedVoteCount;
                $count++;
            }
    ?>

            <div id="chartContainer<?= $postID ?>" style="height: 500px; width: 100%;"></div>
            <script>
                var chart<?= $postID ?> = new CanvasJS.Chart("chartContainer<?= $postID ?>", {
                    animationEnabled: true,
                    theme: "light2",
                    title: {
                        text: "<?= $position ?>"
                    },
                    axisY: {
                        title: "Vote Count"
                    },
                    data: [{
                        type: "column",
                        yValueFormatString: "#,##0 votes",
                        dataPoints: <?php echo json_encode($voteChart, JSON_NUMERIC_CHECK); ?>
                    }]
                });
                chart<?= $postID ?>.render();
            </script>

    <?php
        }

        displayChart("President", "President", $conn);
        displayChart("Vice President", "Vice", $conn);
        displayChart("General Secretary", "General", $conn);
        displayChart("Joint Secretary", "Joint", $conn);
        displayChart("Sports Secretary", "Sports", $conn);
        displayChart("Cultural Secretary", "Cultural", $conn);

        }
    }

    } else {
        header("Location:../login.php");
        exit();
    }
    ?>
</body>

</html>
