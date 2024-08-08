<?php

require_once APPROOT . "/views/inc/header.php";
?>

<body>
    <?php require_once APPROOT . "/views/inc/nav.php"; ?>
    <main>
        <div class="container">
            <div class="input-group">

                <div class="input">
                    <label for="">LGA</label>
                    <select id="lgaSelect" >
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
                    <select id="pollingUnitSelect" >
                        <option value="">Select Polling Unit</option>
                    </select>

                </div>
            </div>
            <div class="btn-group">
                <a href="#" onclick="fetchResults(event)" class="btn primary small">Load Result</a>
            </div>
        </div>
        <div class="loading"> <span class="g_icons" style="font-size: inherit;">hourglass_bottom</span>Loading..</div>
        <div class="container" id="resultsContainer">
        </div>
    </main>
</body>

</html>