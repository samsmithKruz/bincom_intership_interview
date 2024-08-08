<?php

require_once APPROOT . "/views/inc/header.php";
?>

<body>
    <?php require_once APPROOT . "/views/inc/nav.php"; ?>
    <main>
        <div class="container">
            <h1>Vote Collation</h1>
            <div class="input-group">
                <div class="input">
                    <label for="">LGA</label>
                    <select id="lgaSelect">
                        <option selected disabled>Select LGA</option>
                    </select>
                </div>
                <div class="input">
                    <label for="">Ward</label>
                    <select id="wardSelect" onchange="fetchPollingUnits()">
                        <option value="">Select Ward</option>
                    </select>
                </div>
                <div class="input">
                    <label for="">Polling Unit</label>
                    <select id="pollingUnitSelect" onchange="fetchParty()">
                        <option value="">Select Polling Unit</option>
                    </select>
                </div>
                <div class="input">
                    <label for="">Party</label>
                    <select id="partySelect" onchange="fetchVote()">
                        <option value="">Select Party</option>
                    </select>
                </div>
                <div class="input">
                    <label for="">Vote Count</label>
                    <input type="number" id="vote" name="vote" min="0" placeholder="Enter votes">
                </div>
            </div>
            <div class="btn-group">
                <a href="#" onclick="collate(event)" class="btn primary small">Load Result</a>
            </div>
        </div>
        <div class="loading"> <span class="g_icons" style="font-size: inherit;">hourglass_bottom</span>Loading..</div>
        <div class="container" id="resultsContainer">
        </div>
    </main>
</body>

</html>