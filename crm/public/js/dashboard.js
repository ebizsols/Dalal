/**
* Dashboard JS - Dashboard js for Clientmanager theme
* @version v3.1.0
* @copyright 2020 Pepdev.
*/

$(document).ready(function () {
	"use strict";
	var path = $('input.site_url').val();

	//labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    //value: [20, 30, 20, 40, 30, 60, 30, 24, 32, 38, 30, 22]
    if ($('.income-chart-data').length && $('#income-expense-chart').length) {
    	//Income Expense chart
    	var chart_income = JSON.parse($('.income-chart-data').val()),
    	chart_expense = JSON.parse($('.expense-chart-data').val()),
    	ctx = document.getElementById("income-expense-chart").getContext("2d"),
    	gradient = ctx.createLinearGradient(0, 0, 0, 240),
    	gradient1 = ctx.createLinearGradient(0, 0, 0, 240);
    	gradient.addColorStop(0, Chart.helpers.color('#3483FF').alpha(1).rgbString());
    	gradient.addColorStop(1, Chart.helpers.color('#c9d2ff').alpha(.2).rgbString());
    	gradient1.addColorStop(0, Chart.helpers.color('#fd397a').alpha(1).rgbString());
    	gradient1.addColorStop(1, Chart.helpers.color('#ffbac5').alpha(.2).rgbString());

    	new Chart(ctx, {
    		type: 'line',
    		data: {
    			labels: JSON.parse(chart_income.label),
    			datasets: [{
    				label: "Income",
    				fill: false,
    				backgroundColor: '#3483FF',
    				borderColor: '#3483FF',
    				pointHoverRadius: 4,
    				pointHoverBorderWidth: 12,
    				pointBackgroundColor: Chart.helpers.color('#ffffff').alpha(0).rgbString(),
    				pointBorderColor: Chart.helpers.color('#ffffff').alpha(0).rgbString(),
    				pointHoverBackgroundColor: "#3483FF",
    				pointHoverBorderColor: Chart.helpers.color('#000000').alpha(0.2).rgbString(),
    				data: JSON.parse(chart_income.value),
    			},
    			{
    				label: "Expense",
    				fill: false,
    				backgroundColor: '#fd397a',
    				borderColor: '#fd397a',
    				pointHoverRadius: 4,
    				pointHoverBorderWidth: 12,
    				pointBackgroundColor: Chart.helpers.color('#000000').alpha(0).rgbString(),
    				pointBorderColor: Chart.helpers.color('#000000').alpha(0).rgbString(),
    				pointHoverBackgroundColor: "#fd397a",
    				pointHoverBorderColor: Chart.helpers.color('#000000').alpha(0.1).rgbString(),
    				data: JSON.parse(chart_expense.value),
    			}]
    		},
    		options: {
    			title: {
    				display: false,
    			},
    			legend: {
    				display: false
    			},
    			responsive: true,
    			maintainAspectRatio: true,
    			scales: {
    				xAxes: [{
    					categoryPercentage: 0.45,
    					barPercentage: 0.70,
    					display: true,
    					scaleLabel: {
    						display: false,
    						labelString: 'Month'
    					},
    					gridLines: {
    						color: "#d9dffa",
    						drawBorder: false,
    						offsetGridLines: false,
    						drawTicks: false,
    						borderDash: [3, 4],
    						zeroLineWidth: 1,
    						zeroLineColor: "#d9dffa",
    						zeroLineBorderDash: [3, 4]
    					},
    					ticks: {
    						display: true,
    						beginAtZero: true,
    						fontColor: "#afb4d4",
    						fontSize: 13,
    						padding: 10
    					}
    				}],
    				yAxes: [{
    					categoryPercentage: 0.35,
    					barPercentage: 0.70,
    					display: true,
    					scaleLabel: {
    						display: false,
    						labelString: 'Value'
    					},
    					gridLines: {
    						color: "#d9dffa",
    						drawBorder: false,
    						offsetGridLines: false,
    						drawTicks: false,
    						borderDash: [3, 4],
    						zeroLineWidth: 1,
    						zeroLineColor: "#d9dffa",
    						zeroLineBorderDash: [3, 4]
    					},
    					ticks: {           
    						display: true,
    						beginAtZero: true,
    						fontColor: "#afb4d4",
    						fontSize: 13,
    						padding: 10
    					}
    				}]
    			},
    			elements: {
    				line: {
    					tension: 0.2
    				},
    				point: {
    					radius: 4,
    					borderWidth: 12
    				}
    			},
    			tooltips: {
    				enabled: true,
    				intersect: false,
    				mode: 'index',
    				bodySpacing: 5,
    				yPadding: 10,
    				xPadding: 10, 
    				caretPadding: 0,
    				displayColors: false,
    				backgroundColor: "#333333",
    				titleFontColor: '#ffffff', 
    				cornerRadius: 4,
    				footerSpacing: 0,
    				titleSpacing: 0
    			},
    			plugins: {
    				labels: []
    			}
    		}
    	});
    }

    if ($('.invoice-status-chart-data').length && $('#invoice-status-chart').length) {
    	//Invoice Status Chart
    	var chart_invoice_status = JSON.parse($('.invoice-status-chart-data').val()),
    	ctx = document.getElementById("invoice-status-chart").getContext("2d");
    	new Chart(ctx, {
    		type: 'pie',
    		data: {
    			fill: false,
    			datasets: [{
    				data: JSON.parse(chart_invoice_status.value),
    				backgroundColor: ['#0abb87', '#ffb822', '#fd397a', '#A675D4', '#cc5151', '#3483ff']
    			}],
    			labels: JSON.parse(chart_invoice_status.label),
    		},
    		options: {
    			cutoutPercentage: 30,
    			responsive: true,
    			maintainAspectRatio: true,
    			legend: {
    				display: true,
    				position: 'top',
    				labels: {
    					boxWidth: 10,
    					fontSize: 10
    				}
    			},
    			title: {
    				display: false,
    			},
    			animation: {
    				animateScale: true,
    				animateRotate: true
    			},
    			tooltips: {
    				enabled: true,
    				intersect: false,
    				mode: 'nearest',
    				bodySpacing: 5,
    				yPadding: 10,
    				xPadding: 10, 
    				caretPadding: 0,
    				displayColors: false,
    				backgroundColor: '#333',
    				titleFontColor: '#ffffff', 
    				cornerRadius: 4,
    				footerSpacing: 0,
    				titleSpacing: 0
    			},
    			plugins: {
    				labels: [{render: 'percentage', fontColor: "#fff", fontFamily: "'Poppins', sans-serif" }]
    			}
    		}
    	});
    }

    if ($('.expense-category-chart-data').length && $('#expense-category-chart').length) {
   		//Expense by category chart
   		var chart_expense_category = JSON.parse($('.expense-category-chart-data').val()),
   		ctx = document.getElementById("expense-category-chart").getContext("2d");
   		new Chart(ctx, {
   			type: 'pie',
   			data: {
   				fill: false,
   				datasets: [{
   					data: JSON.parse(chart_expense_category.value),
   					backgroundColor: [ '#93e2ff', '#ccc5a8', '#52bacc', '#ffaeae', '#a1ffc3','#fd397a']
   				}],
   				labels: JSON.parse(chart_expense_category.label),
   			},
   			options: {
   				cutoutPercentage: 30,
   				responsive: true,
   				maintainAspectRatio: true,
   				legend: {
   					display: true,
   					position: 'top',
   					labels: {
   						boxWidth: 10,
   						fontSize: 10
   					}
   				},
   				title: {
   					display: false,
   				},
   				animation: {
   					animateScale: true,
   					animateRotate: true
   				},
   				tooltips: {
   					enabled: true,
   					intersect: false,
   					mode: 'nearest',
   					bodySpacing: 5,
   					yPadding: 10,
   					xPadding: 10, 
   					caretPadding: 0,
   					displayColors: false,
   					backgroundColor: '#333',
   					titleFontColor: '#ffffff', 
   					cornerRadius: 4,
   					footerSpacing: 0,
   					titleSpacing: 0
   				},
   				plugins: {
   					labels: [{render: 'percentage', fontColor: "#fff", fontFamily: "'Poppins', sans-serif" }]
   				}
   			}
   		});
   	}

    //Contact chart
    if ($('.contact-chart-data').length && $('#contact-chart').length) {
    	var chart_contacts = JSON.parse($('.contact-chart-data').val()),
    	chart_leads = JSON.parse($('.contact-chart-data').val()),
    	ctx = document.getElementById("contact-chart").getContext("2d"),
    	gradient = ctx.createLinearGradient(0, 0, 0, 240),
    	gradient1 = ctx.createLinearGradient(0, 0, 0, 240);

    	gradient.addColorStop(0, Chart.helpers.color('#3483FF').alpha(1).rgbString());
    	gradient.addColorStop(1, Chart.helpers.color('#c9d2ff').alpha(.2).rgbString());
    	gradient1.addColorStop(0, Chart.helpers.color('#fd397a').alpha(1).rgbString());
    	gradient1.addColorStop(1, Chart.helpers.color('#c9d2ff').alpha(.2).rgbString());
    	new Chart(ctx, {
    		type: 'line',
    		data: {
    			labels: JSON.parse(chart_contacts.label),
    			datasets: [{
    				label: "Contact",
    				fill: true,
    				backgroundColor: gradient,
    				borderColor: '#3483FF',
    				pointHoverRadius: 4,
    				pointHoverBorderWidth: 12,
    				pointBackgroundColor: Chart.helpers.color('#ffffff').alpha(0).rgbString(),
    				pointBorderColor: Chart.helpers.color('#ffffff').alpha(0).rgbString(),
    				pointHoverBackgroundColor: "#3483FF",
    				pointHoverBorderColor: Chart.helpers.color('#000000').alpha(0.2).rgbString(),
    				data: JSON.parse(chart_contacts.value),
    			}]
    		},
    		options: {
    			title: {
    				display: false,
    			},
    			legend: {
    				display: false
    			},
    			responsive: true,
    			maintainAspectRatio: true,
    			scales: {
    				xAxes: [{
    					categoryPercentage: 0.45,
    					barPercentage: 0.70,
    					display: true,
    					scaleLabel: {
    						display: false,
    						labelString: 'Month'
    					},
    					gridLines: {
    						color: "#d9dffa",
    						drawBorder: false,
    						offsetGridLines: false,
    						drawTicks: false,
    						borderDash: [3, 4],
    						zeroLineWidth: 1,
    						zeroLineColor: "#d9dffa",
    						zeroLineBorderDash: [3, 4]
    					},
    					ticks: {
    						display: true,
    						beginAtZero: true,
    						fontColor: "#afb4d4",
    						fontSize: 13,
    						padding: 10
    					}
    				}],
    				yAxes: [{
    					categoryPercentage: 0.35,
    					barPercentage: 0.70,
    					display: true,
    					scaleLabel: {
    						display: false,
    						labelString: 'Value'
    					},
    					gridLines: {
    						color: "#d9dffa",
    						drawBorder: false,
    						offsetGridLines: false,
    						drawTicks: false,
    						borderDash: [3, 4],
    						zeroLineWidth: 1,
    						zeroLineColor: "#d9dffa",
    						zeroLineBorderDash: [3, 4]
    					},
    					ticks: {           
    						display: true,
    						beginAtZero: true,
    						fontColor: "#afb4d4",
    						fontSize: 13,
    						padding: 10
    					}
    				}]
    			},
    			elements: {
    				line: {
    					tension: 0.2
    				},
    				point: {
    					radius: 4,
    					borderWidth: 12
    				}
    			},
    			tooltips: {
    				enabled: true,
    				intersect: false,
    				mode: 'index',
    				bodySpacing: 5,
    				yPadding: 10,
    				xPadding: 10, 
    				caretPadding: 0,
    				displayColors: false,
    				backgroundColor: "#333333",
    				titleFontColor: '#ffffff', 
    				cornerRadius: 4,
    				footerSpacing: 0,
    				titleSpacing: 0
    			},
    			plugins: {
    				labels: []
    			}
    		}
    	});
    }

    //Lead chart
    if ($('.lead-chart-data').length && $('#lead-chart').length) {
        var chart_contacts = JSON.parse($('.lead-chart-data').val()),
        chart_leads = JSON.parse($('.lead-chart-data').val()),
        ctx = document.getElementById("lead-chart").getContext("2d"),
        gradient = ctx.createLinearGradient(0, 0, 0, 240);

        gradient.addColorStop(0, Chart.helpers.color('#fd397a').alpha(1).rgbString());
        gradient.addColorStop(1, Chart.helpers.color('#e8c6d2').alpha(.2).rgbString());

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: JSON.parse(chart_leads.label),
                datasets: [{
                    label: "Leads",
                    fill: true,
                    backgroundColor: gradient,
                    borderColor: '#fd397a',
                    pointHoverRadius: 4,
                    pointHoverBorderWidth: 12,
                    pointBackgroundColor: Chart.helpers.color('#ffffff').alpha(0).rgbString(),
                    pointBorderColor: Chart.helpers.color('#ffffff').alpha(0).rgbString(),
                    pointHoverBackgroundColor: "#3483FF",
                    pointHoverBorderColor: Chart.helpers.color('#000000').alpha(0.2).rgbString(),
                    data: JSON.parse(chart_leads.value),
                }]
            },
            options: {
                title: {
                    display: false,
                },
                legend: {
                    display: false
                },
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    xAxes: [{
                        categoryPercentage: 0.45,
                        barPercentage: 0.70,
                        display: true,
                        scaleLabel: {
                            display: false,
                            labelString: 'Month'
                        },
                        gridLines: {
                            color: "#d9dffa",
                            drawBorder: false,
                            offsetGridLines: false,
                            drawTicks: false,
                            borderDash: [3, 4],
                            zeroLineWidth: 1,
                            zeroLineColor: "#d9dffa",
                            zeroLineBorderDash: [3, 4]
                        },
                        ticks: {
                            display: true,
                            beginAtZero: true,
                            fontColor: "#afb4d4",
                            fontSize: 13,
                            padding: 10
                        }
                    }],
                    yAxes: [{
                        categoryPercentage: 0.35,
                        barPercentage: 0.70,
                        display: true,
                        scaleLabel: {
                            display: false,
                            labelString: 'Value'
                        },
                        gridLines: {
                            color: "#d9dffa",
                            drawBorder: false,
                            offsetGridLines: false,
                            drawTicks: false,
                            borderDash: [3, 4],
                            zeroLineWidth: 1,
                            zeroLineColor: "#d9dffa",
                            zeroLineBorderDash: [3, 4]
                        },
                        ticks: {           
                            display: true,
                            beginAtZero: true,
                            fontColor: "#afb4d4",
                            fontSize: 13,
                            padding: 10
                        }
                    }]
                },
                elements: {
                    line: {
                        tension: 0.2
                    },
                    point: {
                        radius: 4,
                        borderWidth: 12
                    }
                },
                tooltips: {
                    enabled: true,
                    intersect: false,
                    mode: 'index',
                    bodySpacing: 5,
                    yPadding: 10,
                    xPadding: 10, 
                    caretPadding: 0,
                    displayColors: false,
                    backgroundColor: "#333333",
                    titleFontColor: '#ffffff', 
                    cornerRadius: 4,
                    footerSpacing: 0,
                    titleSpacing: 0
                },
                plugins: {
                    labels: []
                }
            }
        });
    }

    //Transaction Chart
    if ($('.transaction-chart-data').length && $('#transaction-chart').length) {
    	var chart_transaction = JSON.parse($('.transaction-chart-data').val()),
    	ctx = document.getElementById("transaction-chart").getContext("2d"),
    	gradient = ctx.createLinearGradient(0, 0, 0, 240),
    	gradient1 = ctx.createLinearGradient(0, 0, 0, 240);
    	gradient.addColorStop(0, Chart.helpers.color('#3483FF').alpha(1).rgbString());
    	gradient.addColorStop(1, Chart.helpers.color('#c9d2ff').alpha(.2).rgbString());
    	gradient1.addColorStop(0, Chart.helpers.color('#fd397a').alpha(1).rgbString());
    	gradient1.addColorStop(1, Chart.helpers.color('#ffbac5').alpha(.2).rgbString());
    	new Chart(ctx, {
    		type: 'bar',
    		data: {
    			labels: JSON.parse(chart_transaction.label),
    			datasets: [{
    				label: "Credit",
    				fill: true,
    				backgroundColor: '#3483FF',
    				borderColor: '#3483FF',
    				pointHoverRadius: 4,
    				pointHoverBorderWidth: 12,
    				pointBackgroundColor: Chart.helpers.color('#ffffff').alpha(0).rgbString(),
    				pointBorderColor: Chart.helpers.color('#ffffff').alpha(0).rgbString(),
    				pointHoverBackgroundColor: "#3483FF",
    				pointHoverBorderColor: Chart.helpers.color('#000000').alpha(0.2).rgbString(),
    				data: JSON.parse(chart_transaction.credit),
    			},
    			{
    				label: "Debit",
    				fill: true,
    				backgroundColor: '#fd397a',
    				borderColor: '#fd397a',
    				pointHoverRadius: 4,
    				pointHoverBorderWidth: 12,
    				pointBackgroundColor: Chart.helpers.color('#000000').alpha(0).rgbString(),
    				pointBorderColor: Chart.helpers.color('#000000').alpha(0).rgbString(),
    				pointHoverBackgroundColor: "#fd397a",
    				pointHoverBorderColor: Chart.helpers.color('#000000').alpha(0.1).rgbString(),
    				data: JSON.parse(chart_transaction.debit),
    			}]
    		},
    		options: {
    			title: {
    				display: false,
    			},
    			legend: {
    				display: false
    			},
    			responsive: true,
    			maintainAspectRatio: true,
    			scales: {
    				xAxes: [{
    					categoryPercentage: 0.45,
    					barPercentage: 0.70,
    					display: true,
    					scaleLabel: {
    						display: false,
    						labelString: 'Month'
    					},
    					gridLines: {
    						color: "#d9dffa",
    						drawBorder: false,
    						offsetGridLines: false,
    						drawTicks: false,
    						borderDash: [3, 4],
    						zeroLineWidth: 1,
    						zeroLineColor: "#d9dffa",
    						zeroLineBorderDash: [3, 4]
    					},
    					ticks: {
    						display: true,
    						beginAtZero: true,
    						fontColor: "#afb4d4",
    						fontSize: 13,
    						padding: 10
    					}
    				}],
    				yAxes: [{
    					categoryPercentage: 0.35,
    					barPercentage: 0.70,
    					display: true,
    					scaleLabel: {
    						display: false,
    						labelString: 'Value'
    					},
    					gridLines: {
    						color: "#d9dffa",
    						drawBorder: false,
    						offsetGridLines: false,
    						drawTicks: false,
    						borderDash: [3, 4],
    						zeroLineWidth: 1,
    						zeroLineColor: "#d9dffa",
    						zeroLineBorderDash: [3, 4]
    					},
    					ticks: {           
    						display: true,
    						beginAtZero: true,
    						fontColor: "#afb4d4",
    						fontSize: 13,
    						padding: 10
    					}
    				}]
    			},
    			elements: {
    				line: {
    					tension: 0.2
    				},
    				point: {
    					radius: 4,
    					borderWidth: 12
    				}
    			},
    			tooltips: {
    				enabled: true,
    				intersect: false,
    				mode: 'index',
    				bodySpacing: 5,
    				yPadding: 10,
    				xPadding: 10, 
    				caretPadding: 0,
    				displayColors: false,
    				backgroundColor: "#333333",
    				titleFontColor: '#ffffff', 
    				cornerRadius: 4,
    				footerSpacing: 0,
    				titleSpacing: 0
    			},
    			plugins: {
    				labels: []
    			}
    		}
    	});
    }

    //Salary Chart
    if ($('.salary-chart-data').length && $('#salary-chart').length) {
    	var chart_salary = JSON.parse($('.salary-chart-data').val()),
    	ctx = document.getElementById("salary-chart").getContext("2d"),
    	gradient = ctx.createLinearGradient(0, 0, 0, 240);
    	gradient.addColorStop(0, Chart.helpers.color('#fec107').alpha(1).rgbString());
    	gradient.addColorStop(1, Chart.helpers.color('#fff0c4').alpha(.2).rgbString());
    	new Chart(ctx, {
    		type: 'line',
    		data: {
    			labels: JSON.parse(chart_salary.label),
    			datasets: [{
    				label: "Revenue",
    				fill: true,
    				backgroundColor: gradient,
    				borderColor: '#fec107',
    				pointHoverRadius: 4,
    				pointHoverBorderWidth: 12,
    				pointBackgroundColor: Chart.helpers.color('#ffffff').alpha(0).rgbString(),
    				pointBorderColor: Chart.helpers.color('#ffffff').alpha(0).rgbString(),
    				pointHoverBackgroundColor: "#fec107",
    				pointHoverBorderColor: Chart.helpers.color('#000000').alpha(0.2).rgbString(),
    				data: JSON.parse(chart_salary.value),
    			}]
    		},
    		options: {
    			title: {
    				display: false,
    			},
    			legend: {
    				display: false
    			},
    			responsive: true,
    			maintainAspectRatio: true,
    			scales: {
    				xAxes: [{
    					categoryPercentage: 0.45,
    					barPercentage: 0.70,
    					display: true,
    					scaleLabel: {
    						display: false,
    						labelString: 'Month'
    					},
    					gridLines: {
    						color: "#d9dffa",
    						drawBorder: false,
    						offsetGridLines: false,
    						drawTicks: false,
    						borderDash: [3, 4],
    						zeroLineWidth: 1,
    						zeroLineColor: "#d9dffa",
    						zeroLineBorderDash: [3, 4]
    					},
    					ticks: {
    						display: true,
    						beginAtZero: true,
    						fontColor: "#afb4d4",
    						fontSize: 13,
    						padding: 10
    					}
    				}],
    				yAxes: [{
    					categoryPercentage: 0.35,
    					barPercentage: 0.70,
    					display: true,
    					scaleLabel: {
    						display: false,
    						labelString: 'Value'
    					},
    					gridLines: {
    						color: "#d9dffa",
    						drawBorder: false,
    						offsetGridLines: false,
    						drawTicks: false,
    						borderDash: [3, 4],
    						zeroLineWidth: 1,
    						zeroLineColor: "#d9dffa",
    						zeroLineBorderDash: [3, 4]
    					},
    					ticks: {           
    						display: true,
    						beginAtZero: true,
    						fontColor: "#afb4d4",
    						fontSize: 13,
    						padding: 10
    					}
    				}]
    			},
    			elements: {
    				line: {
    					tension: 0.2
    				},
    				point: {
    					radius: 4,
    					borderWidth: 12
    				}
    			},
    			tooltips: {
    				enabled: true,
    				intersect: false,
    				mode: 'index',
    				bodySpacing: 5,
    				yPadding: 10,
    				xPadding: 10, 
    				caretPadding: 0,
    				displayColors: false,
    				backgroundColor: "#333333",
    				titleFontColor: '#ffffff', 
    				cornerRadius: 4,
    				footerSpacing: 0,
    				titleSpacing: 0
    			},
    			plugins: {
    				labels: []
    			}
    		}
    	});
    }
});