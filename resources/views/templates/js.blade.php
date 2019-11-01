<!-- Jquery 3.4.1 -->
<script type="text/javascript" src="{{ URL::asset('library/js/jquery-3.4.1.min.js') }}"></script>
<!-- Boostrap 4.3.1 -->
<script type="text/javascript" src="{{ URL::asset('library/js/bootstrap.min.js') }}"></script>
<!-- Toastr 2.1.3 -->
<script type="text/javascript" src="{{ URL::asset('library/js/toastr.min.js') }}"></script>
<!-- Datatable 1.10.20 -->
<script type="text/javascript" src="{{ URL::asset('library/js/datatables.min.js') }}"></script>
<!-- JQueryConfirm 3.3.4 -->
<script type="text/javascript" src="{{ URL::asset('library/js/jquery-confirm.min.js') }}"></script>

<script type="text/javascript">
	function funcShowToastr(type, description, title, options) {
		toastr.remove();
		if (type == "success") {
			toastr.success(description, title, options);
		} else if (type == "info") {
			toastr.info(description, title, options);
		} else if (type == "error") {
			toastr.error(description, title, options);
		} else if (type == "aueo") {
			toastr.error("An Unexpected Error Occured!", "Error");
		} else if (type == "lpw") {
			toastr.info("Please Wait", "Loading", {
				timeOut: 0,
			});
		}
	}
</script>