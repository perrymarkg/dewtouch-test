<?php
	echo isset($selected) ?
		"<div class=\"alert alert-success\">You have selected: <strong>{$selected}</strong></div>" :
		false;
?>


<div id="message1">


<?php echo $this->Form->create('Type',array('id'=>'form_type','type'=>'file','class'=>'','method'=>'POST','autocomplete'=>'off','inputDefaults'=>array(
				
				'label'=>false,'div'=>false,'type'=>'text','required'=>false)))?>
	
<?php echo __("Hi, please choose a type below:")?>
<br><br>

<?php $options_new = array(
 		'Type1' => __('<span class="showDialog" data-id="dialog_1" style="color:blue">Type1</span><div id="dialog_1" class="hide dialog" title="Type 1">
 				<span style="display:inline-block"><ul><li>Description .......</li>
 				<li>Description 2</li></ul></span>
 				</div>'),
		'Type2' => __('<span class="showDialog" data-id="dialog_2" style="color:blue">Type2</span><div id="dialog_2" class="hide dialog" title="Type 2">
 				<span style="display:inline-block"><ul><li>Desc 1 .....</li>
 				<li>Desc 2...</li></ul></span>
 				</div>')
		);?>

<?php echo $this->Form->input('type', array('legend'=>false, 'type' => 'radio', 'options'=>$options_new,'before'=>'<label class="radio line notcheck">','after'=>'</label>' ,'separator'=>'</label><label class="radio line notcheck">'));?>

<hr>
<?php echo $this->Form->submit(false, array('class' => 'btn btn-primary')); ?>
<?php echo $this->Form->end();?>

</div>

<style>
.showDialog:hover{
	text-decoration: underline;
}

#message1 .radio{
	vertical-align: top;
	font-size: 13px;
}

.control-label{
	font-weight: bold;
}

.wrap {
	white-space: pre-wrap;
}

.dialog {
	position:absolute;
	border:1px solid #cccccc;
	background:#ffffff;
	padding:10px;
}

.dialog:before,
.dialog:after {
	content: '';
    display: block;
    position: absolute;
    
    width: 0;
    height: 0;
    border-style: solid;
	top:0;
	bottom:0;
	margin:auto;
}
.dialog:before {
	border-color: transparent #ccc transparent transparent;
    border-width: 11px;
	left:-23px;
}
.dialog:after {
	border-color: transparent #fff transparent transparent;
    border-width: 10px;
	left:-20px;
}

.dialog ul {
	margin-left:20px;
	margin-bottom:0;
}
</style>

<?php $this->start('script_own')?>
<script>

$(document).ready(function(){
	$dialog = $('.showDialog');

	$dialog.on('hover', function(){

		var $this = $(this);
		var $target = $('#' + $this.data('id'));
		$target.toggleClass('hide')
		
		var positions = getElPositions($this, $target);

		$target.css(positions);
	});

	function getElPositions($el, $target)
	{
		var pLeft = $el.offset().left + $el.width() + 20 + 'px';
		var pTop = $el.offset().top - ($target.height() / 2) + 'px';
		var positions = {};

		positions.left = pLeft;
		positions.top = pTop;
		return positions;
	}

	/* $(".dialog").dialog({
		autoOpen: false,
		width: '500px',
		modal: true,
		dialogClass: 'ui-dialog-blue'
	});

	
	$(".showDialog").click(function(){ var id = $(this).data('id'); $("#"+id).dialog('open'); });
 */
})


</script>
<?php $this->end()?>