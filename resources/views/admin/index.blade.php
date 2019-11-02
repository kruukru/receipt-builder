@extends("admin.templates.default")
@section("css")
	<style type="text/css">
		.select2-selection__rendered {
			line-height: 34px !important;
		}
		.select2-container .select2-selection--single {
			height: 38px !important;
		}
		.select2-selection__arrow {
			height: 37px !important;
		}
	</style>
@endsection
@section("content")
	<div class="row" style="margin: 15px 0px 50px;">
		<div class="col-sm-10">
			<h3>Order</h3>
		</div>
		<div class="col-sm-2 text-right">
			<button type="button" class="btn btn-primary btn-block" id="idButtonPrint">Print</button>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="idTableOrder">
					<thead>
						<tr>
							<td>Product</td>
							<td>Quantity</td>
							<td>Price</td>
							<td>Total Price</td>
							<td class="text-center">Action</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<select class="classSelectProduct"></select>
							</td>
							<td>
								<input type="number" class="form-control classNumberQuantity" value="1">
							</td>
							<td>
								<input type="number" class="form-control classNumberPrice">
							</td>
							<td>
								<input type="number" class="form-control classNumberTotalPrice">
							</td>
							<td class="text-center">
								<button type="button" class="btn btn-success classButtonAdd">Add</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 text-right">
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
@endsection
@section('js')
	<script type="text/javascript">
		var arrayProduct = {!! $products !!};
		var arraySelect2Product = [{
			"id": "",
			"text": "Select a product",
		}];
		var rowHTML = "\
			<tr>\
				<td>\
					<select class='classSelectProduct'></select>\
				</td>\
				<td>\
					<input type='number' class='form-control classNumberQuantity' value='1'>\
				</td>\
				<td>\
					<input type='number' class='form-control classNumberPrice'>\
				</td>\
				<td>\
					<input type='number' class='form-control classNumberTotalPrice'>\
				</td>\
				<td class='text-center'>\
					<button type='button' class='btn btn-success classButtonAdd'>Add</button>\
					<button type='button' class='btn btn-danger classButtonRemove'>Remove</button>\
				</td>\
			</tr>";
		$(document).ready(function() {
			$.each(arrayProduct, function(index, value) {
				arraySelect2Product.push({
					"id": value.id,
					"text": value.name,
					"price": value.price,
				});
			});
			$('.classSelectProduct').select2({
				width: "100%",
				height: "100px",
				data: arraySelect2Product,
			});
		});

		$('#idTableOrder tbody').on('click', '.classButtonAdd', function() {
			$('#idTableOrder tbody').append(rowHTML);
			$('.classSelectProduct').select2({
				width: "100%",
				height: "100px",
				data: arraySelect2Product,
			});
		});
		$('#idTableOrder tbody').on('click', '.classButtonRemove', function() {
			$(this).closest('tr').remove();
		});
		$('#idTableOrder tbody').on('keyup', '.classNumberPrice, .classNumberQuantity, .classNumberTotalPrice', function() {
			if ($(this).hasClass('classNumberTotalPrice')) {
				funcComputeOverallTotalPrice();
			} else {
				funcComputeTotalPrice($(this).closest('tr'));
			}
		});
		$('#idTableOrder tbody').on('select2:select', '.classSelectProduct', function(e) {
			var $thisTr = $(this).closest('tr');
			$thisTr.find('.classNumberPrice').val(e.params.data.price);
			
			funcComputeTotalPrice($(this).closest('tr'));
		});

		$('#idButtonPrint').click(function() {
			var arrayOrder = []; var checker = false;
			$('#idTableOrder tbody tr').each(function() {
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
				if (formData.quantity == "" || formData.quantity == "0") {
					checker = true;
					funcShowToastr("error", "Invalid Quantity", "Error");
				}
				if (formData.price == "" || formData.price == "0") {
					checker = true;
					funcShowToastr("error", "Invalid Price", "Error");
				}
				if (formData.totalprice == "" || formData.totalprice == "0") {
					checker = true;
					funcShowToastr("error", "Invalid Total Price", "Error");
				}

				arrayOrder.push(formData);
			});

			if (checker) {
				return;
			}

			funcShowToastr("lpw");
			$('#idButtonPrint').prop('disabled', true);
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
					    			// console.log("rawbt:base64,"+data.print64);
					    			window.location.href="rawbt:base64,"+data.print64;
					    		}
					    	},
					    	cancel: {

					    	},
					    }
					});

					$('#idButtonPrint').prop('disabled', false);
				},
				error: function(data) {
					console.log(data);

					funcShowToastr("aueo");
					$('#idButtonPrint').prop('disabled', false);
				},
			});
		});
		$('#idButtonExactAmount').click(function() {
			var totalprice = parseFloat($('#idNumberTotalPrice').val());
			$('#idNumberCashTendered').val(totalprice.toFixed(2));

			funcComputeChange();
		});		
		$('#idNumberCashTendered').keyup(function() {
			funcComputeChange();
		});

		function funcComputeTotalPrice($thisTr) {
			var price = $thisTr.find('.classNumberPrice').val();
			var quantity = $thisTr.find('.classNumberQuantity').val();
			var product = parseFloat(price) * parseFloat(quantity);

			$thisTr.find('.classNumberTotalPrice').val(product.toFixed(2));

			funcComputeOverallTotalPrice();
		}
		function funcComputeOverallTotalPrice() {
			var total = 0;
			$('#idTableOrder tbody tr').each(function() {
				var price = $(this).find('.classNumberTotalPrice').val();

				total = parseFloat(total) + parseFloat(price);
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
			var cash = $('#idNumberCashTendered').val() == "" ? 0 : $('#idNumberCashTendered').val();

			var change =  parseFloat(cash) - parseFloat(total);
			if (change < 0) {
				$('#idSpanBalance').text(priceFormatter.format(change));
				$('#idNumberBalance').val(change);
			} else {
				$('#idSpanChange').text(priceFormatter.format(change));
				$('#idNumberChange').val(change);
			}
			
		}

		// function funcPrint(){
	 //        var S = "#Intent;scheme=rawbt;";
	 //        var P =  "package=ru.a402d.rawbtprinter;end;";
	 //        var textEncoded = encodeURI($('#idDivTestPrint').text());
	 //        window.location.href="intent:"+textEncoded+S+P;
	 //    }
	</script>
@endsection