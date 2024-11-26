	<script>
		$(document).on('click', '.edit', function(e) {
			e.preventDefault();
			var id = $(this).parent().siblings()[0].value;
			$.ajax({
				url: "<?php echo base_url(); ?>" + "/getSingleUser/" + id,
				method: "GET",
				success: function(result) {


					var data = JSON.parse(result)
					$(".updateId").val(data.id);
					$(".updateUsername").val(data.name);
					$(".updateEmail").val(data.email);
				}
			})
		})

		$(document).on('click', '.delete', function(e) {
			e.preventDefault();
			var id = $(this).parent().siblings()[0].value;
			var a = confirm("Are you sure want to delete");
			if (a) {
				$.ajax({
					url: "<?php echo base_url(); ?>" + "/deleteUser",
					method: "POST",
					data: {
						id: id
					},
					success: function(res) {
						if (res.includes("1")) {
							return window.location.href = window.location.href;
						}


					}
				})
			}

		})

		$(document).on('click', '.delete_all_data', function() {
			var confirmation = confirm("Are you sure you want to delete?");
			if (confirmation) {
				var checkboxes = $(".data_checkbox:checked");
				console.log(checkboxes);

				if (checkboxes.length > 0) {
					var ids = [];
					checkboxes.each(function() {
						ids.push($(this).val());
					})
					console.log(ids);

					$.ajax({
						url: "<?php echo base_url(); ?>" + "/deleteAllUser",
						method: "POST",
						data: {
							ids: ids
						},
						success: function(result) {
							console.log(result);
							checkboxes.each(function() {
								$(this).parent().parent().parent().hide(100);
							})
						}
					})
				}
			}
		})
	</script>

	<style>
		.logout {
			width: 150px;
			z-index: 10;
			text-align: end;
			font-size: 25px;
			margin-left: 10px;
			/* margin-top: 5px; */

		}

		.logout i {
			color: white;
			/* border: 1px solid white; */
			border-radius: 10px;
			padding: 5px;
		}

		.right {
			height: 10%;
			text-align: center;
			justify-content: center;
		}

		.mainButtons {
			gap: 10px;
		}

		.search form input {
			width: 200px;
		}
	</style>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


	<div class="container-l   ">
		<div class="table-responsive d-flex flex-column ">
			<?php
			if (session()->getFlashData("sucess")) {
			?>
				<div class="alert w-50 align-self-center alert-success alert-dismissible fade show" role="alert">
					<?php echo session()->getFlashData("sucess"); ?>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			<?php
			}
			?>
			<div class=" ">
				<div class="bg-dark px-3 py-2">
					<div class="row px-12 d-flex mt-1 justify-content-between">
						<div class="col-sm-2 text-light">
							<h2 style="font-family:Arial, Helvetica, sans-serif;"><b>CRUD</b></h2>
						</div>
						<div class="right col-sm-2  d-flex ">
							<!-- <div class="search">
								<form class="d-flex" method="post">
									<input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
									<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
								</form>
							</div> -->

							<!-- <div class="search d-flex">
								<input type="text" placeholder="Search">
								<button type="submit">Search</button>
							</div>
							 -->
							<div class="col-sm-2 mainButtons d-flex">
								<a href="#addEmployeeModal" class="btn btn-success pt-1 py-0" data-toggle="modal"><i class="material-icons">&#xE147;</i></a>
								<a href="#deleteEmployeeModal" class="delete_all_data btn pt-1 btn-danger py-0" data-toggle="modal"> <i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
							</div>

							<div class="logout">
								<a href="/"><i class="fa fa-sign-out fa-lg	" aria-hidden="true"></i></a>
							</div>
						</div>
					</div>
				</div>
				<table class="table table1 table-striped table-hover ">
					<thead>
						<tr>
							<th>
								<span class="custom-checkbox">
									<input type="checkbox" id="selectAll">
									<label for="selectAll"></label>
								</span>
							</th>
							<th>Name</th>
							<th>Email</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($users) {
							foreach ($users as $user) {


						?>
								<tr>
									<input type="hidden" id="userId" name="id" value="<?php echo $user['id']; ?>">
									<td>
										<span class="custom-checkbox">
											<input type="checkbox" id="data_checkbox" class="data_checkbox" name="data_checkbox" value="<?php echo $user['id'] ?>">
											<label for="data_checkbox"></label>
										</span>
									</td>
									<td><?php echo $user['name']; ?></td>
									<td><?php echo $user['email']; ?></td>
									<td>
										<a href="#editEmployeeModal" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
										<a href="#deleteEmployeeModal" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
									</td>
								</tr>
						<?php
							}
						}
						?>
					</tbody>
				</table>
				<div class="d-flex justify-content-center align-items-center">
					<ul class="pagination">
						<?= $pager->links('group1', 'bs_pagination'); ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- Add Modal HTML -->
	<div id="addEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="<?php echo base_url() . '/saveUser'; ?>" method="POST">
					<div class="modal-header">
						<h4 class="modal-title">Add Employee</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label>Name</label>
							<input type="text" class="form-control" name="name" required>
						</div>
						<div class="form-group">
							<label>Email</label>
							<input type="email" class="form-control" name="email" required>
						</div>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" name="submit" data-dismiss="modal" value="Cancel">
						<input type="submit" class="btn btn-success" value="Add">
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Edit Modal HTML -->
	<div id="editEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action=<?php echo base_url() . '/updateUser' ?> method="POST">
					<div class="modal-header">
						<h4 class="modal-title">Edit Employee</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
						<input type="hidden" name="updateId" class="updateId">
						<div class="form-group">
							<label>Name</label>
							<input type="text" class="form-control updateUsername" name="name" required>
						</div>
						<div class="form-group">
							<label>Email</label>
							<input type="text" class="form-control updateEmail" name="email" required>
						</div>
					</div>
					<div class="modal-footer">
						<input type="button" name="submit" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="submit" class="btn btn-info" value="Save">
					</div>
				</form>
			</div>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>