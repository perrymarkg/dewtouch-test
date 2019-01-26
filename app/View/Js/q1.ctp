<div class="alert  ">
<button class="close" data-dismiss="alert"></button>
Question: Advanced Input Field</div>

<p>
1. Make the Description, Quantity, Unit price field as text at first. When user clicks the text, it changes to input field for use to edit. Refer to the following video.

</p>


<p>
2. When user clicks the add button at left top of table, it wil auto insert a new row into the table with empty value. Pay attention to the input field name. For example the quantity field

<?php echo htmlentities('<input name="data[1][quantity]" class="">')?> ,  you have to change the data[1][quantity] to other name such as data[2][quantity] or data["any other not used number"][quantity]

</p>



<div class="alert alert-success">
<button class="close" data-dismiss="alert"></button>
The table you start with</div>

<table class="table table-striped table-bordered table-hover">
<thead>
<th><span id="add_item_button" class="btn mini green addbutton" onclick="addToObj=false">
											<i class="icon-plus"></i></span></th>
<th>Description</th>
<th>Quantity</th>
<th>Unit Price</th>
</thead>

<tbody id="tbl_body">
	<tr>
	<td><a href="#" class="remove"><i class="icon-remove"></i></a></td>
	<td class="input-area">
		<textarea name="data[1][description]" class="input m-wrap description required" ></textarea>
	</td>
	<td class="input-area"><input name="data[1][quantity]" class="input"></td>
	<td class="input-area"><input name="data[1][unit_price]"  class="input"></td>
	
</tr>

</tbody>

</table>


<p></p>
<div class="alert alert-info ">
<button class="close" data-dismiss="alert"></button>
Video Instruction</div>

<p style="text-align:left;">
<video width="78%"   controls>
  <source src="/video/q3_2.mov">
Your browser does not support the video tag.
</video>
</p>




<style type="text/css">
/* Perry */
table,
table * {
	box-sizing:border-box;
}
.mini {
	height:auto;
}
.d-none {
  display:none;
}
.input {
	width:100%;
	border:none;
	background:none;
	height:30px;
	margin:0;
	outline:none;
	padding:10px;
}
.input:focus {
	border-bottom:1px solid #ddd;
	background:#ffffff;
}
textarea.m-wrap {
	height:auto;
	resize:none;
	overflow:hidden;
}
</style>
<?php $this->start('script_own');?>
<script>
$(document).ready(function(){

	var $tbl_body = $('#tbl_body');

	$tbl_body.on('keyup', 'textarea.input', function(){
		$(this).css({height: $(this)[0].scrollHeight + 'px'});
	});

	$tbl_body.on('click', 'td.input-area', function(){
		$(this).find('.input').focus();
	});

	$tbl_body.on('click', '.remove', function(e){
		e.preventDefault();
		$(this).parent().parent().remove();
		adjustElementIndex();
	});


	$("#add_item_button").click(function(e){
		e.preventDefault();
		var length = $tbl_body.find('tr').length + 1;
		var tpl = getRowTemplate(length);
		$tbl_body.append(tpl);
		adjustElementIndex();
	});

	function getRowTemplate(length)
	{
		var html = '<tr> \
			<td><a href="#" class="remove"><i class="icon-remove"></i></a></td> \
			<td class="input-area"><textarea name="data[0][description]" class="input m-wrap description required" style="height: 50px;"></textarea></td> \
			<td class="input-area"><input name="data[0][quantity]" class="input"></td> \
			<td class="input-area"><input name="data[0][unit_price]" class="input"></td>'
		return html;
	}

	function adjustElementIndex()
	{
		$tbl_body.find('tr').each(function(index, el){
			$(this).find('.input')
				.each(function(i, i_el) {
					var name = $(this).attr('name');
					var e_index = index + 1;
					name = name.replace(/\[\d+\]/g, '['+ e_index +']');
					
					$(this).attr('name', name);
				});
			
		});
	}

	
});
</script>
<?php $this->end();?>

