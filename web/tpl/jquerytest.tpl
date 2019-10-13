<style type="text/css">
	.pic {
	width: 100px;
	height: 50px;
	background-color: red;
	margin: 10px;
	}
</style>

<script type="text/javascript">

$(function() {
$('tr.parent')
		.css("cursor","pointer")
		.attr("title","Click to expand/collapse")
		.click(function(){
			$(this).siblings('.child-'+this.id).toggle();
		});
});

</script>




<table>
	<tr class="parent"  id="1">
		<th>Header 1</th>	
	</tr>
	<tr class="child-1">
		<td>Content 1</td>
	</tr>
	<tr class="child-1">
		<td>Content 1</td>
	</tr>
	<tr class="child-1">
		<td>Content 1</td>
	</tr>
	<tr class="parent" id="2">
		<th>Header 2</th>	
	</tr>
	<tr class="child-2">
		<td>Content 2</td>
	</tr>
	<tr class="child-2">
		<td>Content 2</td>
	</tr>
	<tr class="child-2">
		<td>Content 2</td>
	</tr>
	<tr class="parent" id="3">
		<th>Header 3</th>	
	</tr>
	<tr class="child-3">
		<td>Content 3</td>
	</tr>
	<tr class="child-3">
		<td>Content 3</td>
	</tr>
	<tr class="child-3">
		<td>Content 3</td>
	</tr>
</table>