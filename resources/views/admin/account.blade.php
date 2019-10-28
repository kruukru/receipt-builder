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
					<tr>
						<td>{{$account->name}}</td>
						<td>{{$account->username}}</td>
						<td>
							@if ($account->type == 0) 
								Super Admin
							@elseif ($account->type == 1)
								Admin
							@else 
								Normal
							@endif
						</td>
						<td class="text-center">
							<button type="button" class="btn btn-primary">Update</button>
							@if ($account->type != 0)
								<button type="button" class="btn btn-danger">Remove</button>
							@endif
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<div class="modal fade" tabindex="-1" role="dialog" id="idDivModalAccount" data-thisaction="">
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
							<option value="1">Admin</option>
							<option value="2">Normal</option>
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

		function funcOpenAccountModal(type) {
			if (type == "new") {
				$('#idDivModalAccount .modal-title').text("New Account");
				$('#idDivModalAccount').attr('data-thisaction', "new");
			}

			$('#idDivModalAccount').modal('show');
		}
		function funcSaveAccountModal() {
			var type = $('#idDivModalAccount').attr('data-thisaction');

			if ($('#idInputPassword').val() != $('#idInputPassword2').val()) {
				toastr.error("Password Mismatch");
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

				$.ajax({
					type: "POST",
					url: "{{ route('admin-account-save') }}",
					data: formData,
					dataType: "JSON",
					success: function(data) {
						console.log(data);
					},
					error: function(data) {
						console.log(data);
					},
					complete: function(data) {
						//console.log(data);
					},
				});
			}
		}
	</script>
@endsection