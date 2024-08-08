<?php

require_once APPROOT . "/views/inc/header.php";
?>

<body>
    <?php require_once APPROOT . "/views/inc/nav.php"; ?>
    <main>
        <div class="container">
            <h1>Collated Votes</h1>
            <div class="input-group">
                <div class="input">
                    <label for="">LGA</label>
                    <select id="lgaSelect" class="total">
                        <option selected disabled>Select LGA</option>
                    </select>
                </div>
            </div>
            <div class="btn-group">
                <a href="#" style="flex: 0 0 auto;" onclick="fetchTotal(event)" class="btn primary small">Load Result</a>
            </div>
        </div>
        <div class="loading"> <span class="g_icons" style="font-size: inherit;">hourglass_bottom</span>Loading..</div>
        <div class="container" id="resultsContainer">
        </div>
    </main>
</body>

</html>