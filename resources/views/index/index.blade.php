@extends("index.templates.default")

@section("content")
	<div class="row" style="margin-top: 25vh;">
		<div class="col-md-4 offset-md-4">
			<div class="card bg-light">
				<div class="card-header">
					Login
				</div>
				<div class="card-body">
					<div class="form-group">
						<label>Username</label>
    					<input type="text" class="form-control" placeholder="Enter Username" id="idInputUsername">
					</div>
					<div class="form-group">
						<label>Password</label>
						<input type="password" class="form-control" placeholder="Enter Password" id="idInputPassword">
					</div>
				</div>
				<div class="card-footer text-right">
					<button type="button" class="btn btn-secondary" id="idButtonClear">Clear</button>
					<button type="button" class="btn btn-success" id="idButtonLogin">Login</button>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('js')
<script type="text/javascript">
	$('#idButtonLogin').click(function() {
		$('#idButtonLogin').prop('disabled', true);

		funcShowToastr("lpw");
		var formData = {
			_token: "{{ Session::token() }}",
			username: $('#idInputUsername').val(),
			password: $('#idInputPassword').val(),
		};
		$.ajax({
			type: "POST",
			url: "{{ route('login') }}",
			data: formData,
			success: function(data) {
				//console.log(data);

				if (data == "SUCCESS") {
					location.reload();
				} else {
					funcShowToastr("error", "Invalid Username / Password", "Error");
				}
			},
			error: function(data) {
				console.log(data);

				funcShowToastr("aueo");
			},
			complete: function(data) {
				//console.log(data);

				$('#idButtonLogin').prop('disabled', false);
			},
		});
	});

	$('#idInputPassword').keypress(function(e) {
		if (e.which == 13) {
			$('#idButtonLogin').trigger('click');
		}
	});

	$('#idButtonClear').click(function() {
		$('#idInputUsername').val('');
		$('#idInputPassword').val('');
	});
</script>
@endsection