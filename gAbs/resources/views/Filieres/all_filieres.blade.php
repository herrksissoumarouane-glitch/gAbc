@extends('layouts.nav')
@section('title', 'Permissions')

@section('content')
<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">All Filieres</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">All Filieres</li>
							</ol>
						</nav>
					</div>
					<!-- <div class="ms-auto">
						<div class="btn-group">
							<a href="#" class="btn btn-primary">Add Complexe</a> 				 
						</div>
					</div> -->
				</div>
				<!--end breadcrumb-->

				<hr/>

        <div class="col-md mb-4 mb-md-0">
                  <div class="accordion mt-3" id="accordionExample">


                  @foreach ($complexes as $complexe)
                  <div class="card accordion-item alert-primary">
                      <h2 class="accordion-header alert-primary" id="heading{{ $loop->index }}">
                        <button
                          type="button"
                          class="accordion-button collapsed alert-primary"
                          data-bs-toggle="collapse"
                          data-bs-target="#accordion{{ $loop->index }}"
                          aria-expanded="false"
                          aria-controls="accordion{{ $loop->index }}"
                        >
                        {{ $complexe->complexe }} ({{ $complexe->ville }})
                        </button>
                      </h2>
                      <div
                        id="accordion{{ $loop->index }}"
                        class="accordion-collapse collapse"
                        aria-labelledby="heading{{ $loop->index }}"
                        data-bs-parent="#accordionExample"
                      >
                        <div class="accordion-body">
                          
                        






                        <div id="accordionIcon" class="accordion mt-3 accordion-without-arrow">



                  
                        @foreach ($complexe->efps as $efp)
                                  <div class="accordion-item card">
                                    <h2 class="accordion-header text-body d-flex justify-content-between" id="accordionIconTwo">
                                      <button
                                        type="button"
                                        class="accordion-button collapsed"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#accordionIcon-{{ $loop->index }}"
                                        aria-controls="accordionIcon-{{ $loop->index }}"
                                      >
                                      {{ $efp->efp }}
                                      </button>
                                    </h2>
                                    <div id="accordionIcon-{{ $loop->index }}" class="accordion-collapse collapse" data-bs-parent="#accordionIcon">
                                      <div class="accordion-body">
                                        
                                      
                                      <ul id="menu-efp-{{ $efp->id }}" class="hidden bg-gray-200 p-2 rounded mt-1">
                        @foreach($efp->filieres as $filiere)
                        <div class="card shadow-none bg-transparent border border-primary mb-3">
                          <div class="card-header">{{ $filiere->filiere }}</div>
                          <div class="card-body">
                            <h5 class="card-title">{{ $filiere->niv }} - Année: {{ $filiere->annee_formation }} </h5>
                            @if(Auth::user()->can('group.add'))
                            <p class="card-text">
                              <form action="{{ route('groupes.import') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                                  @csrf
                                  <input type="hidden" name="filiere_id" class="border p-2" value="{{$filiere->id}}">
                                  <div class="input-group">
                                    <input
                                      type="file"
                                      class="form-control"
                                      id="inputGroupFile04"
                                      name="excel_file"
                                      aria-describedby="inputGroupFileAddon04"
                                      aria-label="Upload"
                                    />
                                    <button class="btn btn-outline-primary" type="submit" id="inputGroupFileAddon04">Importer groupes</button>
                                  </div>
                              </form>
                            </p>
                            @endif
                          </div>
                        </div>
                        @endforeach
                            


                                      </div>
                                    </div>
                                  </div>
                        @endforeach
                   


                        </div>















                        


                        </div>
                      </div>
                    </div>
                  @endforeach



                  </div>
                </div>
              
</div>
@endsection
