<!doctype html>
<html lang="en">
<head>
    <title>Ajax Crud</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h6>Add Data</h6>
                    </div>
                    <form id="contactForm" class="p-2">
                        @csrf
                        <!-- Include CSRF token for Laravel -->
                        <div>
                            <label for="name">Name:</label>
                            <input type="text" id="name" class="form-control" name="name" required>
                        </div>
                        <div>
                            <label for="email">Email:</label>
                            <input type="email" id="email" class="form-control" name="email" required>
                        </div>
                        <div>
                            <label for="number">Number:</label>
                            <input type="text" id="number" class="form-control" name="number" required>
                        </div>
                        <button type="submit" class="btn btn-success mt-2">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <table id="dataTable" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Number</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contacts as $contact)
            <tr>
                <td>{{$contact->name ?? '' }}</td>
                <td>{{$contact->email ?? '' }}</td>
                <td>{{$contact->number ?? '' }}</td>
                <td>
                    <button class="btn btn-success btn-sm editBtn" data-toggle="modal" data-id="{{ $contact->id }}" data-target="#modelId">Edit</button>
                    <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $contact->id }}">Delete</button>
                </td>
            </tr>
            @endforeach
            <!-- Modal -->
            <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Data</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editForm" class="p-2">
                                @csrf
                                <input type="hidden" name="id" id="id">
                                <div>
                                    <label for="name">Name</label>
                                    <input type="text" id="editname" class="form-control" name="name" required>
                                </div>
                                <div>
                                    <label for="email">Email</label>
                                    <input type="email" id="editemail" class="form-control" name="email" required>
                                </div>
                                <div>
                                    <label for="number">Number</label>
                                    <input type="text" id="editnumber" class="form-control" name="number" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </tbody>
    </table>
    <div id="responseMessage"></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#contactForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('contact.store') }}",
                    type:"POST",
                    data:{
                        name:$('#name').val(),
                        email:$('#email').val(),
                        number:$('#number').val(),
                    },
                    success:function(response){
                        $('#responseMessage').html('<p>' + response.message +'</p>');
                        $('#contactForm')[0].reset();
                        $('#dataTable').load(window.location.href + ' #dataTable');
                    },
                    error:function(){
                        $('#responseMessage').html('<p> Data Not Add </p>');

                    },
                });
            });

            // $('.editBtn').on('click',function(){
            //     var id = $(this).data('id');
            //     $.ajax({
            //         type:"GET",
            //         url:"{{ route('contact.edit','') }}/" +id,
            //         success:function(response){
            //             var data = response.contact;
            //             $('#id').val(data.id);
            //             $('#editname').val(data.name);
            //             $('#editemail').val(data.email);
            //             $('#editnumber').val(data.number);
            //         },
            //         error: function(xhr, status, error) {
            //             console.error(xhr.responseText);
            //         }
            //     });
            // });

            $(document).on('click', '.editBtn', function() {
                var id = $(this).data('id');

                $.ajax({
                    type: "GET",
                    url: "{{ route('contact.edit', '') }}/" + id,
                    success: function(response) {
                        var data = response.contact;

                        // Populate the form fields with the data
                        $('#id').val(data.id);
                        $('#editname').val(data.name);
                        $('#editemail').val(data.email);
                        $('#editnumber').val(data.number);

                        // Show the modal
                        $('#modelId').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });

    </script>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#editForm').on('submit', function(e) {
                e.preventDefault();
                var id = $('#id').val();
                var url = "{{ route('contact.update', '') }}/" + id; // Correct URL construction

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        name: $('#editname').val(),
                        email: $('#editemail').val(),
                        number: $('#editnumber').val(),
                    },
                    success: function(response) {
                        $('#responseMessage').html('<p>' + response.message + '</p>');
                        $('#editForm')[0].reset();
                        $('#modelId').modal('hide');
                        $('#dataTable').load(window.location.href + ' #dataTable');
                    },
                    error: function(xhr) {
                        $('#responseMessage').html('<p>Data Not Added. Error: ' + xhr.responseText + '</p>');
                        console.error(xhr); // Log the error for debugging
                    },
                });
            });

            $(document).on('click', '.deleteBtn', function() {
                var deleteId = $(this).data('id');
                $.ajax({
                    type: "GET",
                    url: "{{ route('contact.delete', '') }}/" + deleteId,
                    success: function(response) {
                        if (response.contact) {
                            $('#dataTable').load(window.location.href + ' #dataTable');
                        }
                    },
                    error: function(xhr) {
                        console.error("Error:", xhr.responseText);
                    }
                });
            });

        });
    </script>

</body>
</html>
