@extends("admin.templates.default")

@section("content")
	<div class="row" style="margin: 15px 0px 50px;">
		<div class="col-sm-8">
			<h3>Product</h3>
		</div>
		<div class="col-sm-4 text-right">
			<button type="button" class="btn btn-success" style="width: 100px;" onclick="funcOpenProductModal('new');">New</button>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<table class="table table-bordered table-hover" id="idTableProduct">
				<thead>
					<tr>
						<td>Name</td>
						<td>Price</td>
						<td class="text-center">Action</td>
					</tr>
				</thead>
				<tbody>
					@foreach ($products as $product)
					<tr data-thisid="{{$product->id}}">
						<td>{{$product->name}}</td>
						<td>P {{number_format($product->price, 2)}}</td>
						<td class="text-center">
							<button type="button" class="btn btn-primary" onclick="funcOpenProductModal('update', this);">Update</button>
							<button type="button" class="btn btn-danger" id="idButtonRemove" onclick="funcRemoveProduct(this);">Remove</button>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
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
	        		<button type="button" class="btn btn-success" style="width: 100px;" onclick="funcSaveProductModal();">Save</button>
	        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	      		</div>
	    	</div>
	  	</div>
	</div>
@endsection
@section('js')
	<script type="text/javascript">
		$(document).ready(function() {
			$('#idTableProduct').DataTable();
		});

		$('#idInputPrice').on('keyup', function() {
			$('#idSpanPrice').text("- "+priceFormatter.format($(this).val()));
		});

		$('#idDivModalProduct').on('hidden.bs.modal', function() {
			$('#idInputName').val("");
			$('#idInputPrice').val("");
			$('#idSpanPrice').text("")
		});

		function funcOpenProductModal(type, thisElement) {
			if (type == "new") {
				$('#idDivModalProduct .modal-title').text("New Product");
				$('#idDivModalProduct').attr('data-thisaction', "new");
			} else if (type == "update") {
				funcShowToastr("lpw");
				var id = $(thisElement).closest('tr').attr('data-thisid');

				$.ajax({
					type: "GET",
					url: "{{ route('retrieve-product-one') }}",
					data: { id: id, },
					dataType: "JSON",
					success: function(data) {
						//console.log(data);

						$('#idInputName').val(data.name);
						$('#idInputPrice').val(data.price);

						toastr.remove();
					},
					error: function(data) {
						console.log(data);

						funcShowToastr("aueo");
					},
				})

				$('#idDivModalProduct .modal-title').text("Update Product");
				$('#idDivModalProduct').attr('data-thisid', id);
				$('#idDivModalProduct').attr('data-thisaction', "update");
			}

			$('#idDivModalProduct').modal('show');
		}
		function funcSaveProductModal() {
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
					//console.log(data);

					if (data.status == "OK") {
						funcShowToastr("success", "Product Saved!", "Success");
						$('#idDivModalProduct').modal('hide');

						setTimeout(function() {
							location.reload();
						}, 1500);
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
			});
		}
		function funcRemoveProduct(thisElement) {
			var id = $(thisElement).closest('tr').attr('data-thisid');

			$.confirm({
			    title: 'Confirmation!',
			    content: 'Are you sure you want to remove this?',
			    buttons: {
			    	confirm: {
			    		btnClass: 'btn-red',
			    		action: function() {
			    			$.ajax({
			    				type: "POST",
			    				url: "{{ route('product-remove') }}",
			    				data: { _token: "{{ Session::token() }}", id: id, },
			    				dataType: "JSON",
			    				success: function(data) {
			    					funcShowToastr("success", "Product Removed!", "Success");

									setTimeout(function() {
										location.reload();
									}, 1500);
			    				},
			    				error: function(data) {
			    					console.log(data);

			    					funcShowToastr("aueo");
			    				}
			    			});
			    		}
			    	},
			        cancel: function () {
			            
			        },
			    }
			});
		}
	</script>
@endsection