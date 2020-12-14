<?php
echo "Привет";
$result_items="Привет";
?>
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/qs/6.9.4/qs.min.js"
    integrity="sha512-BHtomM5XDcUy7tDNcrcX1Eh0RogdWiMdXl3wJcKB3PFekXb3l5aDzymaTher61u6vEZySnoC/SAj2Y/p918Y3w=="
    crossorigin="anonymous"
></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script type="module">
    // 📁 main.js
    import { main } from "./src/script.js";
    // CITY: "1377",
    // POSITION_ID: "rep",
    // NEEDED_SUBFIELD: "extra",
    // // format YYYY-MM-DD
    // START_DATE: "2020-11-01",
    // END_DATE: "2020-11-13",
    const city = 1377;
    const positionID = "rep";
    const neededSubfield = "spif";
    const startDate = "2020-11-10";
    const endDate = "2020-11-16";

    draw();

    async function draw() {
        const result = await main(city, positionID, startDate, endDate, neededSubfield);
        //console.log(result);
        log(result);
    }
    async function log(x) {
        const result = x;
        //document.location.href="https://00.kz/b24/amirlan/bitrix/payday.php?u_name=" + result;
        console.log(result);
        var resp ;
        var xmlHttp ;
        var url ;

        url = 'https://00.kz/b24/amirlan/bitrix/payday.php';

        xmlHttp = new XMLHttpRequest();
        xmlHttp.open( "GET", url, false );
        xmlHttp.send( 123 );
        resp = xmlHttp.responseText;
        console.log(xmlHttp);
        var q = <?=json_encode($result_items);?>;
       

    }
</script>

