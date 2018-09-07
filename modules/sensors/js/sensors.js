var main = function() {
  "use strict";

  var sensorsData;
  var dataPoints1 = [], dataPoints2 = [], dataPoints3 = [], dataPoints4 = [];
  $.post("scr_sensors.php",
    function(data) {
      sensorsData = jQuery.parseJSON(data);
      $.each(sensorsData, function(index, el) {
        dataPoints1.push({
          x: new Date(el[0]),
          y: parseInt(el[1])
        });
        dataPoints2.push({
          x: new Date(el[0]),
          y: parseInt(el[2])
        });
        dataPoints3.push({
          x: new Date(el[0]),
          y: parseInt(el[3])
        });
        dataPoints4.push({
          x: new Date(el[0]),
          y: parseInt(el[4])
        });
      });
  });

  var chart1 = new CanvasJS.Chart("content1", {
  	animationEnabled: true,
    backgroundColor: "#555",
  	title: {
      fontColor: "#ccc",
  		text: "Температура"
  	},
  	axisX: {
      titleFontColor: "#ccc",
      labelFontColor: "#ccc",
  		title: "Время"
  	},
  	axisY: {
      titleFontColor: "#ccc",
      labelFontColor: "#ccc",
  		title: "Градусы",
  		suffix: "°С"
  	},
  	data: [{
  		type: "line",
  		name: "temperature",
  		connectNullData: true,
  		xValueType: "dateTime",
  		xValueFormatString: "hh:mm",
  		yValueFormatString: "#,##0.##'%'",
      dataPoints : dataPoints1
  		// dataPoints: [
  		// 	{ x: 1528192600, y: 23 },
  		// 	{ x: 1528193800, y: 23 },
  		// 	{ x: 1528194400, y: 24 },
  		// 	{ x: 1528195000, y: 25 },
  		// ]
  	}]
  });
  var chart2 = new CanvasJS.Chart("content2", {
  	animationEnabled: true,
    backgroundColor: "#555",
  	title: {
      fontColor: "#ccc",
  		text: "Влажность"
  	},
  	axisX: {
      titleFontColor: "#ccc",
      labelFontColor: "#ccc",
  		title: "Время"
  	},
  	axisY: {
      titleFontColor: "#ccc",
      labelFontColor: "#ccc",
  		title: "Проценты",
  		suffix: "%"
  	},
  	data: [{
  		type: "line",
  		name: "humidity",
  		connectNullData: true,
  		xValueType: "dateTime",
  		xValueFormatString: "hh:mm",
  		yValueFormatString: "#,##0.##",
      dataPoints : dataPoints2
  	}]
  });
  var chart3 = new CanvasJS.Chart("content3", {
  	animationEnabled: true,
    backgroundColor: "#555",
  	title: {
      fontColor: "#ccc",
  		text: "Диоксид углерода"
  	},
  	axisX: {
      titleFontColor: "#ccc",
      labelFontColor: "#ccc",
  		title: "Время"
  	},
  	axisY: {
      titleFontColor: "#ccc",
      labelFontColor: "#ccc",
  		title: "Частей на миллион",
  		suffix: "ppm(мд)"
  	},
  	data: [{
  		type: "line",
  		name: "co2",
  		connectNullData: true,
  		xValueType: "dateTime",
  		xValueFormatString: "hh:mm",
  		yValueFormatString: "#,##0.##",
      dataPoints : dataPoints3
  	}]
  });
  var chart4 = new CanvasJS.Chart("content4", {
  	animationEnabled: true,
    backgroundColor: "#555",
  	title: {
      fontColor: "#ccc",
  		text: "Свет"
  	},
  	axisX: {
      titleFontColor: "#ccc",
      labelFontColor: "#ccc",
  		title: "Время"
  	},
  	axisY: {
      titleFontColor: "#ccc",
      labelFontColor: "#ccc",
  		title: "Проценты",
  		suffix: "%"
  	},
  	data: [{
  		type: "line",
  		name: "lgt",
  		connectNullData: true,
  		xValueType: "dateTime",
  		xValueFormatString: "hh:mm",
  		yValueFormatString: "#,##0.##'%'",
      dataPoints : dataPoints4
  	}]
  });
  setTimeout(function () {
    chart1.render();
    chart2.render();
    chart3.render();
    chart4.render();
  }, 100);
};
$(document).ready(main);
