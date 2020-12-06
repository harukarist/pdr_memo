<script src="https://unpkg.com/frappe-charts@latest"></script>

<script>
let heatmap = new frappe.Chart("#heatmap", {
	type: 'heatmap',
	title: "Monthly Distribution",
	data: {
	dataPoints: {
		'1524064033': 8, /* ... */},
						// object with timestamp-value pairs
		start: startDate,
		end: endDate,      // Date objects
	},
	countLabel: 'Level',
	discreteDomains: 0,  // default: 1
	colors: ['#ebedf0', '#c0ddf9', '#73b3f3', '#3886e1', '#17459e'],
				// Set of five incremental colors,
				// preferably with a low-saturation color for zero data;
				// def: ['#ebedf0', '#c6e48b', '#7bc96f', '#239a3b', '#196127']
  });
</script>
