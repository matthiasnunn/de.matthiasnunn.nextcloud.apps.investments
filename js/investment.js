Date.prototype.toDateString = function() {
    var day = String(this.getUTCDate()).padStart(2, "0");
    var month = String(this.getUTCMonth() + 1).padStart(2, "0");
    var year = String(this.getUTCFullYear()).slice(-2);
    return `${day}.${month}.${year}`;
}

Date.prototype.toSortString = function() {
    var day = String(this.getUTCDate()).padStart(2, "0");
    var month = String(this.getUTCMonth() + 1).padStart(2, "0");
    var year = String(this.getUTCFullYear());
    return `${year}-${month}-${day}`;
}

Number.prototype.toCurrencyString = function() {
    var parts = this.toFixed(2).split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");  // "." bei Tausender einfügen
    return parts.join(",");
}

Number.prototype.toRenditeString = function() {
    return this.toFixed(2).replace(".", ",");
};


var data = document.getElementsByClassName("data")[0].textContent.trim();

var investments = JSON.parse(data);


var dataSet = [];

investments.forEach(function(investment) {
    investment.purchases.forEach(function(purchase) {
        dataSet.push({
            "name": {
                link: investment.link,
                name: investment.name
            },
            "purchase": purchase.purchase,
            "quantity": purchase.quantity,
            "purchasePrice": {
                "fees": purchase.fees.map(function(fee) {
                    return {
                        "fee": fee.fee,
                        "name": fee.name
                    };
                }),
                "purchasePrice": purchase.purchasePrice
            },
            "purchaseCourse": purchase.purchaseCourse,
            "currentCourse": investment.currentCourse,
            "currentPrice": purchase.currentPrice,
            "rendite": purchase.rendite
        });
    });
});

new DataTable(".table", {
    columns: [
        {
            data: "name",
            render: function(data) {
                return `<a href="${data.link}" target="_blank">${data.name}</a>`;
            },
            title: "Name"
        },
        {
            className: "dt-body-right",  // rechtsbündig
            data: "purchase",
            render: {
                display: function (data) {
                    return new Date(data.date).toDateString();
                },
                sort: function (data) {
                    return new Date(data.date).toSortString();
                }
            },
            title: "Kaufdatum"
        },
        {
            data: "quantity",
            title: "Anzahl"
        },
        {
            className: "dt-body-right",  // rechtsbündig
            data: "purchasePrice",
            render: {
                display: function(data) {
                    var tooltip = "";
                    data.fees.forEach(function(fee) {
                        tooltip += `${fee.name}: ${fee.fee} €\n`;
                    });
                    tooltip = tooltip.slice(0, -1);
                    return `<span title="${tooltip}">${data.purchasePrice.toCurrencyString()} €</span>`;
                },
                sort: function(data) {
                    var fees = 0;
                    data.fees.forEach(function(fee) {
                        fees += fee.fee;
                    });
                    return data.purchasePrice + fees;
                }
            },
            title: "Kaufpreis<br>(inkl. Gebühr)",
            type: "num-fmt"
        },
        {
            data: "purchaseCourse",
            render: {
                display: function(data) {
                    return `${data.toCurrencyString()} €`;
                },
                sort: function(data) {
                    return data;
                }
            },
            title: "Kaufkurs"
        },
        {
            data: "currentCourse",
            render: {
                display: function(data) {
                    return `${data.toCurrencyString()} €`;
                },
                sort: function(data) {
                    return data;
                }
            },
            title: "Aktueller Kurs"
        },
        {
            data: "currentPrice",
            render: {
                display: function(data) {
                    return `${data.toCurrencyString()} €`;
                },
                sort: function(data) {
                    return data;
                }
            },
            title: "Aktueller Preis"
        },
        {
            data: "rendite",
            render: function(data) {
                var className = "";
                if (data > 0) className = "color-green";
                else if (data < 0) className = "color-red";
                return `<span class="${className}">${data.toRenditeString()} %</span>`;
            },
            title: "Rendite"
        }
    ],
    data: dataSet,
    info: false,          // Gesamtzahl der Einträge ausblenden
    lengthChange: false,  // auswahl der Einträge pro Seite ausblenden
    order: [
        [0, "asc"],
        [1, "asc"]
    ],
    pageLength: -1,       // alle Einträge auf Seite 1 anzeigen
    paging: false,
    searching: false
});