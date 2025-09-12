@extends('layouts.nav')
@section('title', 'Permissions')

@section('content')
<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Télécharger Absences</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Télécharger Absences</li>
							</ol>
						</nav>
					</div>
					
				</div>
				<!--end breadcrumb-->

				<hr/>
		

                <form method="POST" action="{{ route('download') }}">

                 <!-- Default Checkboxes and radios & Default checkboxes and radios -->
                 <div class="col-xl-12">
                  <div class="card mb-4">
                    <h5 class="card-header"></h5>
                    <!-- Checkboxes and Radios -->
                    <div class="card-body">
                      <div class="row gy-3">
                        <div class="col-md">
                          <div class="mb-3">
                        <label for="exampleFormControlSelect1" class="form-label">Semaine de Formation :</label>
                        <select class="form-select" name="week" id="idWeek" aria-label="Default select example">
                          @foreach ($weeks as $week)
                              <option value="{{ $week['start'] }}" {{ $week['current'] ? 'selected' : '' }} idGroup="0">
                                  {{ $week['label'] }}
                              </option>
                          @endforeach
                        </select>
                      </div>
                        </div>
                        <div class="col-md">
                          
                        </div>
                      </div>
                    </div>
                    <hr class="m-0" />
                    <div class="card-body">
                      <div class="row gy-3">
                        <div class="col-md">
                          
                        @csrf
      @php $grouped = $groups->groupBy('efp'); @endphp

      <!-- Master Checkbox -->
      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="checkAll">
        <label class="form-check-label" for="checkAll">Tout sélectionner / Tout désélectionner</label>
      </div>

      @foreach($grouped as $efpName => $groupList)
        <strong>{{ $efpName }}:</strong>
        @foreach($groupList as $group)
          <div class="form-check mt-3">
            <input class="form-check-input group-checkbox" type="checkbox" name="groups[]" value="{{ $group->group_id }}" id="groupCheckbox{{ $group->group_id }}">
            <label class="form-check-label" for="groupCheckbox{{ $group->group_id }}">
              {{ $group->code_groupe }} {{ $group->num_groupe }} {{ $group->groupe }} [{{ $group->annee_formation }} A]
            </label>
          </div>
        @endforeach
        <br>
      @endforeach

                          <div class="form-check mt-3">
                          <button type="submit" class="btn btn-primary">Télécharger</button></div>
                        </div>
                        
                      </div>
                    </div>
                   
                    <!-- Inline Checkboxes -->
                    
                  </div>



</form>


</div>


@endsection




