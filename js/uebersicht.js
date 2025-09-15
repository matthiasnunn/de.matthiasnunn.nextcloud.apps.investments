var aktien = JSON.parse(document.getElementsByClassName("data")[0].textContent.trim());
var devisen = JSON.parse(document.getElementsByClassName("data")[1].textContent.trim());
var etfs = JSON.parse(document.getElementsByClassName("data")[2].textContent.trim());
var rohstoffe = JSON.parse(document.getElementsByClassName("data")[3].textContent.trim());

var investmentsDevelopmentModel = JSON.parse(document.getElementsByClassName("data")[4].textContent.trim());


var chartRendite = document.getElementsByClassName("chart")[0];
var chartCurrentPrice = document.getElementsByClassName("chart")[1];
var chartInvestmentRatio = document.getElementsByClassName("chart")[2];

// TODO
// Werte "normalisieren", d.h., wenn die timestamps nicht übereinstimmen, mit null auffüllen.

new Chart(chartRendite, {
  data: {
    labels: investmentsDevelopmentModel[0].items.map(investmentsDevelopmentItemModel => investmentsDevelopmentItemModel.formattedDate),
    datasets: investmentsDevelopmentModel.map(investmentsDevelopmentModel => ({
      data: investmentsDevelopmentModel.items.map(investmentsDevelopmentItemModel => investmentsDevelopmentItemModel.rendite),
      label: investmentsDevelopmentModel.name,
      pointRadius: 4
    }))
  },
  options: {
    scales: {
      y: {
        ticks: {
          callback: function(value) {
            return value + " %";
          }
        }
      }
    }
  },
  type: "line"
});

new Chart(chartCurrentPrice, {
  data: {
    labels: investmentsDevelopmentModel[0].items.map(investmentsDevelopmentItemModel => investmentsDevelopmentItemModel.formattedDate),
    datasets: investmentsDevelopmentModel.map(investmentsDevelopmentModel => ({
      data: investmentsDevelopmentModel.items.map(investmentsDevelopmentItemModel => investmentsDevelopmentItemModel.currentPrice),
      label: investmentsDevelopmentModel.name,
      pointRadius: 4
    }))
  },
  options: {
    scales: {
      y: {
        ticks: {
          callback: function(value) {
            return value + " €";
          }
        }
      }
    }
  },
  type: "line"
});

var totalPrice = aktien.totalCurrentPrice + etfs.totalCurrentPrice + rohstoffe.totalCurrentPrice + devisen.totalCurrentPrice;

new Chart(chartInvestmentRatio, {
  data: {
    labels: [
      "Aktien",
      "ETFs",
      "Rohstoffe",
      "Devisen"
    ],
    datasets: [{
      data: [
        aktien.totalCurrentPrice / totalPrice * 100,
        etfs.totalCurrentPrice / totalPrice * 100,
        rohstoffe.totalCurrentPrice / totalPrice * 100,
        devisen.totalCurrentPrice / totalPrice * 100
      ]
    }]
  },
  type: "pie"
});