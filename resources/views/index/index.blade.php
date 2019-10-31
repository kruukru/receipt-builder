@extends("index.templates.default")

@section("content")
	<div class="row" style="margin-top: 25vh;">
		<div class="col-sm-4 offset-sm-4">
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
					<button type="button" class="btn btn-secondary" onclick="funcPrint();">Print</button>
					<button type="button" class="btn btn-secondary" id="idButtonClear">Clear</button>
					<button type="button" class="btn btn-success" id="idButtonLogin">Login</button>
				</div>
			</div>
		</div>
	</div>
	<div id="idDivTestPrint">
		<!--  -->
	</div>
@endsection
@section('js')
<script type="text/javascript">
	function funcPrint(){
        var S = "#Intent;scheme=rawbt;";
        var P =  "package=ru.a402d.rawbtprinter;end;";
        var textEncoded = encodeURI($('#idDivTestPrint').text());
        window.location.href="intent:"+textEncoded+S+P;
    }


	$('#idButtonLogin').click(function() {
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
			},
		});
	});

	$('#idButtonClear').click(function() {
		$('#idInputUsername').val('');
		$('#idInputPassword').val('');
	});
</script>
@endsection