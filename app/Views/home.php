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
		// In your Javascript (external .js resource or <script> tag)
		$(document).ready(function() {
			$('.js-example-basic-single').select2();
		});
	</script>


	<head>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	</head>

	<div class="container-l  ">
		<div class="table-responsive d-flex  flex-column ">
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

			<div class=" main-container">
				<?php if (session()->getFlashdata('error')): ?>
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<?= session()->getFlashdata('error') ?>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				<?php endif; ?>

				<?php if (session()->getFlashdata('success')): ?>
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						<?= session()->getFlashdata('success') ?>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				<?php endif; ?>

				<!-- Navbar  -->
				<div class="main-head px-4 py-2  pt-4">
					<div class="row px-12">
						<div class=" text-light logo">
							<div class="toppest-div d-flex justify-content-between">
								<a href="/dashboard">
									<h5 style="font-family:Arial, Helvetica, sans-serif; cursor:pointer; margin-bottom:20px">Client data</h5>
								</a>
								
							</div>
						</div>
						<div class="right">
							<div class="search">
								<form class="d-flex" method="get">
									<input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="search">
									<button class="btn btn-primary my-2 my-sm-0" type="submit"><i class="fas fa-search"></i>
									</button>
								</form>
							</div>


							<div class="secondDiv">
								<div class="filter d-flex mt-2">
									<form action="">
										<button type="button" class="dropdown-toggle " style="  text-transform: capitalize; color:dodgerblue; border:1px solid lightgrey; padding:5px 20px; background-color:white; border-radius:5px; padding: 4px 25px" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@fat"><span  class="caret"></span> Filter</button>
									</form>
									

									<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">

												<div class="modal-header">
													<h1 class="modal-title fs-5" id="exampleModalLabel">Filter</h1>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
												<div class="modal-body filter">
													<form action="/filter" method="post" name="filter">
														<div class="mb-3 text-sm-left filterSelect">
															<label for="idFilter" class="col-form-label">Id:</label>
															<select name="idFilter" id="" class="col-form-label form-select">
																<option value="Select">Select</option>
																<?php if ($all_users) {
																	foreach ($all_users  as $user) { ?>
																		<option value="<?php echo $user['id']; ?>"><?php echo $user['id']; ?></option>
																<?php }
																} ?>
															</select>

														</div>
														<div class="mb-3 text-sm-left filterSelect">
															<label for="nameFilter" class="col-form-label">Name:</label>
															<select name="nameFilter" id="" class="col-form-label  form-select">
																<option value="Select">Select</option>
																<?php if ($all_users) {
																	foreach ($all_users as $user) { ?>
																		<option value="<?php echo $user['name'] ?>"><?php echo $user['name'] ?></option>
																<?php }
																} ?>

															</select>
														</div>
														<div class="mb-3 text-sm-left filterSelect  ">
															<label for="emailFilter" class="col-form-label font-family">Email:</label>
															<select name="emailFilter" id="" class="col-form-label form-select">
																<option value="Select">Select</option>
																<?php if ($all_users) {
																	foreach ($all_users as $user) { ?>
																		<option value="<?php echo $user["email"] ?>"><?php echo $user["email"]; ?></option>
																<?php }
																} ?>

															</select>
														</div>

												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
													<button type="submit" class="btn btn-primary">Filter</button>
												</div>
												</form>

											</div>
										</div>

									</div>
									<div class=" mainButtons  d-flex ml-4">
										<a href="#addEmployeeModal" style="padding:4px 25px" class="btn btn-outline-secondary BTN  text-capitalize  " data-toggle="modal">Add </a>
										<a href="#deleteEmployeeModal" style="padding:4px 25px" class="delete_all_data text-capitalize btn BTN btn-danger" data-toggle="modal"> Delete</a>
									</div>
								</div>
								<div class="div d-flex data-btns justify-content-end">
									<div class="dataButtons">
										<div class="uploadData">
											<form action="/UploadData">
												<button type="button" class="btn btn-primary" style="text-transform: capitalize; width:max-content;" data-toggle="modal" data-target="#exampleModalCenter">
													+ Upload
												</button>
											</form>
										</div>
										<div class="downloadData">
											<form action="/ExportData" method="get">
												<button type="submit" style="text-transform: capitalize; color:dodgerblue; border:1px solid lightgrey" class="btn ">Export</button>
											</form>

											<P><?php echo session()->getFlashData("message") ?></P>

										</div>
									</div>
									<div class="dropdown logout  justify-content-end d-flex ml-3  ">
									<button class=" border-0 bg-light text-dark " style="height: 0;" type="button" data-bs-toggle="dropdown" aria-expanded="false">
										&vellip;
									</button>
									<ul class="dropdown-menu">
										<li><a class="dropdown-item" href="/">Logout</a></li>
									</ul>
								</div>
								</div>
							</div>
						</div>


						<!-- <div class="logout col-sm-12 justify-content-end d-flex col-4" style="cursor:pointer;">
							<a><i class="fa fa-sign-out fa-lg	" aria-hidden="true"></i></a>
						</div> -->


					</div>
				</div>
				<table class="table table1  table-hover ">
					<thead class="thead bg-danger">
						<tr class="mainHead bg-danger ">
							<th class="">
								<span class="custom-checkbox ">
									<input type="checkbox" id="selectAll">
									<label for="selectAll"></label>
								</span>
							</th>
							<th class=" fs-5  border-left">ID</th>
							<th class=" fs-5 border-left">Name</th>
							<th class=" fs-5 border-left">Email</th>
							<th class=" fs-5 border-left ">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($users) {
							foreach ($users as $user) {
						?>
								<tr class="tr">
									<input type="hidden" id="userId" name="id" value="<?php echo $user['id']; ?>">
									<td>
										<span class="custom-checkbox">
											<input type="checkbox" id="data_checkbox" class="data_checkbox" name="data_checkbox" value="<?php echo $user['id'] ?>">
											<label for="data_checkbox"></label>
										</span>
									</td>
									<td class=" border-left"><?php echo $user['id'];  ?></td>
									<td class=" border-left"><?php echo $user['name'];  ?></td>
									<td class=" border-left"><?php echo $user['email']; ?></td>
									<td class=" border-left">
										<a href="#editEmployeeModal" class="edit " data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
										<a href="#deleteEmployeeModal" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
									</td>
								</tr>
							<?php
							}
						} else { ?>
							<tr>
								<td class="text-danger " colspan="5" style="text-align: center; font-size:25px;">No user found</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<div class="footer d-flex justify-content-center ">
					<div class=" d-inline  pagination justify-content-center align-items-center">
						<ul class="pagination">
							<?= $pager->links('group1', 'bs_pagination'); ?>
						</ul>
					</div>


					<!-- Modal -->
					<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content">
								<form action="<?= base_url('UploadData') ?>" method="post" enctype="multipart/form-data">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalCenterTitle">Upload your file</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<div class="input-group mb-3">
											<div class="input-group-prepend">
												<span class="input-group-text">Upload</span>
											</div>
											<div class="custom-file">
												<input type="file" name="uploadFile" class="custom-file-input" style="cursor: pointer;" id="inputGroupFile01" accept=".csv,.xls,.xlsx">
												<label class="custom-file-label" for="inputGroupFile01">Choose file</label>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										<button type="submit" class="btn btn-primary">Upload</button>
									</div>
								</form>
							</div>
						</div>
					</div>

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