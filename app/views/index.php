<?php

require_once APPROOT . "/views/inc/header.php";
?>
<body class="home">
    <a href="./" class="logo">
        <img src="./assets/logo.svg" alt="Vote">
    </a>
    <div class="btn-group">
        <a href="<?=URLROOT;?>/view" class="btn primary">View Results</a>
        <a href="<?=URLROOT;?>/add_edit" class="btn primary">Add/Edit Results</a>
        <a href="<?=URLROOT;?>/total" class="btn primary">Total Results</a>
    </div>
</body>
</html>