@extends("admin.templates.default")
@section("css")
<style type="text/css">
.classErrorRedBorder {
	border: 1px solid red;
}
.select2-selection__rendered {
	line-height: 34px !important;
}
.select2-container .select2-selection--single {
	height: 38px !important;
}
.select2-selection__arrow {
	height: 37px !important;
}
.classNumberQuantity, .classNumberPrice, .classNumberTotalPrice {
	min-width: 101px;
}
#idDivContainerOrder .row {
	margin-bottom: 15px;
}
#idDivContainerOrder .row .card-header .row {
	margin-bottom: 0;
}
</style>
@endsection
@section("content")
<div class="row" style="margin: 15px 0px 30px;">
	<div class="col-md-3">
		<h3>Order</h3>
	</div>
	<div class="col-md-3">
		<button type="button" class="btn btn-success btn-block" onclick="funcOpenProductModal('new');" style="margin-bottom: 15px;">New Product</button>
	</div>
	<div class="col-md-3">
		<button type="button" class="btn btn-primary btn-block" id="idButtonViewReceipt" style="margin-bottom: 15px;">Receipt Preview</button>
	</div>
	<div class="col-md-3">
		<button type="button" class="btn btn-success btn-block" id="idButtonAddProduct">Add Product</button>
	</div>
</div>
<div id="idDivContainerOrder">
	<div class="row classDivHeaderRow">
		<div class="col-md-12">
			 <div class="card bg-light">
			 	<div class="card-header">
			 		<div class="row">
			 			<div class="col-md-10 classDivHeaderText" style="font-weight: bold;">
			 				<span id="idSpanHeaderQuantity">1</span> -
			 				<span id="idSpanHeaderProduct"></span> -
			 				<span id="idSpanHeaderPrice"></span>
			 				<span id="idSpanHeaderTotalPrice" style="float: right;"></span>
			 			</div>
			 			<div class="col-md-2">
			 				<button type="button" class="btn btn-danger btn-block classButtonRemove">Remove</button>
			 			</div>
			 		</div>
			 	</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-2">
							<label>Quantity</label>
							<input type="number" class="form-control classNumberQuantity" value="1">
						</div>
						<div class="col-md-6">
							<label>Product</label>
							<select class="form-control classSelectProduct"></select>
						</div>
						<div class="col-md-2">
							<label>Price</label>
							<input type="number" class="form-control classNumberPrice">
						</div>
						<div class="col-md-2">
							<label>Total Price</label>
							<input type="number" class="form-control classNumberTotalPrice">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12 text-right">
		<div class="form-group">
			<h4>
				<span style="font-size: 1rem;">Overall Total Price:</span>
				<span id="idSpanTotalPrice">PHP 0.00</span>
				<input type="hidden" id="idNumberTotalPrice" value="0">
			</h4>
		</div>
		<div class="form-group">
			<h4>
				<span style="font-size: 1rem;">Cash Tendered:</span> 
				<button type="button" class="btn btn-success" id="idButtonExactAmount">Exact Amount</button>
				<input type="number" class="form-control" style="display: inline-block; width: 200px;" id="idNumberCashTendered">
			</h4>
		</div>
		<div class="form-group">
			<h4>
				<span style="font-size: 1rem;">Change:</span>
				<span id="idSpanChange">PHP 0.00</span>
				<input type="hidden" id="idNumberChange" value="0">
			</h4>
		</div>
		<div class="form-group">
			<h4>
				<span style="font-size: 1rem;">Balance:</span>
				<span id="idSpanBalance">PHP 0.00</span>
				<input type="hidden" id="idNumberBalance" value="0">
			</h4>
		</div>
	</div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="idDivModalProduct" data-thisaction="" data-thisid="">
  	<div class="modal-dialog modal-dialog-centered" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title"></h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
         			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
      			<div class="form-group">
      				<label>Name</label>
      				<input type="text" class="form-control" placeholder="Enter Name" id="idInputName">
      			</div>
      			<div class="form-group">
					<label>Price <span id="idSpanPrice"></span></label>
					<input type="number" class="form-control" placeholder="Enter Price" id="idInputPrice">
				</div>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-success" style="width: 100px;" onclick="funcSaveProductModal();" id="idButtonSave">Save</button>
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      		</div>
    	</div>
  	</div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="idDivModalReceiptPreview">
  	<div class="modal-dialog modal-dialog-centered" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title">Receipt Preview</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
         			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body"></div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-success" id="idButtonPrintReceipt" style="width: 100px;">Print</button>
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      		</div>
    	</div>
  	</div>
</div>
@endsection
@section('js')
<script type="text/javascript">
var arrayProduct = {!! $products !!};
var arraySelect2Product = [{
	"id": "",
	"text": "Select a product",
}];
var rowHTML = "\
	<div class='row classDivHeaderRow'>\
		<div class='col-md-12'>\
			 <div class='card bg-light'>\
			 	<div class='card-header'>\
			 		<div class='row'>\
			 			<div class='col-md-10 classDivHeaderText' style='font-weight: bold;'>\
			 				<span id='idSpanHeaderQuantity'>1</span> -\
			 				<span id='idSpanHeaderProduct'></span> -\
			 				<span id='idSpanHeaderPrice'></span>\
			 				<span id='idSpanHeaderTotalPrice' style='float: right;'></span>\
			 			</div>\
			 			<div class='col-md-2'>\
			 				<button type='button' class='btn btn-danger btn-block classButtonRemove'>Remove</button>\
			 			</div>\
			 		</div>\
			 	</div>\
				<div class='card-body'>\
					<div class='row'>\
						<div class='col-md-2'>\
							<label>Quantity</label>\
							<input type='number' class='form-control classNumberQuantity' value='1'>\
						</div>\
						<div class='col-md-6'>\
							<label>Product</label>\
							<select class='form-control classSelectProduct'></select>\
						</div>\
						<div class='col-md-2'>\
							<label>Price</label>\
							<input type='number' class='form-control classNumberPrice'>\
						</div>\
						<div class='col-md-2'>\
							<label>Total Price</label>\
							<input type='number' class='form-control classNumberTotalPrice'>\
						</div>\
					</div>\
				</div>\
			</div>\
		</div>\
	</div>";
$(document).ready(function() {
	$.each(arrayProduct, function(index, value) {
		arraySelect2Product.push({
			"id": value.id,
			"text": value.name+" (PHP "+parseFloat(value.price).toFixed(2)+")",
			"price": value.price,
		});
	});
	$('.classSelectProduct').select2({
		width: "100%",
		height: "100px",
		data: arraySelect2Product,
		tags: true,
		createTag: function(params) {
			return {
				id: "new-"+params.term,
			    text: params.term,
			    newOption: true,
			};
		}
	});
});

$('#idDivContainerOrder').on('click', '.classButtonRemove', function() {
	$(this).closest('.classDivHeaderRow').remove();
	funcComputeOverallTotalPrice();
});
$('#idDivContainerOrder').on('focus', '.classNumberPrice, .classNumberQuantity, .classNumberTotalPrice', function() {
	$(this).removeClass('classErrorRedBorder');
	$(this).val("");
});
$('#idDivContainerOrder').on('blur', '.classNumberPrice, .classNumberQuantity, .classNumberTotalPrice', function() {
	var thisData = parseFloat($(this).val());
	if (isNaN(thisData)) {
		$(this).addClass('classErrorRedBorder');

		if ($(this).hasClass('classNumberPrice')) {
			funcShowToastr("error", "Invalid Price", "Error");
		} else if ($(this).hasClass('classNumberQuantity')) {
			funcShowToastr("error", "Invalid Quantity", "Error");
		} else if ($(this).hasClass('classNumberTotalPrice')) {
			funcShowToastr("error", "Invalid Total Price", "Error");
		}
	}
});
$('#idDivContainerOrder').on('keyup', '.classNumberPrice, .classNumberQuantity, .classNumberTotalPrice', function() {
	$thisRow = $(this).closest('.classDivHeaderRow');

	if ($(this).hasClass('classNumberTotalPrice')) {
		var quantity = parseFloat($thisRow.find('.classNumberQuantity').val());
		if (isNaN(quantity)) {
			quantity = 0;
		}
		var totalprice = parseFloat($(this).val());
		if (isNaN(totalprice)) {
			totalprice = 0;
		}

		$thisRow.find('.classNumberPrice').val(totalprice / quantity);

		funcComputeOverallTotalPrice();
	} else {
		funcComputeTotalPrice($thisRow);
	}
	
	if ($(this).hasClass('classNumberPrice')) {
		$thisRow.find('.classDivHeaderText #idSpanHeaderPrice').text($(this).val());
	} else if ($(this).hasClass('classNumberQuantity')) {
		$thisRow.find('.classDivHeaderText #idSpanHeaderQuantity').text($(this).val());
	} else if ($(this).hasClass('classNumberTotalPrice')) {
		$thisRow.find('.classDivHeaderText #idSpanHeaderTotalPrice').text(priceFormatter.format($(this).val()));
	}
});
$('#idDivContainerOrder').on('select2:select', '.classSelectProduct', function(e) {
	var $thisRow = $(this).closest('.classDivHeaderRow');

	$thisRow.find('.classDivHeaderText #idSpanHeaderProduct').text(e.params.data.text);
	$thisRow.find('.classDivHeaderText #idSpanHeaderPrice').text(e.params.data.price);
	$thisRow.find('.classNumberPrice').val(e.params.data.price).removeClass('classErrorRedBorder');
	$thisRow.find('.classNumberTotalPrice').removeClass('classErrorRedBorder');

	funcComputeTotalPrice($thisRow);
});

$('#idButtonAddProduct').click(function() {
	$('#idDivContainerOrder').prepend(rowHTML);
	$('.classSelectProduct').select2({
		width: "100%",
		height: "100px",
		data: arraySelect2Product,
		tags: true,
		createTag: function(params) {
			return {
				id: "new-"+params.term,
			    text: params.term,
			    newOption: true,
			};
		}
	});
});
$('#idButtonViewReceipt').click(function() {
	var arrayOrder = []; var checker = false; var strOutHTML = "";
	$('#idDivContainerOrder .classDivHeaderRow').each(function() {
		var formData = {
			id: $(this).find('.classSelectProduct').val(),
			quantity: $(this).find('.classNumberQuantity').val(),
			price: $(this).find('.classNumberPrice').val(),
			totalprice: $(this).find('.classNumberTotalPrice').val(),
		};
		if (formData.id == "") {
			funcShowToastr("error", "Invalid Product", "Error");
			checker = true;
		}
		if (isNaN(parseFloat(formData.quantity))) {
			checker = true;
			funcShowToastr("error", "Invalid Quantity", "Error");
		}
		if (isNaN(parseFloat(formData.price))) {
			checker = true;
			funcShowToastr("error", "Invalid Price", "Error");
		}
		if (isNaN(parseFloat(formData.totalprice))) {
			checker = true;
			funcShowToastr("error", "Invalid Total Price", "Error");
		}

		strOutHTML += "<div><b>"+formData.quantity+"</b> - "+$(this).find('.classSelectProduct').select2('data')[0].text+"</div><div class='text-right'><b>"+(parseFloat(formData.quantity)*parseFloat(formData.price)).toFixed(2)+"</b></div>";
		arrayOrder.push(formData);
	});
	strOutHTML += "<hr>";
	strOutHTML += "<div><b>Total</b></div><div class='text-right'><b>"+parseFloat($('#idNumberTotalPrice').val()).toFixed(2)+"</b></div>";
	strOutHTML += "<div><b>Cash Tendered</b></div><div class='text-right'><b>"+parseFloat($('#idNumberCashTendered').val() == "" ? 0 : $('#idNumberCashTendered').val()).toFixed(2)+"</b></div>";
	strOutHTML += "<div><b>Change</b></div><div class='text-right'><b>"+parseFloat($('#idNumberChange').val()).toFixed(2)+"</b></div>";
	strOutHTML += "<div><b>Balance</b></div><div class='text-right'><b>"+parseFloat($('#idNumberBalance').val()).toFixed(2)+"</b></div>";

	if (checker) {
		return;
	}

	$('#idDivModalReceiptPreview .modal-body').html(strOutHTML);
	$('#idDivModalReceiptPreview').modal('show');
});
$('#idButtonExactAmount').click(function() {
	var totalprice = parseFloat($('#idNumberTotalPrice').val());
	$('#idNumberCashTendered').val(totalprice.toFixed(2));

	funcComputeChange();
});		
$('#idNumberCashTendered').keyup(function() {
	funcComputeChange();
});
$('#idNumberCashTendered').focus(function() {
	$(this).val("");
});
$('#idInputPrice').keyup(function() {
	$('#idSpanPrice').text("- "+priceFormatter.format($(this).val()));
});
$('#idInputPrice').focus(function() {
	$(this).val("");
});
$('#idInputPrice').keypress(function(e) {
	if (e.which == 13) {
		funcSaveProductModal();
	}
});
$('#idButtonPrintReceipt').click(function() {
	$('#idButtonPrintReceipt').prop('disabled', true);
	funcShowToastr("lpw");

	var arrayOrder = [];
	$('#idDivContainerOrder .classDivHeaderRow').each(function() {
		var formData = {
			id: $(this).find('.classSelectProduct').val(),
			quantity: $(this).find('.classNumberQuantity').val(),
			price: $(this).find('.classNumberPrice').val(),
			totalprice: $(this).find('.classNumberTotalPrice').val(),
		};
		arrayOrder.push(formData);
	});
	var formData = {
		_token: "{{ Session::token() }}",
		totalprice: $('#idNumberTotalPrice').val(),
		cashtendered: $('#idNumberCashTendered').val() == "" ? "0" : $('#idNumberCashTendered').val(),
		change: $('#idNumberChange').val(),
		balance: $('#idNumberBalance').val(),
		arrayorder: arrayOrder,
	};
	$.ajax({
		type: "POST",
		url: "{{ route('print-receipt') }}",
		data: formData,
		dataType: "JSON",
		success: function(data) {
			//console.log(data);

			toastr.remove();
			$.confirm({
			    title: 'Confirmation!',
			    content: 'Are you sure you want to print this?',
			    buttons: {
			    	confirm: {
			    		btnClass: 'btn-green',
			    		action: function() {
			    			// console.log(data);
			    			// window.location.href="rawbt:base64,"+data.print64;
			    			var textEncoded = encodeURI(data.print);
			    			window.location.href="intent://"+textEncoded+"#Intent;scheme=quickprinter;package=pe.diegoveloper.printerserverapp;end;";
			    		}
			    	},
			    	cancel: {

			    	},
			    }
			});

			$('#idButtonPrintReceipt').prop('disabled', false);
		},
		error: function(data) {
			console.log(data);

			funcShowToastr("aueo");
			$('#idButtonPrintReceipt').prop('disabled', false);
		},
	});
});

$('#idDivModalProduct').on('hidden.bs.modal', function() {
	$('#idInputName').val("");
	$('#idInputPrice').val("");
	$('#idSpanPrice').text("")
});

function funcComputeTotalPrice($thisRow) {
	var quantity = parseFloat($thisRow.find('.classNumberQuantity').val());
	if (isNaN(quantity)) {
		quantity = 0;
	}
	var price = parseFloat($thisRow.find('.classNumberPrice').val());
	if (isNaN(price)) {
		price = 0;
	}
	
	var product = price * quantity;
	$thisRow.find('.classNumberTotalPrice').val(product.toFixed(2));
	$thisRow.find('.classDivHeaderText #idSpanHeaderTotalPrice').text(priceFormatter.format(product));

	funcComputeOverallTotalPrice();
}
function funcComputeOverallTotalPrice() {
	var total = 0;
	$('#idDivContainerOrder .classDivHeaderRow').each(function() {
		var totalprice = parseFloat($(this).find('.classNumberTotalPrice').val());
		if (isNaN(totalprice)) {
			totalprice = 0;
		}

		total = parseFloat(total) + totalprice;
	});
	$('#idSpanTotalPrice').text(priceFormatter.format(total));
	$('#idNumberTotalPrice').val(total);

	funcComputeChange();
}
function funcComputeChange() {
	$('#idSpanBalance').text(priceFormatter.format(0));
	$('#idNumberBalance').val(0)
	$('#idSpanChange').text(priceFormatter.format(0));
	$('#idNumberChange').val(0);

	var total = $('#idNumberTotalPrice').val();
	var cash = parseFloat($('#idNumberCashTendered').val());
	if (isNaN(cash)) {
		cash = 0;
	}

	var change =  parseFloat(cash) - parseFloat(total);
	if (change < 0) {
		$('#idSpanBalance').text(priceFormatter.format(change));
		$('#idNumberBalance').val(change);
	} else {
		$('#idSpanChange').text(priceFormatter.format(change));
		$('#idNumberChange').val(change);
	}
}
function funcOpenProductModal(type) {
	if (type == "new") {
		$('#idDivModalProduct .modal-title').text("New Product");
		$('#idDivModalProduct').attr('data-thisaction', "new");
	}

	$('#idDivModalProduct').modal('show');
}
function funcSaveProductModal() {
	$('#idButtonSave').prop('disabled', true);

	funcShowToastr("lpw");
	var type = $('#idDivModalProduct').attr('data-thisaction');
	var id = $('#idDivModalProduct').attr('data-thisid');

	if (type == "new") {
		var formData = {
			_token: "{{ Session::token() }}",
			action: "new",
			name: $('#idInputName').val(),
			price: $('#idInputPrice').val(),
		};
	} else if (type == "update") {
		var formData = {
			_token: "{{ Session::token() }}",
			id: id,
			action: "update",
			name: $('#idInputName').val(),
			price: $('#idInputPrice').val(),
		};
	}

	$.ajax({
		type: "POST",
		url: "{{ route('product-save') }}",
		data: formData,
		dataType: "JSON",
		success: function(data) {
			// console.log(data);

			if (data.status == "OK") {
				funcShowToastr("success", "Product Saved!", "Success");
				$('#idDivModalProduct').modal('hide');

				arraySelect2Product.push({
					"id": data.data.id,
					"text": data.data.name+" (PHP "+parseFloat(data.data.price).toFixed(2)+")",
					"price": data.data.price,
				});
				$('.classSelectProduct').select2({
					width: "100%",
					height: "100px",
					data: arraySelect2Product,
					tags: true,
					createTag: function(params) {
						return {
							id: "new-"+params.term,
						    text: params.term,
						    newOption: true,
						};
					}
				});
			} else {
				var str = "";
				$.each(data.data, function(index, value) {
					str += value+"<br>";
				});
				funcShowToastr("error", str, "Error", {
					escapeHtml: false,
				});
			}
		},
		error: function(data) {
			console.log(data);

			funcShowToastr("aueo");
		},
		complete: function(data) {
			// console.log(data);

			$('#idButtonSave').prop('disabled', false);
		},
	});
}
// function funcPrint(){
//        var S = "#Intent;scheme=rawbt;";
//        var P =  "package=ru.a402d.rawbtprinter;end;";
//        var textEncoded = encodeURI($('#idDivTestPrint').text());
//        window.location.href="intent:"+textEncoded+S+P;
//    }
</script>
@endsection