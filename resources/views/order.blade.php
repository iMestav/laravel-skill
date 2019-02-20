@extends('layouts.app')

@section('title', 'Order Page')

@section('content')
<div class="container">
    <form action="" id="orderForm">
        <div class="row clearfix">
            <div class="col-md-12 text-right">
                <button type="submit" id="saveOrder" class="btn btn-success pull-left">Save Order</button>
            </div>
        </div>
        <hr>
        <div class="row clearfix">
            <div class="col-md-12">
            <table class="table table-bordered table-hover" id="tableProduct">
                <thead>
                <tr>
                    <th class="text-center"> # </th>
                    <th class="text-center"> Product name </th>
                    <th class="text-center"> Quantity in stock </th>
                    <th class="text-center"> Price per item </th>
                    <th class="text-center"> Datetime </th>
                    <th class="text-center"> Total </th>
                </tr>
                </thead>
                <tbody>
                <tr id='productRow-0'>
                    <td>1</td>
                    <td><input type="text" name='product[]'  placeholder='Enter Product Name' class="form-control" required/></td>
                    <td><input type="number" name='qty[]' placeholder='Enter Qty' class="form-control qty" step="0" min="0" required/></td>
                    <td><input type="number" name='price[]' placeholder='Enter Unit Price' class="form-control price" step="0.00" min="0" required/></td>
                    <td><input type="date" name='date[]' placeholder='Enter Submitted Date' class="form-control date" required/></td>
                    <td><input type="number" name='total[]' placeholder='0.00' class="form-control total" readonly required/></td>
                </tr>
                <tr id='productRow-1'></tr>
                </tbody>
            </table>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12 text-right">
                <button id='deleteRow' class="pull-right btn btn-danger">Delete Last Row</button>
                <button id="addRow" class="btn btn-success pull-right">Add Row</button>
            </div>
        </div>
        <div class="row clearfix" style="margin-top:20px">
            <div class="col-md-8"></div>
            <div class="pull-right col-md-4 col-md-offset-4">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th class="text-center">Sub Total</th>
                    <td class="text-center"><input type="number" name='total_value_before_tax' placeholder='0.00' class="form-control" id="total_value_before_tax" readonly/></td>
                </tr>
                <tr>
                    <th class="text-center">Tax Percent</th>
                    <td class="text-center"><div class="input-group mb-2 mb-sm-0">
                        <input type="number" class="form-control" id="tax" placeholder="0">
                        <div class="input-group-addon">%</div>
                    </div></td>
                </tr>
                <tr>
                    <th class="text-center">Total Tax</th>
                    <td class="text-center"><input type="number" name='total_tax_value' id="total_tax_value" placeholder='0.00' class="form-control" readonly/></td>
                </tr>
                <tr>
                    <th class="text-center">Grand Total</th>
                    <td class="text-center"><input type="number" name='total_value' id="total_value" placeholder='0.00' class="form-control" readonly/></td>
                </tr>
                </tbody>
            </table>
            </div>
        </div>
    </form>
</div>
@endsection


@section('script')
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script>
$(document).ready(function() {
	var i = 1;
	$("#addRow").click(function() {
        b = i-1;
		$('#productRow-'+i).html(
            $('#productRow-'+b).html()
        ).find('td:first-child').html(i+1);
		$('#tableProduct').append('<tr id="productRow-'+(i+1)+'"></tr>');
		i++;
	});
	$("#deleteRow").click(function(){
		if(i > 1) {
			$("#productRow-"+(i-1)).html('');
			i--;
		}
		calc();
	});
	$('#tableProduct tbody').on('keyup change',function(){
		calc();
	});
	$('#tax').on('keyup change',function(){
		calcTotal();
	});

    $('form#orderForm').on('submit', function(event) {
        event.preventDefault();
        var data = $('form#orderForm').serialize();
        // $('#saveOrder').attr("disabled", "true").html('Sending...');
        console.log(data);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('save-order') }}",
            type: "POST",
            data: data,
            success: function(response) {
                $('#saveOrder').attr("disabled", "false").html('Order Send');
                console.log(response);
                alert(response.messsage);
                //location.reload();
            },
            error: function(err) {
                $('#saveOrder').attr("disabled", "false").html('Send');
                alert(data.message);
                // setTimeout(function() { location.reload(); /*window.location = data.route */ }, 1000);
            }
        });
    });
});

function calc() {
	$('#tableProduct tbody tr').each(function(i, element) {
		var html = $(this).html();
		if(html!='')
		{
			var qty = $(this).find('.qty').val();
			var price = $(this).find('.price').val();
			$(this).find('.total').val(qty*price);

			calcTotal();
		}
  });
}

function calcTotal() {
	total=0;
	$('.total').each(function() {
		total += parseInt($(this).val());
	});
	$('#total_value_before_tax').val(total.toFixed(2));
	tax_sum=total/100*$('#tax').val();
	$('#total_tax_value').val(tax_sum.toFixed(2));
	$('#total_value').val((tax_sum+total).toFixed(2));
}
</script>
@endsection
