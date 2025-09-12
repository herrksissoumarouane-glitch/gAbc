@extends('layouts.nav')
@section('title', 'Permissions')

@section('content')
<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">All Complexs</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">All Roles</li>
							</ol>
						</nav>
					</div>
					<div class="ms-auto">
						<div class="btn-group">
							<a href="#" class="btn btn-primary">Add Complexe</a> 				 
						</div>
					</div>
				</div>
				<!--end breadcrumb-->

				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example" class="table table-striped table-bordered" style="width:100%">
								<thead>
			<tr>
			<th>Sl</th>
				<th>Complexe </th> 
				<th>Code </th> 
				<th>Ville </th> 
				<th>Action</th>  
			</tr>
		</thead>
		<tbody>
	@foreach($complexes as $key => $item)		
			<tr>
				<td> {{ $key+1 }} </td>
				<td>{{ $item->complexe }}</td> 
				<td>{{ $item->code }}</td> 
				<td>{{ $item->ville }}</td> 

				<td>
                <!-- <a href="{{ route('edit.roles',$item->id) }}" class="btn btn-info">Edit</a>
                <a href="{{ route('delete.roles',$item->id) }}" class="btn btn-danger" id="delete" >Delete</a> -->

				</td> 
			</tr>
			@endforeach


		</tbody>
		<tfoot>
			<tr>
				<th>Sl</th>
				<th>Complexe </th> 
				<th>Code </th> 
				<th>Ville </th> 
				<th>Action</th> 
			</tr>
		</tfoot>
	</table>
						</div>
					</div>
				</div>



			</div>




@endsection