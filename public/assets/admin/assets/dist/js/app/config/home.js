
let printed_invoice, printed_users, printed_books, send_mail, send_invoice, total_users, total_books, total_payment_method, total_roles;

async function records() {
    try {
        const response = await fetch(base_url + 'api/records');
        if (!response.ok) {
            $.jGrowl("Error", {
                header: 'Network response was not ok'
            });
            return;
        }
        const data = await response.json();
        return data; 
    } catch (error) {
        $.jGrowl("Error", {
            header: 'Error fetching records:', error
        });
        return;
    }
}


await records()
    .then(data => {
        // Handle successful response 
        if (data) { 
            printed_invoice = data.printed_invoices;
            printed_users = data.printed_users;
            printed_books = data.printed_books;
            send_mail= data.send_emails;
            send_invoice = data.send_invoices;
            total_users =data.users;
            total_books =data.books;
            total_payment_method = data.payment_method;
            total_roles = data.roles;
            const invoices = data.invoices;

            var Piechart = new CanvasJS.Chart("PieChart", {
                exportEnabled: false,
                animationEnabled: true,
                title: {
                    text: "Record Overview"
                },
                legend: {
                    cursor: "pointer",
                    itemclick: explodePie
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    toolTipContent: "{name}: <strong>{y}%</strong>",
                    indexLabel: "{name} - {y}%",
                    dataPoints: [{
                        y: printed_invoice,
                        name: "Printed Invoice",
                        exploded: true
                    },
            
                    {
                        y: printed_users,
                        name: " Printed Users",
                        exploded: true
                    },
            
                    {
                        y: printed_books,
                        name: "Printed Books",
                        exploded: true
                    },
            
                    {
                        y: send_mail,
                        name: "Total Email Sent",
                        exploded: true
                    },
            
                    {
                        y: send_invoice,
                        name: "Total Invoice Sent",
                        exploded: true
                    }
                    ]
                }]
                });
            
                var AccChart = new CanvasJS.Chart("product_review", {
                exportEnabled: false,
                animationEnabled: true,
                title: {
                    text: "Invoices Overview"
                },
                legend: {
                    cursor: "pointer",
                    itemclick: explodePie
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    toolTipContent: "{name}: <strong>{y}%</strong>",
                    indexLabel: "{name} - {y}%",
                    dataPoints: [{
                        y: invoices,
                        name: "Invoices",
                        exploded: true
                    },
            
                    {
                        y: total_roles,
                        name: "Roles",
                        exploded: true
                    },
            
                    {
                        y: total_books,
                        name: "Stock Library",
                        exploded: true
                    }
            
                    ]
                }]
                });
                Piechart.render();
                AccChart.render();
        }
    })

    function explodePie(e) {
        if (typeof(e.dataSeries.dataPoints[e.dataPointIndex].exploded) === "undefined" || !e.dataSeries.dataPoints[e.dataPointIndex].exploded) {
            e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
        } else {
            e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
        }
        e.chart.render();
    }