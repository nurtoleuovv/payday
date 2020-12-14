<!DOCTYPE html>

<html>
<head>



  <script
          src="https://cdnjs.cloudflare.com/ajax/libs/qs/6.9.4/qs.min.js"
          integrity="sha512-BHtomM5XDcUy7tDNcrcX1Eh0RogdWiMdXl3wJcKB3PFekXb3l5aDzymaTher61u6vEZySnoC/SAj2Y/p918Y3w=="
          crossorigin="anonymous"
  ></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <meta charset="utf-8">
  <title>Расчет Pay Day</title>
</head>
<body>
<form id="myForm">
  <p>
    <label>Дата от </label>
    <input type="date" id="date_from">
  </p>
  <p>
    <label>Дата до: </label>
    <input type="date" id="date_to">
  </p>
  <p><label>Регион: </label>
    <select size="1" id="region">
      <option value="539">Алматы</option>
      <option value="1377">Астана</option>
    </select></p>
  <p>
  <p><label>Должность: </label>
    <select size="1" id="dolzhnost" onchange="neededSub()">
      <option value="null">Выберите должность</option>
      <option value="agent">Агенты</option>
      <option value="operator">Операторы</option>
      <option value="rep">РЭПы</option>
      <option value="manager">Менеджеры</option>
      <option value="podium">Подиум-мейкер</option>
      <option value="MM">Руководитель маркетинга</option>
      <option value="CCM">Руководитель КЦ</option>
      <option value="SM">Руководитель ОП</option>
      <option value="director">Директор по продажам</option>
    </select></p>
  <p><label>Тип отчета: </label>
    <select size="1" id="neededSubfield">
        <option value="null">Выберите отчет</option>
        <option id="gross" value="gross" style="display: none">Гросс</option>
        <option id="grossMonth" value="grossMonth" style="display: none">Гросс за месяц</option>
        <option id="spif" value="spif" style="display: none">Спифф</option>
        <option id="bonus" value="bonus" style="display: none">Бонусы</option>
        <option id="extra" value="extra" style="display: none">Экстра бонусы</option>

    </select></p>
  <div>
    <button type="button" class="button" id="hello" onclick="otchet()">Сформировать отчет</button>
  </div>
  <p>
    <div id="otchet">

    </div>
  </p>
</form>

</body>
</html>
<script type="module">
  // 📁 main.js
  import { main } from "./src/script.js";
  // CITY: "1377",
  // POSITION_ID: "rep",
  // NEEDED_SUBFIELD: "extra",
  // // format YYYY-MM-DD
  // START_DATE: "2020-11-01",
  // END_DATE: "2020-11-13",
  // const city = 1377;
  // const positionID = "rep";
  // const neededSubfield = "spif";
  // const startDate = "2020-11-10";
  // const endDate = "2020-11-16";


  window.otchet = async function () {
   // e.preventDefault();
    const city = document.getElementById('region').value;
    const positionID = document.getElementById('dolzhnost').value;
    const neededSubfield = document.getElementById('neededSubfield').value;
    var startDate = document.getElementById('date_from').value;
    var endDate = document.getElementById('date_to').value;

      // const positionID = 'agent';
      // const neededSubfield = 'gross';
      // const startDate = '2020-11-10';
      // const endDate = '2020-11-23';
    console.log(city);
    console.log(positionID);
    console.log(neededSubfield);
    console.log(startDate);
    console.log(endDate);
    var result;
    if (city==539){
        var city_name="Алматы";
    }
      if (city==1377){
          var city_name="Астана";
      }
      if (positionID=='rep'&&neededSubfield=='spif'){
          startDate = new Date (document.getElementById('date_from').value);
          endDate = new Date (document.getElementById('date_to').value);
          endDate.setDate(startDate.getDate()+6);
          startDate=formatDate(startDate);
          endDate=formatDate(endDate);
          await draw();
          startDate = new Date (document.getElementById('date_from').value);
          endDate = new Date (document.getElementById('date_to').value);
          startDate.setDate(endDate.getDate()-6);
          startDate=formatDate(startDate);
          endDate=formatDate(endDate);
          await draw();
      } else {
          draw();
      }
    async function draw() {
        const result = await main(city, positionID, startDate, endDate, neededSubfield);
        var key = Object.keys(result)
        console.log(result);
        if (positionID=='agent'){

            document.getElementById('otchet').innerHTML = "";
            if (key=='gross')
            {
                var data = result.gross;
                var table = document.createElement('div');
                var tableHTML='';
                tableHTML+='<table border="1px" style="border-collapse: collapse">';
                tableHTML+='<tr ><th colspan=\'17\'>Гросс по Агентам с '+startDate+' по '+ endDate+' | Регион '+city_name+' </th></tr>';
                tableHTML+='<tr ><th colspan=\'11\'>Данные</th><th  colspan=\'3\'>Расчет</th><th  colspan=\'3\'>Приход</th></tr>';
                tableHTML+='<tr ><th >№</th><th >ФИО</th><th width="50px">Номер сотрудника</th><th width="50px">Тип договора</th><th width="auto" >Место заполнения</th><th width="50px">Количество до обеда</th><th width="100px" >Сумма до обеда</th><th width="50px" >Количество после обеда</th><th width="100px" >Сумма после обеда</th><th width="50px" >Общее количество</th><th width="50px" >Общая сумма</th><th width="100px" >Сумма до обеда</th><th width="100px" >Сумма после обеда</th><th width="50px">Общая сумма</th><th width="100px">Сумма до обеда</th><th width="100px">Сумма после обеда</th><th width="50px">Общая сумма</th></tr>';
                var i=1;
                var afterLunchCountTotal = 0;
                var afterLunchSumTotal = 0;
                var beforeLunchCountTotal = 0;
                var beforeLunchSumTotal = 0;
                var calculatedAfterLunchSumTotal = 0;
                var calculatedBeforeLunchSumTotal = 0;
                var calculatedPrihodAfterLunchSumTotal = 0;
                var calculatedPrihodBeforeLunchSumTotal = 0;
                var calculatedPrihodSumTotal = 0;
                var calculatedSumTotal = 0;
                var contractTypeTotal = 0;
                var elementIDTotal = 0;
                var filledPlaceTotal = 0;
                var fullNameTotal = 0;
                var totalCountTotal = 0;
                var totalSumTotal = 0;
                data.sort((a,b)=>a.ID>b.ID ? 1 : -1);
                for (var n=0;n<data.length;n++)
                {
                    tableHTML+='<tr><td align="center">'+i+'</td><td>'+data[n].fullName+'</td><td>'+data[n].ID+'</td><td>'+data[n].contractType+'</td><td>'+data[n].filledPlace+'</td><td align="center">'+data[n].beforeLunchCount+'</td><td align="center">'+new Intl.NumberFormat().format(data[n].beforeLunchSum)+'</td><td align="center">'+new Intl.NumberFormat().format(data[n].afterLunchCount)+'</td><td align="center">'+new Intl.NumberFormat().format(data[n].afterLunchSum)+'</td><td align="center">'+new Intl.NumberFormat().format(data[n].totalCount)+'</td><td align="center">'+new Intl.NumberFormat().format(data[n].totalSum)+'</td><td align="center">'+new Intl.NumberFormat().format(round(data[n].calculatedBeforeLunchSum,2))+'</td><td align="center">'+new Intl.NumberFormat().format(round(data[n].calculatedAfterLunchSum,2))+'</td><td align="center">'+new Intl.NumberFormat().format(round(data[n].calculatedSum,2))+'</td><td align="center">'+new Intl.NumberFormat().format(data[n].calculatedPrihodBeforeLunchSum)+'</td><td align="center">'+new Intl.NumberFormat().format(data[n].calculatedPrihodAfterLunchSum)+'</td><td align="center">'+new Intl.NumberFormat().format(data[n].calculatedPrihodSum)+'</td></tr>';
                    afterLunchCountTotal=afterLunchCountTotal+data[n].afterLunchCount;
                    afterLunchSumTotal=afterLunchSumTotal+data[n].afterLunchSum;
                    beforeLunchCountTotal=beforeLunchCountTotal+data[n].beforeLunchCount;
                    beforeLunchSumTotal=beforeLunchSumTotal+data[n].beforeLunchSum;
                    calculatedAfterLunchSumTotal=calculatedAfterLunchSumTotal+data[n].calculatedAfterLunchSum;
                    calculatedBeforeLunchSumTotal=calculatedBeforeLunchSumTotal+data[n].calculatedBeforeLunchSum;
                    calculatedSumTotal=calculatedSumTotal+data[n].calculatedSum;
                    calculatedPrihodAfterLunchSumTotal=calculatedPrihodAfterLunchSumTotal+data[n].calculatedPrihodAfterLunchSum;
                    calculatedPrihodBeforeLunchSumTotal=calculatedPrihodBeforeLunchSumTotal+data[n].calculatedPrihodBeforeLunchSum;
                    calculatedPrihodSumTotal=calculatedPrihodSumTotal+data[n].calculatedPrihodSum;
                    contractTypeTotal=contractTypeTotal+data[n].contractType;
                    elementIDTotal=elementIDTotal+data[n].elementID;
                    filledPlaceTotal=filledPlaceTotal+data[n].filledPlace;
                    fullNameTotal=fullNameTotal+data[n].fullName;
                    totalCountTotal=totalCountTotal+data[n].totalCount;
                    totalSumTotal=totalSumTotal+data[n].totalSum;
                    i++;
                }
                console.log(calculatedAfterLunchSumTotal);
                tableHTML+='<tr><th colspan="5">Итого</th><th>'+new Intl.NumberFormat().format(beforeLunchCountTotal)+'</th><th>'+new Intl.NumberFormat().format(beforeLunchSumTotal)+'</th><th>'+new Intl.NumberFormat().format(afterLunchCountTotal)+'</th><th>'+new Intl.NumberFormat().format(afterLunchSumTotal)+'</th><th>'+new Intl.NumberFormat().format(totalCountTotal)+'</th><th>'+new Intl.NumberFormat().format(totalSumTotal)+'</th><th >'+new Intl.NumberFormat().format(round(calculatedBeforeLunchSumTotal,2)) +'</th><th >'+new Intl.NumberFormat().format(round(calculatedAfterLunchSumTotal,2))+'</th><th >'+new Intl.NumberFormat().format(round(calculatedSumTotal,2))+'</th><th >'+new Intl.NumberFormat().format(calculatedPrihodBeforeLunchSumTotal)+'</th><th >'+new Intl.NumberFormat().format(calculatedPrihodAfterLunchSumTotal)+'</th><th >'+new Intl.NumberFormat().format(calculatedPrihodSumTotal)+'</th></tr>';
                tableHTML+='</table>';
                table.innerHTML = tableHTML;
                document.getElementById('otchet').appendChild(table);
            }
            if (key=='bonus')
            {
                var data = result.bonus;
                console.log(data);
                var table = document.createElement('div');
                var tableHTML='';
                tableHTML+='<table border="1px" style="border-collapse: collapse">';
                tableHTML+='<tr ><th colspan=\'6\'>Бонсуы по Агентам с '+startDate+' по '+ endDate+' | Регион '+city_name+' </th></tr>';
                tableHTML+='<tr ><th colspan=\'5\'>Данные</th><th  colspan=\'2\'>Расчет</th></tr>';
                tableHTML+='<tr ><th >№</th><th >ФИО</th><th width="50px">Номер сотрудника</th><th width="100px">Общее количество</th><th width="80px">Общая сумма</th><th width="50px">Бонус</th></tr>';
                var i=1;
                var totalSumTotal=0;
                var totalCountTotal=0;
                var totalBonusTotal=0;
                //data.sort((a,b)=>a.ID>b.ID ? 1 : -1);
                for (var n=0;n<data.length;n++)
                {
                    if (n==0) {
                        tableHTML += '<tr bgcolor=\'#FFD700\'><td align="center">' + i + '</td><td>' + data[n].fullName + '</td><td>' + data[n].ID + '</td><td>' + new Intl.NumberFormat().format(data[n].totalCount) + '</td><td align="center">' + new Intl.NumberFormat().format(data[n].totalSum) + '</td><td align="center">' + new Intl.NumberFormat().format(data[n].bonus) + '</td></tr>';
                    }
                    if (n==1) {
                        tableHTML += '<tr bgcolor=\'#C0C0C0\'><td align="center">' + i + '</td><td>' + data[n].fullName + '</td><td>' + data[n].ID + '</td><td>' + new Intl.NumberFormat().format(data[n].totalCount) + '</td><td align="center">' + new Intl.NumberFormat().format(data[n].totalSum) + '</td><td align="center">' + new Intl.NumberFormat().format(data[n].bonus) + '</td></tr>';
                    }
                    if (n==2) {
                        tableHTML += '<tr bgcolor=\'#DAA520\'><td align="center">' + i + '</td><td>' + data[n].fullName + '</td><td>' + data[n].ID + '</td><td>' + new Intl.NumberFormat().format(data[n].totalCount) + '</td><td align="center">' + new Intl.NumberFormat().format(data[n].totalSum) + '</td><td align="center">' + new Intl.NumberFormat().format(data[n].bonus) + '</td></tr>';
                    }
                    totalCountTotal=totalCountTotal+data[n].totalCount;
                    totalSumTotal=totalSumTotal+data[n].totalSum;
                    totalBonusTotal=totalBonusTotal+data[n].bonus;
                    i++;
                }
                console.log(calculatedAfterLunchSumTotal);
                tableHTML+='<tr><th colspan="3">Итого</th><th>'+new Intl.NumberFormat().format(totalCountTotal)+'</th><th>'+new Intl.NumberFormat().format(totalSumTotal)+'</th><th>'+new Intl.NumberFormat().format(totalBonusTotal)+'</th></tr>';
                tableHTML+='</table>';
                table.innerHTML = tableHTML;
                document.getElementById('otchet').appendChild(table);
            }
        }

        if (positionID=='operator') {

            document.getElementById('otchet').innerHTML = "";
            if (key == 'gross')
            {
                var data = result.gross;
                var table = document.createElement('div');
                var tableHTML = '';
                tableHTML += '<table border="1px" style="border-collapse: collapse">';
                tableHTML += '<tr ><th colspan=\'13\'>Гросс по Операторам с ' + startDate + ' по ' + endDate + ' | Регион ' + city_name + ' </th></tr>';
                tableHTML += '<tr ><th colspan=\'10\'>Данные</th><th  colspan=\'3\'>Расчет</th></tr>';
                tableHTML += '<tr ><th >№</th><th >ФИО</th><th width="50px">Номер сотрудника</th><th width="50px">Тип договора</th><th width="50px">Количество до обеда</th><th width="100px" >Сумма до обеда</th><th width="50px" >Количество после обеда</th><th width="100px" >Сумма после обеда</th><th width="50px" >Общее количество</th><th width="50px" >Общая сумма</th><th width="100px" >Сумма до обеда</th><th width="100px" >Сумма после обеда</th><th width="50px">Общая сумма</th></tr>';
                var i = 1;
                var afterLunchCountTotal = 0;
                var afterLunchSumTotal = 0;
                var beforeLunchCountTotal = 0;
                var beforeLunchSumTotal = 0;
                var calculatedAfterLunchSumTotal = 0;
                var calculatedBeforeLunchSumTotal = 0;
                var calculatedPrihodAfterLunchSumTotal = 0;
                var calculatedPrihodBeforeLunchSumTotal = 0;
                var calculatedPrihodSumTotal = 0;
                var calculatedSumTotal = 0;
                var contractTypeTotal = 0;
                var elementIDTotal = 0;
                var filledPlaceTotal = 0;
                var fullNameTotal = 0;
                var totalCountTotal = 0;
                var totalSumTotal = 0;
                data.sort((a, b) => a.ID > b.ID ? 1 : -1);
                for (var n = 0; n < data.length; n++) {
                    tableHTML += '<tr><td align="center">' + i + '</td><td>' + data[n].fullName + '</td><td>' + data[n].ID + '</td><td>' + data[n].contractType + '</td><td align="center">' + data[n].beforeLunchCount + '</td><td align="center">' + new Intl.NumberFormat().format(data[n].beforeLunchSum) + '</td><td align="center">' + new Intl.NumberFormat().format(data[n].afterLunchCount) + '</td><td align="center">' + new Intl.NumberFormat().format(data[n].afterLunchSum) + '</td><td align="center">' + new Intl.NumberFormat().format(data[n].totalCount) + '</td><td align="center">' + new Intl.NumberFormat().format(data[n].totalSum) + '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedBeforeLunchSum, 2)) + '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedAfterLunchSum, 2)) + '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedSum, 2)) + '</td></tr>';
                    afterLunchCountTotal = afterLunchCountTotal + data[n].afterLunchCount;
                    afterLunchSumTotal = afterLunchSumTotal + data[n].afterLunchSum;
                    beforeLunchCountTotal = beforeLunchCountTotal + data[n].beforeLunchCount;
                    beforeLunchSumTotal = beforeLunchSumTotal + data[n].beforeLunchSum;
                    calculatedAfterLunchSumTotal = calculatedAfterLunchSumTotal + data[n].calculatedAfterLunchSum;
                    calculatedBeforeLunchSumTotal = calculatedBeforeLunchSumTotal + data[n].calculatedBeforeLunchSum;
                    calculatedSumTotal = calculatedSumTotal + data[n].calculatedSum;
                    calculatedPrihodAfterLunchSumTotal = calculatedPrihodAfterLunchSumTotal + data[n].calculatedPrihodAfterLunchSum;
                    calculatedPrihodBeforeLunchSumTotal = calculatedPrihodBeforeLunchSumTotal + data[n].calculatedPrihodBeforeLunchSum;
                    calculatedPrihodSumTotal = calculatedPrihodSumTotal + data[n].calculatedPrihodSum;
                    contractTypeTotal = contractTypeTotal + data[n].contractType;
                    elementIDTotal = elementIDTotal + data[n].elementID;
                    filledPlaceTotal = filledPlaceTotal + data[n].filledPlace;
                    fullNameTotal = fullNameTotal + data[n].fullName;
                    totalCountTotal = totalCountTotal + data[n].totalCount;
                    totalSumTotal = totalSumTotal + data[n].totalSum;
                    i++;
                }
                console.log(calculatedAfterLunchSumTotal);
                tableHTML += '<tr><th colspan="4">Итого</th><th>' + new Intl.NumberFormat().format(beforeLunchCountTotal) + '</th><th>' + new Intl.NumberFormat().format(beforeLunchSumTotal) + '</th><th>' + new Intl.NumberFormat().format(afterLunchCountTotal) + '</th><th>' + new Intl.NumberFormat().format(afterLunchSumTotal) + '</th><th>' + new Intl.NumberFormat().format(totalCountTotal) + '</th><th>' + new Intl.NumberFormat().format(totalSumTotal) + '</th><th >' + new Intl.NumberFormat().format(round(calculatedBeforeLunchSumTotal, 2)) + '</th><th >' + new Intl.NumberFormat().format(round(calculatedAfterLunchSumTotal, 2)) + '</th><th >' + new Intl.NumberFormat().format(round(calculatedSumTotal, 2)) + '</th></tr>';
                tableHTML += '</table>';
                table.innerHTML = tableHTML;
                document.getElementById('otchet').appendChild(table);
            }
            if (key == 'spif')
            {
                var data = result.spif;
                var table = document.createElement('div');
                var tableHTML = '';
                tableHTML += '<table border="1px" style="border-collapse: collapse">';
                tableHTML += '<tr ><th colspan=\'13\'>Спиф по Операторам с ' + startDate + ' по ' + endDate + ' | Регион ' + city_name + ' </th></tr>';
                tableHTML += '<tr ><th colspan=\'10\'>Данные</th><th  colspan=\'3\'>Расчет</th></tr>';
                tableHTML += '<tr ><th >№</th><th >ФИО</th><th width="50px">Номер сотрудника</th><th width="50px">Тип договора</th><th width="50px">Количество до обеда</th><th width="100px" >Сумма до обеда</th><th width="50px" >Количество после обеда</th><th width="100px" >Сумма после обеда</th><th width="50px" >Общее количество</th><th width="50px" >Общая сумма</th><th width="100px" >Сумма до обеда</th><th width="100px" >Сумма после обеда</th><th width="50px">Общая сумма</th></tr>';
                var i = 1;
                var afterLunchCountTotal = 0;
                var afterLunchSumTotal = 0;
                var beforeLunchCountTotal = 0;
                var beforeLunchSumTotal = 0;
                var calculatedAfterLunchSumTotal = 0;
                var calculatedBeforeLunchSumTotal = 0;
                var calculatedPrihodAfterLunchSumTotal = 0;
                var calculatedPrihodBeforeLunchSumTotal = 0;
                var calculatedPrihodSumTotal = 0;
                var calculatedSumTotal = 0;
                var contractTypeTotal = 0;
                var elementIDTotal = 0;
                var filledPlaceTotal = 0;
                var fullNameTotal = 0;
                var totalCountTotal = 0;
                var totalSumTotal = 0;
                data.sort((a, b) => a.ID > b.ID ? 1 : -1);
                for (var n = 0; n < data.length; n++) {
                    tableHTML += '<tr><td align="center">' + i + '</td><td>' + data[n].fullName + '</td><td>' + data[n].ID + '</td><td>' + data[n].contractType + '</td><td align="center">' + data[n].beforeLunchCount + '</td><td align="center">' + new Intl.NumberFormat().format(data[n].beforeLunchSum) + '</td><td align="center">' + new Intl.NumberFormat().format(data[n].afterLunchCount) + '</td><td align="center">' + new Intl.NumberFormat().format(data[n].afterLunchSum) + '</td><td align="center">' + new Intl.NumberFormat().format(data[n].totalCount) + '</td><td align="center">' + new Intl.NumberFormat().format(data[n].totalSum) + '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedBeforeLunchSum, 2)) + '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedAfterLunchSum, 2)) + '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedSum, 2)) + '</td></tr>';
                    afterLunchCountTotal = afterLunchCountTotal + data[n].afterLunchCount;
                    afterLunchSumTotal = afterLunchSumTotal + data[n].afterLunchSum;
                    beforeLunchCountTotal = beforeLunchCountTotal + data[n].beforeLunchCount;
                    beforeLunchSumTotal = beforeLunchSumTotal + data[n].beforeLunchSum;
                    calculatedAfterLunchSumTotal = calculatedAfterLunchSumTotal + data[n].calculatedAfterLunchSum;
                    calculatedBeforeLunchSumTotal = calculatedBeforeLunchSumTotal + data[n].calculatedBeforeLunchSum;
                    calculatedSumTotal = calculatedSumTotal + data[n].calculatedSum;
                    calculatedPrihodAfterLunchSumTotal = calculatedPrihodAfterLunchSumTotal + data[n].calculatedPrihodAfterLunchSum;
                    calculatedPrihodBeforeLunchSumTotal = calculatedPrihodBeforeLunchSumTotal + data[n].calculatedPrihodBeforeLunchSum;
                    calculatedPrihodSumTotal = calculatedPrihodSumTotal + data[n].calculatedPrihodSum;
                    contractTypeTotal = contractTypeTotal + data[n].contractType;
                    elementIDTotal = elementIDTotal + data[n].elementID;
                    filledPlaceTotal = filledPlaceTotal + data[n].filledPlace;
                    fullNameTotal = fullNameTotal + data[n].fullName;
                    totalCountTotal = totalCountTotal + data[n].totalCount;
                    totalSumTotal = totalSumTotal + data[n].totalSum;
                    i++;
                }
                console.log(calculatedAfterLunchSumTotal);
                tableHTML += '<tr><th colspan="4">Итого</th><th>' + new Intl.NumberFormat().format(beforeLunchCountTotal) + '</th><th>' + new Intl.NumberFormat().format(beforeLunchSumTotal) + '</th><th>' + new Intl.NumberFormat().format(afterLunchCountTotal) + '</th><th>' + new Intl.NumberFormat().format(afterLunchSumTotal) + '</th><th>' + new Intl.NumberFormat().format(totalCountTotal) + '</th><th>' + new Intl.NumberFormat().format(totalSumTotal) + '</th><th >' + new Intl.NumberFormat().format(round(calculatedBeforeLunchSumTotal, 2)) + '</th><th >' + new Intl.NumberFormat().format(round(calculatedAfterLunchSumTotal, 2)) + '</th><th >' + new Intl.NumberFormat().format(round(calculatedSumTotal, 2)) + '</th></tr>';
                tableHTML += '</table>';
                table.innerHTML = tableHTML;
                document.getElementById('otchet').appendChild(table);
            }
        }

        if (positionID=='rep') {
            if (key == 'gross')
            {
                document.getElementById('otchet').innerHTML = "";
                var data = result.gross;
                var table = document.createElement('div');
                var tableHTML = '';
                tableHTML += '<table border="1px" style="border-collapse: collapse">';
                tableHTML += '<tr ><th colspan=\'14\'>Гросс по РЭП с ' + startDate + ' по ' + endDate + ' | Регион ' + city_name + ' </th></tr>';
                tableHTML += '<tr ><th colspan=\'11\'>Данные</th><th  colspan=\'3\'>Расчет</th></tr>';
                tableHTML += '<tr ><th >№</th><th >ФИО</th><th width="50px">Номер сотрудника</th><th width="50px">Количество до обеда</th><th width="100px" >Сумма до обеда</th><th width="50px" >Количество после обеда</th><th width="100px" >Сумма после обеда</th><th width="50px" >Сумма за 1-ую неделю</th><th width="50px" >Сумма за 2-ую неделю</th><th width="50px">Общее количество</th><th width="50px">Общая сумма</th><th width="100px" >Сумма за 1-ую неделю</th><th width="100px" >Сумма за 2-ую неделю</th><th width="50px">Общая сумма</th></tr>';
                var i = 1;
                var afterLunchCountTotal = 0;
                var afterLunchSumTotal = 0;
                var beforeLunchCountTotal = 0;
                var beforeLunchSumTotal = 0;
                var calculatedFirstWeekSumTotal = 0;
                var calculatedSecondWeekSumTotal = 0;
                var firstWeekSumTotal = 0;
                var secondWeekSumTotal = 0;
                var calculatedPrihodSumTotal = 0;
                var calculatedSumTotal = 0;
                var contractTypeTotal = 0;
                var elementIDTotal = 0;
                var filledPlaceTotal = 0;
                var fullNameTotal = 0;
                var totalCountTotal = 0;
                var totalSumTotal = 0;
                data.sort((a, b) => a.ID > b.ID ? 1 : -1);
                for (var n = 0; n < data.length; n++) {
                    tableHTML += '<tr><td align="center">' + i +
                        '</td><td>' + data[n].fullName +
                        '</td><td>' + data[n].ID +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].beforeLunchCount) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].beforeLunchSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].afterLunchCount) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].afterLunchSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].firstWeekSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].secondWeekSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].totalCount) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].totalSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedFirstWeekSum, 2)) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedSecondWeekSum, 2)) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedSum, 2)) + '</td></tr>';
                    afterLunchCountTotal = afterLunchCountTotal + data[n].afterLunchCount;
                    afterLunchSumTotal = afterLunchSumTotal + data[n].afterLunchSum;
                    beforeLunchCountTotal = beforeLunchCountTotal + data[n].beforeLunchCount;
                    beforeLunchSumTotal = beforeLunchSumTotal + data[n].beforeLunchSum;
                    calculatedFirstWeekSumTotal = calculatedFirstWeekSumTotal + data[n].calculatedFirstWeekSum;
                    calculatedSecondWeekSumTotal = calculatedSecondWeekSumTotal + data[n].calculatedSecondWeekSum;
                    calculatedSumTotal = calculatedSumTotal + data[n].calculatedSum;
                    firstWeekSumTotal = firstWeekSumTotal + data[n].firstWeekSum;
                    secondWeekSumTotal = secondWeekSumTotal + data[n].secondWeekSum;
                    calculatedPrihodSumTotal = calculatedPrihodSumTotal + data[n].calculatedPrihodSum;
                    contractTypeTotal = contractTypeTotal + data[n].contractType;
                    elementIDTotal = elementIDTotal + data[n].elementID;
                    filledPlaceTotal = filledPlaceTotal + data[n].filledPlace;
                    fullNameTotal = fullNameTotal + data[n].fullName;
                    totalCountTotal = totalCountTotal + data[n].totalCount;
                    totalSumTotal = totalSumTotal + data[n].totalSum;
                    i++;
                }
                console.log(calculatedAfterLunchSumTotal);
                tableHTML += '<tr><th colspan="3">Итого</th><th>'
                    + new Intl.NumberFormat().format(beforeLunchCountTotal) +
                    '</th><th>' + new Intl.NumberFormat().format(beforeLunchSumTotal) +
                    '</th><th>' + new Intl.NumberFormat().format(afterLunchCountTotal) +
                    '</th><th>' + new Intl.NumberFormat().format(afterLunchSumTotal) +
                    '</th><th>' + new Intl.NumberFormat().format(firstWeekSumTotal) +
                    '</th><th>' + new Intl.NumberFormat().format(secondWeekSumTotal) +
                    '</th><th>' + new Intl.NumberFormat().format(totalCountTotal) +
                    '</th><th>' + new Intl.NumberFormat().format(totalSumTotal) +
                    '</th><th >' + new Intl.NumberFormat().format(round(calculatedFirstWeekSumTotal, 2)) +
                    '</th><th >' + new Intl.NumberFormat().format(round(calculatedSecondWeekSumTotal, 2)) +
                    '</th><th >' + new Intl.NumberFormat().format(round(calculatedSumTotal, 2)) +
                    '</th></tr>';
                tableHTML += '</table>';
                table.innerHTML = tableHTML;
                document.getElementById('otchet').appendChild(table);
            }
            if (key == 'spif')
            {

                var data = result.spif;
                var table = document.createElement('div');
                var tableHTML = '';
                tableHTML += '<table border="1px" style="border-collapse: collapse">';
                tableHTML += '<tr ><th colspan=\'13\'>Спиф по РЭП с ' + startDate + ' по ' + endDate + ' | Регион ' + city_name + ' </th></tr>';
                tableHTML += '<tr ><th colspan=\'9\'>Данные</th><th  colspan=\'3\'>Расчет</th></tr>';
                tableHTML += '<tr ><th >№</th><th >ФИО</th><th width="50px">Номер сотрудника</th><th width="50px">Количество до обеда</th><th width="100px" >Сумма до обеда</th><th width="50px" >Количество после обеда</th><th width="100px" >Сумма после обеда</th><th width="50px" >Общее количество</th><th width="50px" >Общая сумма</th><th width="100px" >Сумма до обеда</th><th width="100px" >Сумма после обеда</th><th width="50px">Общая сумма</th></tr>';
                var i = 1;
                var afterLunchCountTotal = 0;
                var afterLunchSumTotal = 0;
                var beforeLunchCountTotal = 0;
                var beforeLunchSumTotal = 0;
                var calculatedAfterLunchSumTotal = 0;
                var calculatedBeforeLunchSumTotal = 0;
                var calculatedPrihodAfterLunchSumTotal = 0;
                var calculatedPrihodBeforeLunchSumTotal = 0;
                var calculatedPrihodSumTotal = 0;
                var calculatedSumTotal = 0;
                var contractTypeTotal = 0;
                var elementIDTotal = 0;
                var filledPlaceTotal = 0;
                var fullNameTotal = 0;
                var totalCountTotal = 0;
                var totalSumTotal = 0;
                data.sort((a, b) => a.ID > b.ID ? 1 : -1);
                for (var n = 0; n < data.length; n++) {
                    tableHTML += '<tr><td align="center">' + i + '</td><td>' + data[n].fullName + '</td><td>' + data[n].ID + '</td><td align="center">' + data[n].beforeLunchCount + '</td><td align="center">' + new Intl.NumberFormat().format(data[n].beforeLunchSum) + '</td><td align="center">' + new Intl.NumberFormat().format(data[n].afterLunchCount) + '</td><td align="center">' + new Intl.NumberFormat().format(data[n].afterLunchSum) + '</td><td align="center">' + new Intl.NumberFormat().format(data[n].totalCount) + '</td><td align="center">' + new Intl.NumberFormat().format(data[n].totalSum) + '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedBeforeLunchSum, 2)) + '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedAfterLunchSum, 2)) + '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedSum, 2)) + '</td></tr>';
                    afterLunchCountTotal = afterLunchCountTotal + data[n].afterLunchCount;
                    afterLunchSumTotal = afterLunchSumTotal + data[n].afterLunchSum;
                    beforeLunchCountTotal = beforeLunchCountTotal + data[n].beforeLunchCount;
                    beforeLunchSumTotal = beforeLunchSumTotal + data[n].beforeLunchSum;
                    calculatedAfterLunchSumTotal = calculatedAfterLunchSumTotal + data[n].calculatedAfterLunchSum;
                    calculatedBeforeLunchSumTotal = calculatedBeforeLunchSumTotal + data[n].calculatedBeforeLunchSum;
                    calculatedSumTotal = calculatedSumTotal + data[n].calculatedSum;
                    calculatedPrihodAfterLunchSumTotal = calculatedPrihodAfterLunchSumTotal + data[n].calculatedPrihodAfterLunchSum;
                    calculatedPrihodBeforeLunchSumTotal = calculatedPrihodBeforeLunchSumTotal + data[n].calculatedPrihodBeforeLunchSum;
                    calculatedPrihodSumTotal = calculatedPrihodSumTotal + data[n].calculatedPrihodSum;
                    contractTypeTotal = contractTypeTotal + data[n].contractType;
                    elementIDTotal = elementIDTotal + data[n].elementID;
                    filledPlaceTotal = filledPlaceTotal + data[n].filledPlace;
                    fullNameTotal = fullNameTotal + data[n].fullName;
                    totalCountTotal = totalCountTotal + data[n].totalCount;
                    totalSumTotal = totalSumTotal + data[n].totalSum;
                    i++;
                }
                console.log(calculatedAfterLunchSumTotal);
                tableHTML += '<tr><th colspan="3">Итого</th><th>' + new Intl.NumberFormat().format(beforeLunchCountTotal) + '</th><th>' + new Intl.NumberFormat().format(beforeLunchSumTotal) + '</th><th>' + new Intl.NumberFormat().format(afterLunchCountTotal) + '</th><th>' + new Intl.NumberFormat().format(afterLunchSumTotal) + '</th><th>' + new Intl.NumberFormat().format(totalCountTotal) + '</th><th>' + new Intl.NumberFormat().format(totalSumTotal) + '</th><th >' + new Intl.NumberFormat().format(round(calculatedBeforeLunchSumTotal, 2)) + '</th><th >' + new Intl.NumberFormat().format(round(calculatedAfterLunchSumTotal, 2)) + '</th><th >' + new Intl.NumberFormat().format(round(calculatedSumTotal, 2)) + '</th></tr>';
                tableHTML += '</table>';
                tableHTML += '<br>';
                table.innerHTML = tableHTML;
                document.getElementById('otchet').appendChild(table);

            }
            if (key == 'extra')
            {
                document.getElementById('otchet').innerHTML = "";
                var data = result.extra;
                var table = document.createElement('div');
                var tableHTML = '';
                tableHTML += '<table border="1px" style="border-collapse: collapse">';
                tableHTML += '<tr ><th colspan=\'16\'>Экстра бонусы по РЭП с ' + startDate + ' по ' + endDate + ' | Регион ' + city_name + ' </th></tr>';
                tableHTML += '<tr ><th colspan=\'10\'>Данные</th><th  colspan=\'6\'>Расчет</th></tr>';
                tableHTML += '<tr ><th >№</th><th >ФИО</th><th width="50px">Номер сотрудника</th><th width="50px">Количество до обеда</th><th width="100px" >Сумма до обеда</th><th width="50px" >Количество после обеда</th><th width="100px" >Сумма после обеда</th><th width="50px" >Общее количество</th><th width="50px" >Общая сумма</th><th width="100px" >Количество контрактов за 1 неделю</th><th width="100px" >Количество сертификатов за 1 неделю</th><th width="100px" >Количество контрактов за 2 неделю</th><th width="100px" >Количество сертификатов за 2 неделю</th><th width="100px" >Сумма до обеда</th><th width="100px" >Сумма после обеда</th><th width="50px">Общая сумма</th></tr>';
                var i = 1;
                var afterLunchCountTotal = 0;
                var afterLunchSumTotal = 0;
                var beforeLunchCountTotal = 0;
                var beforeLunchSumTotal = 0;
                var calculatedFirstWeekSumTotal = 0;
                var calculatedSecondWeekSumTotal = 0;
                var calculatedSumTotal = 0;
                var elementIDTotal = 0;
                var firstWeekCertificateCountTotal = 0;
                var firstWeekContractCountTotal = 0;
                var fullNameTotal = 0;
                var secondWeekCertificateCountTotal = 0;
                var secondWeekContractCountTotal = 0;
                var totalCountTotal = 0;
                var totalSumTotal = 0;

                data.sort((a, b) => a.ID > b.ID ? 1 : -1);
                for (var n = 0; n < data.length; n++) {
                    tableHTML += '<tr><td align="center">' + i +
                        '</td><td>' + data[n].fullName +
                        '</td><td>' + data[n].ID +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].beforeLunchCount) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].beforeLunchSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].afterLunchCount) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].afterLunchSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].totalCount) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].totalSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].firstWeekContractCount, 2)) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].firstWeekCertificateCount, 2)) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].secondWeekContractCount, 2)) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].secondWeekCertificateCount, 2)) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedFirstWeekSum, 2)) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedSecondWeekSum, 2)) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedSum, 2)) +
                        '</td></tr>';
                    afterLunchCountTotal=afterLunchCountTotal+data[n].afterLunchCount;
                    afterLunchSumTotal=afterLunchSumTotal+data[n].afterLunchSum;
                    beforeLunchCountTotal=beforeLunchCountTotal+data[n].beforeLunchCount;
                    beforeLunchSumTotal=beforeLunchSumTotal+data[n].beforeLunchSum;
                    calculatedFirstWeekSumTotal=calculatedFirstWeekSumTotal+data[n].calculatedFirstWeekSum;
                    calculatedSecondWeekSumTotal=calculatedSecondWeekSumTotal+data[n].calculatedSecondWeekSum;
                    calculatedSumTotal=calculatedSumTotal+data[n].calculatedSum;
                    elementIDTotal=elementIDTotal+data[n].elementID;
                    firstWeekCertificateCountTotal=firstWeekCertificateCountTotal+data[n].firstWeekCertificateCount;
                    firstWeekContractCountTotal=firstWeekContractCountTotal+data[n].firstWeekContractCount;
                    fullNameTotal=fullNameTotal+data[n].fullName;
                    secondWeekCertificateCountTotal=secondWeekCertificateCountTotal+data[n].secondWeekCertificateCount;
                    secondWeekContractCountTotal=secondWeekContractCountTotal+data[n].secondWeekContractCount;
                    totalCountTotal=totalCountTotal+data[n].totalCount;
                    totalSumTotal=totalSumTotal+data[n].totalSum;

                    i++;
                }
                console.log(calculatedAfterLunchSumTotal);
                tableHTML += '<tr><th colspan="3">Итого</th><th>' + new Intl.NumberFormat().format(beforeLunchCountTotal) +
                    '</th><th>' + new Intl.NumberFormat().format(beforeLunchSumTotal) +
                    '</th><th>' + new Intl.NumberFormat().format(afterLunchCountTotal) +
                    '</th><th>' + new Intl.NumberFormat().format(afterLunchSumTotal) +
                    '</th><th>' + new Intl.NumberFormat().format(totalCountTotal) +
                    '</th><th>' + new Intl.NumberFormat().format(totalSumTotal) +
                    '</th><th>' + new Intl.NumberFormat().format(firstWeekContractCountTotal) +
                    '</th><th>' + new Intl.NumberFormat().format(firstWeekCertificateCountTotal) +
                    '</th><th>' + new Intl.NumberFormat().format(secondWeekContractCountTotal) +
                    '</th><th>' + new Intl.NumberFormat().format(secondWeekCertificateCountTotal) +
                    '</th><th >' + new Intl.NumberFormat().format(round(calculatedFirstWeekSumTotal, 2)) +
                    '</th><th >' + new Intl.NumberFormat().format(round(calculatedSecondWeekSumTotal, 2)) +
                    '</th><th >' + new Intl.NumberFormat().format(round(calculatedSumTotal, 2)) + '</th></tr>';
                tableHTML += '</table>';
                table.innerHTML = tableHTML;
                document.getElementById('otchet').appendChild(table);
            }
        }

        if (positionID=='manager') {

            document.getElementById('otchet').innerHTML = "";
            if (key == 'gross')
            {
                var data = result.gross;
                var table = document.createElement('div');
                var tableHTML = '';
                tableHTML += '<table border="1px" style="border-collapse: collapse">';
                tableHTML += '<tr ><th colspan=\'15\'>Гросс по Менеджерам с ' + startDate + ' по ' + endDate + ' | Регион ' + city_name + ' </th></tr>';
                tableHTML += '<tr ><th colspan=\'12\'>Данные</th><th  colspan=\'3\'>Расчет</th></tr>';
                tableHTML += '<tr ><th >№</th><th >ФИО</th><th width="50px">Номер сотрудника</th><th width="50px">Тип договора</th><th width="50px">Количество до обеда</th><th width="100px" >Сумма до обеда</th><th width="50px" >Количество после обеда</th><th width="100px" >Сумма после обеда</th><th width="50px" >Сумма за 1-ую неделю</th><th width="50px" >Сумма за 2-ую неделю</th><th width="50px" >Общее количество</th><th width="50px" >Общая сумма</th><th width="100px" >Сумма за 1-ую неделю</th><th width="100px" >Сумма за 2-ую неделю</th><th width="50px">Общая сумма</th></tr>';
                var i = 1;
                var IDTotal = 0;
                var afterLunchCountTotal = 0;
                var afterLunchSumTotal = 0;
                var beforeLunchCountTotal = 0;
                var beforeLunchSumTotal = 0;
                var calculatedForTableTotal = 0;
                var calculatedSumTotal = 0;
                var certificateCountTotal = 0;
                var contractTypeTotal = 0;
                var elementIDTotal = 0;
                var firstWeekSumTotal = 0;
                var fullNameTotal = 0;
                var secondWeekSumTotal = 0;
                var calculatedFirstWeekSumTotal= 0;
                var calculatedSecondWeekSumTotal=0;
                var totalCountTotal = 0;
                var totalSumTotal = 0;

                data.sort((a, b) => a.ID > b.ID ? 1 : -1);
                for (var n = 0; n < data.length; n++) {
                    if(typeof(data[n].calculatedFirstWeekSum) == "undefined" && (data[n].calculatedFirstWeekSum) == null) {
                        data[n].calculatedFirstWeekSum=0;
                    }
                    if(typeof(data[n].calculatedSecondWeekSum) == "undefined" && (data[n].calculatedSecondWeekSum) == null) {
                        data[n].calculatedSecondWeekSum=0;
                    }
                    if(typeof(data[n].calculatedSum) == "undefined" && (data[n].calculatedSum) == null) {
                        data[n].calculatedSum=0;
                    }
                    tableHTML += '<tr><td align="center">' + i +
                        '</td><td>' + data[n].fullName +
                        '</td><td>' + data[n].ID +
                        '</td><td>' + data[n].contractType +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].beforeLunchCount) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].beforeLunchSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].afterLunchCount) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].afterLunchSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].firstWeekSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].secondWeekSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].totalCount) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].totalSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedFirstWeekSum, 2)) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedSecondWeekSum, 2)) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedSum, 2)) + '</td></tr>';

                    calculatedFirstWeekSumTotal = calculatedFirstWeekSumTotal + data[n].calculatedFirstWeekSum;
                    calculatedSecondWeekSumTotal = calculatedSecondWeekSumTotal + data[n].calculatedSecondWeekSum;
                    afterLunchCountTotal = afterLunchCountTotal + data[n].afterLunchCount;
                    afterLunchSumTotal = afterLunchSumTotal + data[n].afterLunchSum;
                    beforeLunchCountTotal = beforeLunchCountTotal + data[n].beforeLunchCount;
                    beforeLunchSumTotal = beforeLunchSumTotal + data[n].beforeLunchSum;
                    calculatedForTableTotal = calculatedForTableTotal + data[n].calculatedForTable;
                    calculatedSumTotal = calculatedSumTotal + data[n].calculatedSum;
                    certificateCountTotal = certificateCountTotal + data[n].certificateCount;
                    contractTypeTotal = contractTypeTotal + data[n].contractType;
                    elementIDTotal = elementIDTotal + data[n].elementID;
                    firstWeekSumTotal = firstWeekSumTotal + data[n].firstWeekSum;
                    fullNameTotal = fullNameTotal + data[n].fullName;
                    secondWeekSumTotal = secondWeekSumTotal + data[n].secondWeekSum;
                    totalCountTotal = totalCountTotal + data[n].totalCount;
                    totalSumTotal = totalSumTotal + data[n].totalSum;
                    i++;
                }
                console.log(calculatedAfterLunchSumTotal);
                tableHTML += '<tr><th colspan="4">Итого</th><th>'
                    + new Intl.NumberFormat().format(beforeLunchCountTotal) + '</th><th>'
                    + new Intl.NumberFormat().format(beforeLunchSumTotal) + '</th><th>'
                    + new Intl.NumberFormat().format(afterLunchCountTotal) + '</th><th>'
                    + new Intl.NumberFormat().format(afterLunchSumTotal) + '</th><th>'
                    + new Intl.NumberFormat().format(firstWeekSumTotal) + '</th><th>'
                    + new Intl.NumberFormat().format(secondWeekSumTotal) + '</th><th>'
                    + new Intl.NumberFormat().format(totalCountTotal) + '</th><th>'
                    + new Intl.NumberFormat().format(totalSumTotal) + '</th><th >'
                    + new Intl.NumberFormat().format(round(calculatedFirstWeekSumTotal, 2)) + '</th><th >'
                    + new Intl.NumberFormat().format(round(calculatedSecondWeekSumTotal, 2)) + '</th><th >'
                    + new Intl.NumberFormat().format(round(calculatedSumTotal, 2)) + '</th></tr>';
                tableHTML += '</table>';
                table.innerHTML = tableHTML;
                document.getElementById('otchet').appendChild(table);
            }
            if (key == 'spif')
            {
                var data = result.spif;
                var table = document.createElement('div');
                var tableHTML = '';
                tableHTML += '<table border="1px" style="border-collapse: collapse">';
                tableHTML += '<tr ><th colspan=\'12\'>Спиф по Менеджерам с ' + startDate + ' по ' + endDate + ' | Регион ' + city_name + ' </th></tr>';
                tableHTML += '<tr ><th colspan=\'9\'>Данные</th><th  colspan=\'3\'>Расчет</th></tr>';
                tableHTML += '<tr ><th >№</th><th >ФИО</th><th width="50px">Номер сотрудника</th><th width="50px">Количество до обеда</th><th width="100px" >Сумма до обеда</th><th width="50px" >Количество после обеда</th><th width="100px" >Сумма после обеда</th><th width="50px" >Общее количество</th><th width="50px" >Общая сумма</th><th width="100px" >Сумма до обеда</th><th width="100px" >Сумма после обеда</th><th width="50px">Общая сумма</th></tr>';
                var i = 1;
                var afterLunchCountTotal = 0;
                var afterLunchSumTotal = 0;
                var beforeLunchCountTotal = 0;
                var beforeLunchSumTotal = 0;
                var calculatedAfterLunchSumTotal = 0;
                var calculatedBeforeLunchSumTotal = 0;
                var calculatedPrihodAfterLunchSumTotal = 0;
                var calculatedPrihodBeforeLunchSumTotal = 0;
                var calculatedPrihodSumTotal = 0;
                var calculatedSumTotal = 0;
                var contractTypeTotal = 0;
                var elementIDTotal = 0;
                var filledPlaceTotal = 0;
                var fullNameTotal = 0;
                var totalCountTotal = 0;
                var totalSumTotal = 0;
                data.sort((a, b) => a.ID > b.ID ? 1 : -1);
                for (var n = 0; n < data.length; n++) {
                    tableHTML += '<tr><td align="center">' + i +
                        '</td><td>' + data[n].fullName +
                        '</td><td>' + data[n].ID +
                        '</td><td align="center">' + data[n].beforeLunchCount +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].beforeLunchSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].afterLunchCount) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].afterLunchSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].totalCount) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].totalSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedBeforeLunchSum, 2)) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedAfterLunchSum, 2)) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedSum, 2)) + '</td></tr>';
                    afterLunchCountTotal = afterLunchCountTotal + data[n].afterLunchCount;
                    afterLunchSumTotal = afterLunchSumTotal + data[n].afterLunchSum;
                    beforeLunchCountTotal = beforeLunchCountTotal + data[n].beforeLunchCount;
                    beforeLunchSumTotal = beforeLunchSumTotal + data[n].beforeLunchSum;
                    calculatedAfterLunchSumTotal = calculatedAfterLunchSumTotal + data[n].calculatedAfterLunchSum;
                    calculatedBeforeLunchSumTotal = calculatedBeforeLunchSumTotal + data[n].calculatedBeforeLunchSum;
                    calculatedSumTotal = calculatedSumTotal + data[n].calculatedSum;
                    calculatedPrihodAfterLunchSumTotal = calculatedPrihodAfterLunchSumTotal + data[n].calculatedPrihodAfterLunchSum;
                    calculatedPrihodBeforeLunchSumTotal = calculatedPrihodBeforeLunchSumTotal + data[n].calculatedPrihodBeforeLunchSum;
                    calculatedPrihodSumTotal = calculatedPrihodSumTotal + data[n].calculatedPrihodSum;
                    contractTypeTotal = contractTypeTotal + data[n].contractType;
                    elementIDTotal = elementIDTotal + data[n].elementID;
                    filledPlaceTotal = filledPlaceTotal + data[n].filledPlace;
                    fullNameTotal = fullNameTotal + data[n].fullName;
                    totalCountTotal = totalCountTotal + data[n].totalCount;
                    totalSumTotal = totalSumTotal + data[n].totalSum;
                    i++;
                }
                console.log(calculatedAfterLunchSumTotal);
                tableHTML += '<tr><th colspan="3">Итого</th><th>' + new Intl.NumberFormat().format(beforeLunchCountTotal) +
                    '</th><th>' + new Intl.NumberFormat().format(beforeLunchSumTotal) +
                    '</th><th>' + new Intl.NumberFormat().format(afterLunchCountTotal) +
                    '</th><th>' + new Intl.NumberFormat().format(afterLunchSumTotal) +
                    '</th><th>' + new Intl.NumberFormat().format(totalCountTotal) +
                    '</th><th>' + new Intl.NumberFormat().format(totalSumTotal) +
                    '</th><th >' + new Intl.NumberFormat().format(round(calculatedBeforeLunchSumTotal, 2)) +
                    '</th><th >' + new Intl.NumberFormat().format(round(calculatedAfterLunchSumTotal, 2)) +
                    '</th><th >' + new Intl.NumberFormat().format(round(calculatedSumTotal, 2)) + '</th></tr>';
                tableHTML += '</table>';
                table.innerHTML = tableHTML;
                document.getElementById('otchet').appendChild(table);
            }
        }

        if (positionID=='podium') {

            document.getElementById('otchet').innerHTML = "";
            if (key == 'gross')
            {
                var data = result.gross;
                var table = document.createElement('div');
                var tableHTML = '';
                tableHTML += '<table border="1px" style="border-collapse: collapse">';
                tableHTML += '<tr ><th colspan=\'15\'>Гросс по Подиум-Мейкерам с ' + startDate + ' по ' + endDate + ' | Регион ' + city_name + ' </th></tr>';
                tableHTML += '<tr ><th colspan=\'10\'>Данные</th><th  colspan=\'1\'>Расчет</th></tr>';
                tableHTML += '<tr ><th >№</th><th >ФИО</th><th width="50px">Номер сотрудника</th><th width="50px">Дата презентации</th><th width="50px">Количество до обеда</th><th width="100px" >Сумма до обеда</th><th width="50px" >Количество после обеда</th><th width="100px" >Сумма после обеда</th><th width="50px" >Общее количество</th><th width="50px" >Общая сумма</th><th width="50px">Общая сумма</th></tr>';
                var i = 1;
                var IDTotal = 0;
                var afterLunchCountTotal = 0;
                var afterLunchSumTotal = 0;
                var beforeLunchCountTotal = 0;
                var beforeLunchSumTotal = 0;
                var calculatedForTableTotal = 0;
                var calculatedSumTotal = 0;
                var certificateCountTotal = 0;
                var contractTypeTotal = 0;
                var elementIDTotal = 0;
                var firstWeekSumTotal = 0;
                var fullNameTotal = 0;
                var secondWeekSumTotal = 0;
                var calculatedFirstWeekSumTotal= 0;
                var calculatedSecondWeekSumTotal=0;
                var totalCountTotal = 0;
                var totalSumTotal = 0;

                data.sort((a, b) => a.ID > b.ID ? 1 : -1);
                for (var n = 0; n < data.length; n++) {
                    if(typeof(data[n].calculatedFirstWeekSum) == "undefined" && (data[n].calculatedFirstWeekSum) == null) {
                        data[n].calculatedFirstWeekSum=0;
                    }
                    if(typeof(data[n].calculatedSecondWeekSum) == "undefined" && (data[n].calculatedSecondWeekSum) == null) {
                        data[n].calculatedSecondWeekSum=0;
                    }
                    if(typeof(data[n].calculatedSum) == "undefined" && (data[n].calculatedSum) == null) {
                        data[n].calculatedSum=0;
                    }
                    tableHTML += '<tr><td align="center">' + i +
                        '</td><td>' + data[n].fullName +
                        '</td><td>' + data[n].ID +
                        '</td><td>' + data[n].presentationDate +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].beforeLunchCount) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].beforeLunchSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].afterLunchCount) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].afterLunchSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].totalCount) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].totalSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedSum, 2)) + '</td></tr>';

                    calculatedFirstWeekSumTotal = calculatedFirstWeekSumTotal + data[n].calculatedFirstWeekSum;
                    calculatedSecondWeekSumTotal = calculatedSecondWeekSumTotal + data[n].calculatedSecondWeekSum;
                    afterLunchCountTotal = afterLunchCountTotal + data[n].afterLunchCount;
                    afterLunchSumTotal = afterLunchSumTotal + data[n].afterLunchSum;
                    beforeLunchCountTotal = beforeLunchCountTotal + data[n].beforeLunchCount;
                    beforeLunchSumTotal = beforeLunchSumTotal + data[n].beforeLunchSum;
                    calculatedForTableTotal = calculatedForTableTotal + data[n].calculatedForTable;
                    calculatedSumTotal = calculatedSumTotal + data[n].calculatedSum;
                    certificateCountTotal = certificateCountTotal + data[n].certificateCount;
                    contractTypeTotal = contractTypeTotal + data[n].contractType;
                    elementIDTotal = elementIDTotal + data[n].elementID;
                    firstWeekSumTotal = firstWeekSumTotal + data[n].firstWeekSum;
                    fullNameTotal = fullNameTotal + data[n].fullName;
                    secondWeekSumTotal = secondWeekSumTotal + data[n].secondWeekSum;
                    totalCountTotal = totalCountTotal + data[n].totalCount;
                    totalSumTotal = totalSumTotal + data[n].totalSum;
                    i++;
                }
                console.log(calculatedAfterLunchSumTotal);
                tableHTML += '<tr><th colspan="4">Итого</th><th>'
                    + new Intl.NumberFormat().format(beforeLunchCountTotal) + '</th><th>'
                    + new Intl.NumberFormat().format(beforeLunchSumTotal) + '</th><th>'
                    + new Intl.NumberFormat().format(afterLunchCountTotal) + '</th><th>'
                    + new Intl.NumberFormat().format(afterLunchSumTotal) + '</th><th>'
                    + new Intl.NumberFormat().format(totalCountTotal) + '</th><th>'
                    + new Intl.NumberFormat().format(totalSumTotal) + '</th><th >'
                    + new Intl.NumberFormat().format(round(calculatedSumTotal, 2)) + '</th></tr>';
                tableHTML += '</table>';
                table.innerHTML = tableHTML;
                document.getElementById('otchet').appendChild(table);
            }
        }

        if (positionID=='MM') {

            document.getElementById('otchet').innerHTML = "";
            if (key == 'gross')
            {
                var data = result.gross;
                var table = document.createElement('div');
                var tableHTML = '';
                tableHTML += '<table border="1px" style="border-collapse: collapse">';
                tableHTML += '<tr ><th colspan=\'12\'>Гросс по Руководителю маркетинга с ' + startDate + ' по ' + endDate + ' | Регион ' + city_name + ' </th></tr>';
                tableHTML += '<tr ><th colspan=\'9\'>Данные</th><th  colspan=\'3\'>Расчет</th></tr>';
                tableHTML += '<tr ><th >ФИО</th><th width="50px">Тип договора</th><th width="50px">Место заполнения</th><th width="50px">Количество до обеда</th><th width="100px" >Сумма до обеда</th><th width="50px" >Количество после обеда</th><th width="100px" >Сумма после обеда</th><th width="50px" >Общее количество</th><th width="50px" >Общая сумма</th><th width="50px" >Сумма до обеда</th><th width="50px" >Сумма после обеда</th><th width="50px">Общая сумма</th></tr>';
                var i = 1;
                var IDTotal = 0;
                var afterLunchCountTotal = 0;
                var afterLunchSumTotal = 0;
                var beforeLunchCountTotal = 0;
                var beforeLunchSumTotal = 0;
                var calculatedForTableTotal = 0;
                var calculatedSumTotal = 0;
                var certificateCountTotal = 0;
                var contractTypeTotal = 0;
                var elementIDTotal = 0;
                var firstWeekSumTotal = 0;
                var fullNameTotal = 0;
                var secondWeekSumTotal = 0;
                var calculatedFirstWeekSumTotal= 0;
                var calculatedSecondWeekSumTotal=0;
                var calculatedAfterLunchSumTotal= 0;
                var calculatedBeforeLunchSumTotal=0;
                var totalCountTotal = 0;
                var totalSumTotal = 0;

                data.sort((a, b) => a.ID > b.ID ? 1 : -1);
                for (var n = 0; n < data.length; n++) {
                    if(typeof(data[n].calculatedFirstWeekSum) == "undefined" && (data[n].calculatedFirstWeekSum) == null) {
                        data[n].calculatedFirstWeekSum=0;
                    }
                    if(typeof(data[n].calculatedSecondWeekSum) == "undefined" && (data[n].calculatedSecondWeekSum) == null) {
                        data[n].calculatedSecondWeekSum=0;
                    }
                    if(typeof(data[n].calculatedSum) == "undefined" && (data[n].calculatedSum) == null) {
                        data[n].calculatedSum=0;
                    }
                    tableHTML += '<tr><td>' + data[n].fullName +
                        '</td><td>' + data[n].contractType +
                        '</td><td>' + data[n].filledPlace +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].beforeLunchCount) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].beforeLunchSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].afterLunchCount) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].afterLunchSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].totalCount) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].totalSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedBeforeLunchSum, 2)) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedAfterLunchSum, 2)) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedSum, 2)) + '</td></tr>';

                    calculatedFirstWeekSumTotal = calculatedFirstWeekSumTotal + data[n].calculatedFirstWeekSum;
                    calculatedSecondWeekSumTotal = calculatedSecondWeekSumTotal + data[n].calculatedSecondWeekSum;
                    afterLunchCountTotal = afterLunchCountTotal + data[n].afterLunchCount;
                    afterLunchSumTotal = afterLunchSumTotal + data[n].afterLunchSum;
                    beforeLunchCountTotal = beforeLunchCountTotal + data[n].beforeLunchCount;
                    beforeLunchSumTotal = beforeLunchSumTotal + data[n].beforeLunchSum;
                    calculatedForTableTotal = calculatedForTableTotal + data[n].calculatedForTable;
                    calculatedSumTotal = calculatedSumTotal + data[n].calculatedSum;
                    calculatedAfterLunchSumTotal = calculatedAfterLunchSumTotal + data[n].calculatedBeforeLunchSum;
                    calculatedBeforeLunchSumTotal = calculatedBeforeLunchSumTotal + data[n].calculatedAfterLunchSum;
                    certificateCountTotal = certificateCountTotal + data[n].certificateCount;
                    contractTypeTotal = contractTypeTotal + data[n].contractType;
                    elementIDTotal = elementIDTotal + data[n].elementID;
                    firstWeekSumTotal = firstWeekSumTotal + data[n].firstWeekSum;
                    fullNameTotal = fullNameTotal + data[n].fullName;
                    secondWeekSumTotal = secondWeekSumTotal + data[n].secondWeekSum;
                    totalCountTotal = totalCountTotal + data[n].totalCount;
                    totalSumTotal = totalSumTotal + data[n].totalSum;
                    i++;
                }
                console.log(calculatedAfterLunchSumTotal);
                tableHTML += '<tr><th colspan="3">Итого</th><th>'
                    + new Intl.NumberFormat().format(beforeLunchCountTotal) + '</th><th>'
                    + new Intl.NumberFormat().format(beforeLunchSumTotal) + '</th><th>'
                    + new Intl.NumberFormat().format(afterLunchCountTotal) + '</th><th>'
                    + new Intl.NumberFormat().format(afterLunchSumTotal) + '</th><th>'
                    + new Intl.NumberFormat().format(totalCountTotal) + '</th><th>'
                    + new Intl.NumberFormat().format(totalSumTotal) + '</th><th >'
                    + new Intl.NumberFormat().format(round(calculatedBeforeLunchSumTotal, 2)) + '</th><th >'
                    + new Intl.NumberFormat().format(round(calculatedAfterLunchSumTotal, 2)) + '</th><th >'
                    + new Intl.NumberFormat().format(round(calculatedSumTotal, 2)) + '</th></tr>';
                tableHTML += '</table>';
                table.innerHTML = tableHTML;
                document.getElementById('otchet').appendChild(table);
            }
        }

        if (positionID=='CCM') {

            document.getElementById('otchet').innerHTML = "";
            if (key == 'gross')
            {
                var data = result.gross;
                var table = document.createElement('div');
                var tableHTML = '';
                tableHTML += '<table border="1px" style="border-collapse: collapse">';
                tableHTML += '<tr ><th colspan=\'10\'>Гросс по Руководителю Колл Центра с ' + startDate + ' по ' + endDate + ' | Регион ' + city_name + ' </th></tr>';
                tableHTML += '<tr ><th colspan=\'7\'>Данные</th><th  colspan=\'3\'>Расчет</th></tr>';
                tableHTML += '<tr ><th >ФИО</th><th width="50px">Количество до обеда</th><th width="100px" >Сумма до обеда</th><th width="50px" >Количество после обеда</th><th width="100px" >Сумма после обеда</th><th width="50px" >Общее количество</th><th width="50px" >Общая сумма</th><th width="100px" >Сумма до обеда</th><th width="100px" >Сумма после обеда</th><th width="50px">Общая сумма</th></tr>';
                var i = 1;
                var IDTotal = 0;
                var afterLunchCountTotal = 0;
                var afterLunchSumTotal = 0;
                var beforeLunchCountTotal = 0;
                var beforeLunchSumTotal = 0;
                var calculatedForTableTotal = 0;
                var calculatedSumTotal = 0;
                var certificateCountTotal = 0;
                var contractTypeTotal = 0;
                var elementIDTotal = 0;
                var firstWeekSumTotal = 0;
                var fullNameTotal = 0;
                var secondWeekSumTotal = 0;
                var calculatedFirstWeekSumTotal= 0;
                var calculatedSecondWeekSumTotal=0;
                var calculatedAfterLunchSumTotal= 0;
                var calculatedBeforeLunchSumTotal=0;
                var totalCountTotal = 0;
                var totalSumTotal = 0;

                data.sort((a, b) => a.ID > b.ID ? 1 : -1);
                for (var n = 0; n < data.length; n++) {
                    if(typeof(data[n].calculatedFirstWeekSum) == "undefined" && (data[n].calculatedFirstWeekSum) == null) {
                        data[n].calculatedFirstWeekSum=0;
                    }
                    if(typeof(data[n].calculatedSecondWeekSum) == "undefined" && (data[n].calculatedSecondWeekSum) == null) {
                        data[n].calculatedSecondWeekSum=0;
                    }
                    if(typeof(data[n].calculatedSum) == "undefined" && (data[n].calculatedSum) == null) {
                        data[n].calculatedSum=0;
                    }
                    tableHTML += '<tr><td>' + data[n].fullName +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].beforeLunchCount) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].beforeLunchSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].afterLunchCount) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].afterLunchSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].totalCount) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(data[n].totalSum) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedBeforeLunchSum, 2)) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedAfterLunchSum, 2)) +
                        '</td><td align="center">' + new Intl.NumberFormat().format(round(data[n].calculatedSum, 2)) + '</td></tr>';

                    calculatedFirstWeekSumTotal = calculatedFirstWeekSumTotal + data[n].calculatedFirstWeekSum;
                    calculatedSecondWeekSumTotal = calculatedSecondWeekSumTotal + data[n].calculatedSecondWeekSum;
                    afterLunchCountTotal = afterLunchCountTotal + data[n].afterLunchCount;
                    afterLunchSumTotal = afterLunchSumTotal + data[n].afterLunchSum;
                    beforeLunchCountTotal = beforeLunchCountTotal + data[n].beforeLunchCount;
                    beforeLunchSumTotal = beforeLunchSumTotal + data[n].beforeLunchSum;
                    calculatedForTableTotal = calculatedForTableTotal + data[n].calculatedForTable;
                    calculatedSumTotal = calculatedSumTotal + data[n].calculatedSum;
                    calculatedAfterLunchSumTotal = calculatedAfterLunchSumTotal + data[n].calculatedBeforeLunchSum;
                    calculatedBeforeLunchSumTotal = calculatedBeforeLunchSumTotal + data[n].calculatedAfterLunchSum;
                    certificateCountTotal = certificateCountTotal + data[n].certificateCount;
                    contractTypeTotal = contractTypeTotal + data[n].contractType;
                    elementIDTotal = elementIDTotal + data[n].elementID;
                    firstWeekSumTotal = firstWeekSumTotal + data[n].firstWeekSum;
                    fullNameTotal = fullNameTotal + data[n].fullName;
                    secondWeekSumTotal = secondWeekSumTotal + data[n].secondWeekSum;
                    totalCountTotal = totalCountTotal + data[n].totalCount;
                    totalSumTotal = totalSumTotal + data[n].totalSum;
                    i++;
                }
                console.log(calculatedAfterLunchSumTotal);
                tableHTML += '<tr><th colspan="1">Итого</th><th>'
                    + new Intl.NumberFormat().format(beforeLunchCountTotal) + '</th><th>'
                    + new Intl.NumberFormat().format(beforeLunchSumTotal) + '</th><th>'
                    + new Intl.NumberFormat().format(afterLunchCountTotal) + '</th><th>'
                    + new Intl.NumberFormat().format(afterLunchSumTotal) + '</th><th>'
                    + new Intl.NumberFormat().format(totalCountTotal) + '</th><th>'
                    + new Intl.NumberFormat().format(totalSumTotal) + '</th><th >'
                    + new Intl.NumberFormat().format(round(calculatedBeforeLunchSumTotal, 2)) + '</th><th >'
                    + new Intl.NumberFormat().format(round(calculatedAfterLunchSumTotal, 2)) + '</th><th >'
                    + new Intl.NumberFormat().format(round(calculatedSumTotal, 2)) + '</th></tr>';
                tableHTML += '</table>';
                table.innerHTML = tableHTML;
                document.getElementById('otchet').appendChild(table);
            }
        }
    }
      function formatDate(date) {
          var d = new Date(date),
              month = '' + (d.getMonth() + 1),
              day = '' + d.getDate(),
              year = d.getFullYear();

          if (month.length < 2)
              month = '0' + month;
          if (day.length < 2)
              day = '0' + day;

          return [year, month, day].join('-');
      }
  }



</script>
<script>
    function round(value, decimals) {
        return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
    }
    function neededSub(){
        var dolzhnost = document.getElementById("dolzhnost").value;
        var neededSubfield =  document.getElementById("neededSubfield");

        if (dolzhnost=="null")
        {
            document.getElementById('gross').style.display='none';
            document.getElementById('grossMonth').style.display='none';
            document.getElementById('spif').style.display='none';
            document.getElementById('bonus').style.display='none';
            document.getElementById('extra').style.display='none';
        }
        if (dolzhnost=="agent")
        {
            document.getElementById('gross').style.display='block';
            document.getElementById('grossMonth').style.display='none';
            document.getElementById('spif').style.display='none';
            document.getElementById('bonus').style.display='block';
            document.getElementById('extra').style.display='none';
        }
        if (dolzhnost=="operator")
        {
            document.getElementById('gross').style.display='block';
            document.getElementById('grossMonth').style.display='none';
            document.getElementById('spif').style.display='block';
            document.getElementById('bonus').style.display='none';
            document.getElementById('extra').style.display='none';
        }
        if (dolzhnost=="rep")
        {
            document.getElementById('gross').style.display='block';
            document.getElementById('grossMonth').style.display='none';
            document.getElementById('spif').style.display='block';
            document.getElementById('bonus').style.display='none';
            document.getElementById('extra').style.display='block';
        }
        if (dolzhnost=="manager")
        {
            document.getElementById('gross').style.display='block';
            document.getElementById('grossMonth').style.display='none';
            document.getElementById('spif').style.display='block';
            document.getElementById('bonus').style.display='none';
            document.getElementById('extra').style.display='none';
        }
        if (dolzhnost=="podium")
        {
            document.getElementById('gross').style.display='block';
            document.getElementById('grossMonth').style.display='none';
            document.getElementById('spif').style.display='none';
            document.getElementById('bonus').style.display='none';
            document.getElementById('extra').style.display='none';
        }
        if (dolzhnost=="MM")
        {
            document.getElementById('gross').style.display='block';
            document.getElementById('grossMonth').style.display='none';
            document.getElementById('spif').style.display='none';
            document.getElementById('bonus').style.display='none';
            document.getElementById('extra').style.display='none';
        }
        if (dolzhnost=="CCM")
        {
            document.getElementById('gross').style.display='block';
            document.getElementById('grossMonth').style.display='none';
            document.getElementById('spif').style.display='none';
            document.getElementById('bonus').style.display='none';
            document.getElementById('extra').style.display='none';
        }
        if (dolzhnost=="SM")
        {
            document.getElementById('gross').style.display='block';
            document.getElementById('grossMonth').style.display='block';
            document.getElementById('spif').style.display='none';
            document.getElementById('bonus').style.display='none';
            document.getElementById('extra').style.display='none';
        }
        if (dolzhnost=="director")
        {
            document.getElementById('gross').style.display='block';
            document.getElementById('grossMonth').style.display='none';
            document.getElementById('spif').style.display='none';
            document.getElementById('bonus').style.display='none';
            document.getElementById('extra').style.display='none';
        }

    };
</script>