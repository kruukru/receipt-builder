@extends("admin.templates.default")

@section("content")
	<div class="row" style="margin: 15px 0px 50px;">
		<div class="col-sm-8">
			<h3>Account</h3>
		</div>
		<div class="col-sm-4 text-right">
			<button type="button" class="btn btn-success" style="width: 100px;" onclick="funcOpenAccountModal('new');">New</button>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<table class="table table-bordered table-hover" id="idTableAccount">
				<thead>
					<tr>
						<td>Name</td>
						<td>Username</td>
						<td>Account Type</td>
						<td class="text-center">Action</td>
					</tr>
				</thead>
				<tbody>
					@foreach ($accounts as $account)
					<tr data-thisid="{{$account->id}}">
						<td>{{$account->name}}</td>
						<td>{{$account->username}}</td>
						<td>
							@if ($account->type == 0) 
								Super Admin
							@else 
								Default
							@endif
						</td>
						<td class="text-center">
							<button type="button" class="btn btn-primary" onclick="funcOpenAccountModal('update', this);">Update</button>
							@if ($account->id != 1)
								<button type="button" class="btn btn-danger" id="idButtonRemove" onclick="funcRemoveAccount(this);">Remove</button>
							@endif
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<div class="modal fade" tabindex="-1" role="dialog" id="idDivModalAccount" data-thisaction="" data-thisid="">
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
						<label>Username</label>
    					<input type="text" class="form-control" placeholder="Enter Username" id="idInputUsername">
					</div>
					<div class="form-group">
						<label>Password</label>
						<input type="password" class="form-control" placeholder="Enter Password" id="idInputPassword">
						<label>Confirm Password</label>
						<input type="password" class="form-control" placeholder="Enter Confirm Password" id="idInputPassword2">
					</div>
					<div class="form-group">
						<label>Account Type</label>
						<select class="form-control" id="idSelectType">
							<option value="0">Super Admin</option>
							<option value="1">Default</option>
						</select>
					</div>
	      		</div>
	      		<div class="modal-footer">
	        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        		<button type="button" class="btn btn-success" style="width: 100px;" onclick="funcSaveAccountModal();">Save</button>
	      		</div>
	    	</div>
	  	</div>
	</div>
@endsection
@section('js')
	<script type="text/javascript">
		$(document).ready(function() {
			$('#idTableAccount').DataTable();
		});
		$('#idDivModalAccount').on('hidden.bs.modal', function() {
			$('#idInputName').val("");
			$('#idInputUsername').val("");
			$('#idInputPassword').val("").prop('disabled', false);
			$('#idInputPassword2').val("").prop('disabled', false);
			$('#idSelectType').val(0).prop('disabled', false);
		});

		function funcOpenAccountModal(type, thisElement) {
			if (type == "new") {
				$('#idDivModalAccount .modal-title').text("New Account");
				$('#idDivModalAccount').attr('data-thisaction', "new");
			} else if (type == "update") {
				funcShowToastr("lpw");
				var id = $(thisElement).closest('tr').attr('data-thisid');

				$.ajax({
					type: "GET",
					url: "{{ route('retrieve-account-one') }}",
					data: { id: id, },
					dataType: "JSON",
					success: function(data) {
						//console.log(data);

						$('#idInputName').val(data.name);
						$('#idInputUsername').val(data.username);
						$('#idInputPassword').val("");
						$('#idInputPassword2').val("");
						$('#idSelectType').val(data.type);

						toastr.remove();
					},
					error: function(data) {
						console.log(data);

						funcShowToastr("aueo");
					},
				})

				$('#idDivModalAccount .modal-title').text("Update Account");
				$('#idDivModalAccount').attr('data-thisid', id);
				$('#idDivModalAccount').attr('data-thisaction', "update");
			}

			$('#idDivModalAccount').modal('show');
		}
		function funcSaveAccountModal() {
			funcShowToastr("lpw");
			var type = $('#idDivModalAccount').attr('data-thisaction');
			var id = $('#idDivModalAccount').attr('data-thisid');

			if ($('#idInputPassword').val() != $('#idInputPassword2').val()) {
				funcShowToastr("error", "Password Mismatch", "Error");
				return;
			}

			if (type == "new") {
				var formData = {
					_token: "{{ Session::token() }}",
					action: "new",
					name: $('#idInputName').val(),
					username: $('#idInputUsername').val(),
					password: $('#idInputPassword').val(),
					type: $('#idSelectType').val(),
				};
			} else if (type == "update") {
				var formData = {
					_token: "{{ Session::token() }}",
					id: id,
					action: "update",
					name: $('#idInputName').val(),
					username: $('#idInputUsername').val(),
					password: $('#idInputPassword').val(),
					type: $('#idSelectType').val(),
				};
			}

			$.ajax({
				type: "POST",
				url: "{{ route('admin-account-save') }}",
				data: formData,
				dataType: "JSON",
				success: function(data) {
					//console.log(data);

					if (data.status == "OK") {
						funcShowToastr("success", "Account Saved!", "Success");
						$('#idDivModalAccount').modal('hide');

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
		function funcRemoveAccount(thisElement) {
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
			    				url: "{{ route('admin-account-remove') }}",
			    				data: { _token: "{{ Session::token() }}", id: id, },
			    				dataType: "JSON",
			    				success: function(data) {
			    					funcShowToastr("success", "Account Removed!", "Success");

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