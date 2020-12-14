// 📁 main.js
import { main } from "./src/script.js";
// CITY: "1377",
// POSITION_ID: "rep",
// NEEDED_SUBFIELD: "extra",
// // format YYYY-MM-DD
// START_DATE: "2020-11-01",
// END_DATE: "2020-11-13",
const city = 1377;
const positionID = "agent";
const neededSubfield = "bonus";
const startDate = "2020-11-10";
const endDate = "2020-11-16";

draw();

async function draw() {
  const result = await main(city, positionID, startDate, endDate, neededSubfield);
  console.log(result);
}
