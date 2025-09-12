@extends('layouts.nav')
@section('title', 'Permissions')

@section('content')
<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Stagiaires absents Hier/aujourd'hui</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Stagiaires absents Hier/aujourd'hui</li>
							</ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->

				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
                        <table class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>Name</th>
            <th>Group</th>
            <th>Seances</th> 
			<th>Statu</th> 
        </tr>
    </thead>
    <tbody>
        @forelse($stagiaires as $stagiaire)
            <tr>
                <td>{{ $stagiaire['name'] }}</td>
                <td>{{ $stagiaire['num_groupe'] }} - {{ $stagiaire['group_name'] }}</td>
                <td>@foreach(explode(' || ', $stagiaire['statuts']) as $statut)
						@php
							$badgeClass = match($statut) {
								'non justifié' => 'bg-danger',
								'justifié' => 'bg-success',
								'certificat médical' => 'bg-info',
								'autorisé' => 'bg-warning text-dark',
								default => 'bg-secondary',
							};
						@endphp
						<span class="badge {{ $badgeClass }}">{{ $statut }}</span>
					@endforeach
				</td>
				<td>{{ $stagiaire['seances'] }}</td>
            </tr>
			
        @empty
            <tr>
                <td colspan="3" class="text-center">No absences today.</td>
            </tr>
        @endforelse
    </tbody>
</table>
						</div>
					</div>
				</div>



			</div>













@endsection 