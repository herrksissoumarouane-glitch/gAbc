@extends('layouts.nav')
@section('title', 'Permissions')

@section('content')
<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Liste des Efps</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Les Efps</li>
							</ol>
						</nav>
					</div>
					<!-- <div class="ms-auto">
						<div class="btn-group">
		<a href="#" class="btn btn-primary">Ajouter Efp</a> 				 
						</div> -->
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
				<th>Efp </th> 
				<th>Code </th> 
				<th>Ville </th> 
				<th>Action</th>  
			</tr>
		</thead>
		<tbody>
	@foreach($efps as $key => $item)		
			<tr>
				<td> {{ $key+1 }} </td>
				<td>{{ $item->efp }}</td> 
				<td>{{ $item->code }}</td> 
				<td>{{ $item->ville }}</td> 

				<td>
                       
						<button type="button" class="btn btn-success d-inline-flex align-items-center import-btn" 
        style="height: 38px;" data-bs-toggle="modal" 
        data-bs-target="#importModal" data-complexe-id="{{ $item->id }}">
    <i class='bx bx-file'></i>Importer Filieres
</button>
                <!-- <a href="{{ route('edit.roles',$item->id) }}" class="btn btn-info">Edit</a>
                <a href="{{ route('delete.roles',$item->id) }}" class="btn btn-danger" id="delete" >Delete</a> -->

				</td> 
			</tr>
			@endforeach


		</tbody>
		<tfoot>
			<tr>
				<th>Sl</th>
				<th>efp </th> 
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



 <!-- Modal -->
 <div class="modal fade" id="importModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Importer fichier Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="importForm" method="POST" enctype="multipart/form-data" action="">

                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="excel_file" class="form-label">Choisir fichier Excel</label>
                            <input type="file" class="form-control" id="excel_file" name="excel_file" accept=".xlsx, .xls" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Importer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<script>
document.addEventListener("DOMContentLoaded", function () {
    const importButtons = document.querySelectorAll(".import-btn");
    const importForm = document.getElementById("importForm");

    importButtons.forEach(button => {
        button.addEventListener("click", function () {
            const complexeId = this.getAttribute("data-complexe-id");
            if (complexeId) {
                importForm.action = "/efps/import/" + complexeId;
            }
        });
    });
});
</script>


