//the namespace for this tutorial
Ext.ns('com.quizzpot.tutorial');

com.quizzpot.tutorial.Charts = {
	init: function(){
		//code here
		var data = [['Ext JS',115000],['jQuery',250100],['Prototype',150000],['mootools',75000],['YUI',95000],['Dojo',20000],['Sizzle',15000]];
		
		var store = new Ext.data.ArrayStore({
			fields:[{name:'framework'},{name:'users', type:'float'}]
		});
		store.loadData(data);
		
		var columnChart = new Ext.chart.ColumnChart({
			store: store,
			//url:'../ext-3.0-rc1/resources/charts.swf',
			xField: 'framework',
			yField: 'users'
		});
		
		var pieChart = new Ext.chart.PieChart({
			store: store,
			dataField: 'users',
			categoryField : 'framework'
		});
		
		var lineChart = new Ext.chart.LineChart({
			store: store,
			xField: 'framework',
			yField: 'users'
		});
		
		var panel1 = new Ext.Panel({
			title: 'Column chart example',
			items:[columnChart]
		});
		
		var panel2 = new Ext.Panel({
			title: 'Line chart example',
			items:[lineChart]
		});
		
		var panel3 = new Ext.Panel({
			title: 'Pie chart example',
			items:[pieChart]
		});
		
		var main = new Ext.Panel({
			renderTo: 'frame',
			width:450,
			defaults: {
				height:250,
				collapsible: true,
				border:false,
				titleCollapse: true
			},
			items: [panel1,panel2,panel3]
		});
		
	}
}

Ext.onReady(com.quizzpot.tutorial.Charts.init,com.quizzpot.tutorial.Charts);