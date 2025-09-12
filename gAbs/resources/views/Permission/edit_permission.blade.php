@extends('layouts.nav')
@section('title', 'Permissions')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Add Permission /</span> Add Permission</h4>


  <!-- Basic Layout -->
  <div class="col-xxl">
                  <div class="card mb-4">
                    <div class="card-body">
                    <form id="myForm" method="post" action="{{ route('update.permission') }}" >
                        @csrf
                        <input type="hidden" name="id" value="{{ $permission->id }}">
                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="basic-default-name">Permission Name</label>
                          <div class="col-sm-10">
                            <input type="text" value="{{ $permission->name }}" name="name" class="form-control" id="basic-default-name" placeholder="Permission Name" />
                          </div>
                        </div>
                        <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-default-email">Group Name</label>
                        <div class="col-sm-10">
                            <select name="group_name" class="form-select" aria-label="Default select example">
                                <option selected="">Open this select Group</option>
                                <option value="formateur" {{ $permission->group_name == 'formateur' ? 'selected': ''}}>Formateur</option>
                                <option value="groupe" {{ $permission->group_name == 'groupe' ? 'selected': ''}}>Groupe</option>
                                <option value="stagiaires" {{ $permission->group_name == 'stagiaires' ? 'selected': ''}}>Stagiaires</option>
                                <option value="complexe" {{ $permission->group_name == 'complexe' ? 'selected': ''}}>complexe</option>
                                <option value="efp" {{ $permission->group_name == 'efp' ? 'selected': ''}}>efp</option>
                                <option value="filiere" {{ $permission->group_name == 'filiere' ? 'selected': ''}}>filiere</option>
                                <option value="setting" {{ $permission->group_name == 'setting' ? 'selected': ''}}>Setting</option>
                                <option value="role" {{ $permission->group_name == 'role' ? 'selected': ''}}>Role</option>
                                <option value="admin" {{ $permission->group_name == 'admin' ? 'selected': ''}}>Admin</option>
                            </select>
                          </div>
                        </div>
                       
                        <div class="row justify-content-end">
                          <div class="col-sm-10">
                            <input type="submit" class="btn btn-primary" value="Enregistrer" />
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- Basic with Icons -->

<hr class="my-5" />
@endsection 